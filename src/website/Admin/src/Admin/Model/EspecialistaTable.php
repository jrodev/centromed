<?php

namespace Admin\Model;

use Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
        
    DataBase\Model\Table,
    Zend\Db\Sql\Predicate,
    Zend\Db\Sql\Sql
;

class EspecialistaTable extends Table implements InputFilterAwareInterface
{

    public function getEspecialistas($porPage=1,$page=1)
    {
        $adp = $this->tableGateway->getAdapter();
        $sql = new Sql($adp);
        $sel = $sql->select();
        
        $sel->from('osi_especialista')->limit($porPage)->offset(($page-1)*$porPage);
        $selStr = $sql->getSqlStringForSqlObject($sel);
        return $adp->query($selStr, 'execute')->toArray();
    }

    public function getEspecByFecha($idsede=0, $fecha=FALSE) // obiamente un Room esta asociado a una sede en particular
    {
        if(!$fecha || !$idsede) throw new \Exception('fecha or idsede not null or cero');
        flog('$fecha:',$fecha);
        
        $prd = new Predicate\Predicate();
        $sel = $this->sql->select()->columns(array(
            'idhorario'     => new Predicate\Expression('h.idhorario'),
            'idespecialista'=> new Predicate\Expression('h.idespecialista'),
            'nom'           => new Predicate\Expression('e.nom'),
            'ape'           => new Predicate\Expression('e.ape'),
            'horaini'       => new Predicate\Expression('h.horaini'),
            'horafin'       => new Predicate\Expression('h.horafin'),
        ))
        ->from(array('h'=>'osi_horario'))
        ->join(array('r'=>'osi_room'),'h.idroom=r.idroom',array(),'left')
        ->join(array('e'=>'osi_especialista'),'h.idespecialista=e.idespecialista',array(),'left')
        ->join(array('s'=>'osi_sede'),'r.idsede=s.idsede',array(),'left')
        ->where(array(
            $prd->equalTo('r.idsede', $idsede),
            new Predicate\Like('h.horaini', "%$fecha%"), // antes: h.fecha
        ))
        ->order('e.nom ASC')->group('h.idespecialista'); // flog('sql:',$sel->getSqlString());
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