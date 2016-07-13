<?php

namespace Admin\Model;

use DataBase\Model\Table,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\Db\Sql\Predicate
;

class RoomTable extends Table implements InputFilterAwareInterface
{

    public function getRoomsBySede($idsede=0)
    {
        if(!$idsede) throw new Exception('idsede debe ser numerico mayor q cero.');

        $prd = new Predicate\Predicate();
        $sel = $this->sql->select()->columns(array(
            'idroom'=>new Predicate\Expression('r.idroom'),
            'descr'=>new Predicate\Expression('r.descr'),
        ))
        ->from(array('r'=>'osi_room'))
        ->join(array('s'=>'osi_sede'),'s.idsede=r.idsede',array(),'left')
        ->where(array(
            $prd->equalTo('r.idsede', $idsede)->equalTo('r.activo', 1),
        ))->order('r.idroom DESC');
        
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