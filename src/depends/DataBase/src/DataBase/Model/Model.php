<?php

namespace DataBase\Model;

use Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

class Model implements InputFilterAwareInterface
{
   public $fields = array(); // Setting in Class extend

   public function exchangeArray($data)
   {
       foreach ($this->fields as $i=>$val){
            if(key_exists($i, $data)){
                $this->fields[$i] = (!empty($data[$i])) ? $data[$i] : null;
            }
       }
       $this->idcita = (!empty($data['idcita'])) ? $data['idcita'] : null;
       $this->idusuario = (!empty($data['idusuario'])) ? $data['idusuario'] : null;
       $this->idseguro = (!empty($data['idseguro'])) ? $data['idseguro'] : null;
       $this->idsede = (!empty($data['idsede'])) ? $data['idsede'] : null;
       $this->idhorario = (!empty($data['idhorario'])) ? $data['idhorario'] : null;
       $this->codprom = (!empty($data['codprom'])) ? $data['codprom'] : null;
       $this->idasistente = (!empty($data['idasistente'])) ? $data['idasistente'] : null;
       $this->codpago = (!empty($data['codpago'])) ? $data['codpago'] : null;
       $this->fechareg = (!empty($data['fechareg'])) ? $data['fechareg'] : null;
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