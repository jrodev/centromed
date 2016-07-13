<?php

namespace Admin\Model;

use DataBase\Model\Table,
    Zend\Db\Sql\Predicate,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface
;

class CitaTable extends Table implements InputFilterAwareInterface
{
    public $table = 'osi_cita';
    
    public function getCitaByUser($idUser = FALSE, $page=1, $porPage=10)
    {
        if(!$idUser){ throw new \Exception('idsede debe ser numerico mayor q cero.'); }
        $page = ((int)$page<=0)?1:$page;

        $prd = new Predicate\Predicate();
        $sel = $this->sql->select()->columns(array(
            'id'=>new Predicate\Expression('oc.idcita'),
            'sede'=>new Predicate\Expression('os.nom'),
            'spclsta'=>new Predicate\Expression("CONCAT(oe.nom,' ',oe.ape)"),
            'spcldad'=>new Predicate\Expression('oe.esp'),
            'fecha'=>new Predicate\Expression('oc.datehour'),
            'codpago'=>new Predicate\Expression('oc.codpago'),
            'estpago'=>new Predicate\Expression('oc.estpago'),
            'estcita'=>new Predicate\Expression('oc.estcita'),
            'reagen'=>new Predicate\Expression('oc.reagen'),
        ))
        ->from(array('oc'=>'osi_cita'))
        ->join(array('oh'=>'osi_horario'),'oc.idhorario = oh.idhorario',array())//,'left'
        ->join(array('oe'=>'osi_especialista'),'oh.idespecialista = oe.idespecialista',array())//,'left'
        ->join(array('oro'=>'osi_room'),'oh.idroom = oro.idroom',array())//,'left'
        ->join(array('os'=>'osi_sede'),'oro.idsede = os.idsede',array())//,'left'
        ->where(array( $prd->equalTo('oc.idusuario', $idUser) ))
        ->order('oc.idcita DESC')->limit($porPage)->offset(($page-1)*$porPage);

        return $this->sqlFetchAll($sel);
    }
    
    /**
     * Citas por especialista segun sede y dia
     * @return Array Con 2 elementos cant y res (Cantidad total de registros y resultado
     *               para un pagina especifica respectivamente).
     */
    public function getCitaByEsp($idSede=0, $idEsp=0, $codDia=0, $page=1, $text='', $porPage=8)
    {

        if(is_string($idEsp)/* || is_string($codDia)*/ || (int)$idEsp<0/* || (int)$codDia<0*/){
            throw new \Exception('idEsp, codDia debe ser numerico mayor q cero.');
        }
        $page = ((int)$page<=0)?1:$page;

        $prd = new Predicate\Predicate();
        $sel = $this->sql->select()//->columns($cols)
        ->from(array('oc'=>'osi_cita'))
        ->join(array('oh'=>'osi_horario'),'oc.idhorario = oh.idhorario',array())//,'left'
        ->join(array('oe'=>'osi_especialista'),'oh.idespecialista = oe.idespecialista',array())//,'left'
        ->join(array('oro'=>'osi_room'),'oh.idroom = oro.idroom',array())//,'left'
        ->join(array('os'=>'osi_sede'),'oro.idsede = os.idsede',array())
        ->join(array('ou'=>'osi_usuario'),'ou.idusuario = oc.idusuario',array());
        
        $awhere = [];
        if($idSede>0){ $awhere[] = $prd->equalTo('os.idsede',$idSede); }
        if($idEsp >0){ $awhere[] = $prd->equalTo('oe.idespecialista',$idEsp); }

        if($codDia){
            $parts = explode(' - ', $codDia);
            $ini=$parts[0]; $end=$parts[1];
            $awhere[] = $prd->greaterThan('oc.datehour',new Predicate\Expression("'$ini' - interval 1 day"));
            $awhere[] = $prd->lessThan   ('oc.datehour',new Predicate\Expression("'$end' + interval 1 day"));
        }

        if(trim($text)!==''){ // textLike sera una cadena
            $awhere[] = new Predicate\PredicateSet(
                array(
                    //new Predicate\Like('os.nom', "%$text%"),
                    //new Predicate\Like('oe.esp', "%$text%"),
                    new Predicate\Like('ou.nom', "%$text%"),
                    new Predicate\Like('ou.ape', "%$text%"),
                ),
                Predicate\PredicateSet::COMBINED_BY_OR
            );
        }
                
        if(count($awhere)){ $sel->where($awhere); }
        
        // Cantidad de registros
        $sel->columns(array('count'=>new Predicate\Expression('COUNT(*)')));
        $cant = $this->sqlFetchAll($sel);
        
        // Resultado de query  | flog('sel:',$sel->getSqlString());
        $sel->columns(array(
            'id'      => new Predicate\Expression('oc.idcita'),
            'idusu'   => new Predicate\Expression('ou.idusuario'),
            'nom'     => new Predicate\Expression('os.nom'),
            'esp'     => new Predicate\Expression('oe.esp'),
            'hora'    => new Predicate\Expression('oc.datehour'),
            'cli'     => new Predicate\Expression("CONCAT(ou.nom,' ',ou.ape)"),
            'pago'    => new Predicate\Expression('oc.codpago'),
            'estpago' => new Predicate\Expression('oc.estpago'),
            'estcita' => new Predicate\Expression('oc.estcita'),
            'reagen'  => new Predicate\Expression('oc.reagen'),
        ))->order('oc.datehour DESC')->limit($porPage)->offset(($page-1)*$porPage);
        
        return ['cant'=>$cant, 'res'=>$this->sqlFetchAll($sel)];
    }
    
    /**
     * Obtiene los options para el select frmfecha
     * @return Array de values y options
     */
    public function getOptionsFecha()
    {
        $days=array('0'=>'- Elija horario'); 
        $dias=array(
            'Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miercoles','Thursday'=>'Jueves',
            'Friday'=>'Viernes','Saturday'=>'Sabado','Sunday'=>'Domingo',
        ); 
        
        $date=date("Y-m-d");
        
        for($i=0; $i<8; $i++){
            $day  = strftime('%A', strtotime("$date +$i day")); // Nombre dia
            $new  = date('Y-m-d', strtotime("$date +$i day"));  // Nueva fecha
            $newP = date('d-m-Y', strtotime("$date +$i day"));  // Nueva fecha preformateada
            
            $text = "{$dias[$day]}, $newP";
            if($i==0) $text = "Hoy, $text";
            if($i==1) $text = "Mañana, $text";
            
            $days[$new] = $text;
        }
        return $days;
    }
    
    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'frmseguro',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 60,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}