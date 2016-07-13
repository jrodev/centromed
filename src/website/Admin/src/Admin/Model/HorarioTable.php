<?php

namespace Admin\Model;

use DataBase\Model\Table,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\Db\Sql\Predicate
;

class HorarioTable extends Table implements InputFilterAwareInterface 
{
    // obiamente un Room esta asociado a una sede en particular
    public function getHorarioByRoom($idsede=0, $idroom=0, $weekStart=0,$weekEnd=0) 
    {
        //if(!$idroom) throw new \Exception('idsede y idroom numeric mayor a cero');
        flog('$idroom:',$idroom);
        
        $prd = new Predicate\Predicate();
        $sel = $this->sql->select()->columns(array(
            'idhorario'       => new Predicate\Expression('h.idhorario'),
            'startEditable'   => new Predicate\Expression('(COUNT(c.idcita)=0)'),
            'durationEditable'=> new Predicate\Expression('(COUNT(c.idcita)=0)'),
            'idespecialista'  => new Predicate\Expression('h.idespecialista'),
            'idroom'          => new Predicate\Expression('h.idroom'),
            
            // Datos para FullCalendar
            'title'         =>new Predicate\Expression('e.nom'),
            'start'         =>new Predicate\Expression('h.horaini'),
            'end'           =>new Predicate\Expression('h.horafin'),
        ))
        ->from(array('h'=>'osi_horario'))
        ->join(array('c'=>'osi_cita'),'h.idhorario=c.idhorario',array(),'left') // Citas por horario
        ->join(array('e'=>'osi_especialista'),'h.idespecialista=e.idespecialista',array(),'left');

        // Si se envia idsede y no idroom.
        if((int)$idsede && !(int)$idroom){
            $sel->join(array('r'=>'osi_room'),'h.idroom=r.idroom',array(),'left')
                ->join(array('s'=>'osi_sede'),'r.idsede=s.idsede',array(),'left');
            $sel->where(array($prd->equalTo('r.idsede', $idsede)));
        }
        
        // Si se envia idroom (por mas que envie idsede).
        if((int)$idroom){ $sel->where(array($prd->equalTo('h.idroom', $idroom))); }
        
        // Si se envian Ambos (inicio y fin de la semena)
        if((int)$weekStart && (int)$weekEnd){
            $sel->where(array(
                $prd->greaterThan('h.horaini',new Predicate\Expression("'$weekStart' - interval 1 day")),
                $prd->lessThan('h.horafin'   ,new Predicate\Expression("'$weekEnd' + interval 1 day"))
            ));
        }
        $sel->group('h.idhorario'); // Agrupando por horarios debido al join con 'osi_cita'
        //flog($sel->getSqlString());
        return $this->sqlFetchAll($sel);
    }
    
    /**
     * Citas por idsede y fecha para un especialista dado
     */
    public function getCitasByEspec($idsede=FALSE, $idespec=FALSE, $fecha=FALSE)
    {
        if(!$fecha || !$idsede || !$idespec){ throw new \Exception('fecha,idsede,idespec necesarios'); }
        
        $prd = new Predicate\Predicate();
        $sel = $this->sql->select()->columns(array(
            'idespec'=> new Predicate\Expression('h.idespecialista'),
            'nom'    => new Predicate\Expression('e.nom'),
            'idshs'  => new Predicate\Expression("GROUP_CONCAT(DISTINCT h.idhorario SEPARATOR '|')"),
            'inis'   => new Predicate\Expression("GROUP_CONCAT(DISTINCT h.horaini SEPARATOR '|')"),
            'fins'   => new Predicate\Expression("GROUP_CONCAT(DISTINCT h.horafin SEPARATOR '|')"),
            'citas'  => new Predicate\Expression("GROUP_CONCAT(c.datehour SEPARATOR '|')"),            
        ))
        ->from(array('h'=>'osi_horario'))
        ->join(array('r'=>'osi_room'),'h.idroom=r.idroom',array())
        ->join(array('e'=>'osi_especialista'),'e.idespecialista=h.idespecialista',array())
        ->join(array('s'=>'osi_sede'),'r.idsede=s.idsede',array())
        ->join(array('c'=>'osi_cita'),'c.idhorario=h.idhorario',array(),'left')
        ->where(array(
            new Predicate\Like('h.horaini', "%$fecha%"),  // antes: h.fecha
            $prd->equalTo('r.idsede', $idsede), 
            $prd->equalTo('h.idespecialista', $idespec),
        ))
        ->group('e.idespecialista'); //flog('sql:',$sel->getSqlString());
        return $this->sqlFetchAll($sel);
    }
    
    /**
     * Obtiene las citas para un idhorario especifico (usado al hacer click en un horario
     * en el calendario de horarios en admin/horario/index).
     * @param type $idHorario
     * @throws \Exception
     */
    public function getCitasByIdHorario($idHorario=FALSE){
        
        if(!$idHorario){ throw new \Exception('idhorario necesarios'); }
        
        $prd = new Predicate\Predicate();
        
        $sel = $this->sql->select()->columns(array(
            'idcita'   => new Predicate\Expression('c.idcita'),
            'nom'      => new Predicate\Expression('u.nom'),
            'datehour' => new Predicate\Expression('c.datehour'),
            'codpago'  => new Predicate\Expression('c.codpago'),
            'estcita'  => new Predicate\Expression('c.estcita'),
        ))->from(array('c'=>'osi_cita'))
          ->join(array('u'=>'osi_usuario'),'c.idusuario = u.idusuario',array())
          ->where($prd->equalTo('c.idhorario', $idHorario))
        ;
        return $this->sqlFetchAll($sel);
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