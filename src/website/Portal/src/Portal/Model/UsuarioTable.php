<?php

namespace Portal\Model;

use DataBase\Model\Table,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface
;

class UsuarioTable extends Table implements InputFilterAwareInterface
{
    /**
     * Conpara array de field con valores para ver si hacen match con la tabla
     * osi_usuario
     * 
     */
    public function andWhere($colsVals=array())
    {
        if( !(is_array($colsVals) && count($colsVals)) )
            throw new \Exception('UsuarioTable->andWhere: $colsVals No es array o esta vacio');
        
        $sel = $this->sql->select()->from('osi_usuario')->where($colsVals);
        //flog('UsuarioTable->getSqlString:',$sel->getSqlString());
        
        return $this->sqlFetchAll($sel);
    }
    
    /*
     * Get Distritos de lima
     */
    public function getDistritosLima()
    {
        $sel = $this->sql->select()
                         ->from('osi_distrito')
                         ->where(array('idprov'=>'150100'))
                         ->order('nombre ASC');
        return $this->sqlFetchAll($sel);
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    /**
     * Obteniendo los filtros para el formulario
     * @param Adapater $adp Adatador de conexion a BD.
     * @param bolean $isUpdate Flag para saber si el form se usa para UPD o INS.
     * @return Array Retorna Array de Validadores para el formulario dado.
     */
    public function getInputFilter($adp = null, $isUpdate=FALSE)
    {   
        flog('UsuarioTable->isUpdate',$isUpdate);
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $textValid = array(
                'required'=>true,
                'filters'=>array(array('name'=>'StripTags'), array('name'=>'StringTrim')),
                'validators'=>array(
                    array('name'=>'StringLength','options'=>array('encoding'=>'UTF-8', 'min'=>1, 'max'=>60)),
                ),
            );
            
            $int1Length = array(
                'required'=>true,
                'filters'=>array(array('name'=>'StripTags'), array('name'=>'StringTrim')),
                'validators'=>array(
                    array('name'=>'StringLength','options'=>array('encoding'=>'UTF-8', 'min'=>1, 'max'=>1)),
                ),
            );
            
            $inputFilter->add(array(
                'name'    =>'idusuario',
                'required'=>true,
                'filters' =>array(array('name'=>'Int')),
            ));

            $inputFilter->add(array('name'=>'nom')+$textValid);
            
            $inputFilter->add(array('name'=>'ape')+$textValid);
            
            // SI ES INSERT: Este inputFilter sera necesaria
            if(!$isUpdate){
                $inputFilter->add(array('name'=>'tipodoc')+$int1Length);
            }
            
            // Validacion de nro documento condicionada -------------------------------------------------------
            $nroDocValidator = array(array(
                'name'=>'Between',
                'options'=>array('min'=>10000000, 'max'=>99999999, 'message'=>'Numerico de 8 caracteres')
            ));
            
            // SI ES INSERT: Se adiciona validador de nroddoc unico en la BD.
            if(!$isUpdate){ 
                $nroDocValidator[] = new \Zend\Validator\Db\NoRecordExists(array(
                    'table'  => 'osi_usuario', 
                    'field'  => 'nrodoc', 
                    'adapter'=> $adp,
                    'message'=> 'Nro de documento ya existe!', 
                ));
            }
            //flog('sqlNoRecordExistsSQL:', $nroDocValidator[1]->getSelect()->getSqlString());

            // SI ES INSERT: Este inputFilter sera necesario
            if(!$isUpdate){ 
                $inputFilter->add(array(
                    'name'=>'nrodoc',
                    'required'=>true,
                    'filters'=>array(array('name'=>'StripTags'), array('name'=>'StringTrim'),array('name'=>'Int')),
                    'validators'=>$nroDocValidator,
                ));
            }
            // ------------------------------------------------------------------------------------------------
           
            $inputFilter->add(array('name'=>'fecnac','required'=>true));
            
            
            $inputFilter->add(array(
                'name'=>'iddistrito',
                'required'=>true,
                'validators'=>array(
                    array(
                        'name'=>'Between',
                        'options'=>array('encoding'=>'UTF-8', 'min'=>1, 'message'=>'Elija distrito')
                    ),
                ),
            ));
            
            $inputFilter->add(array('name'=>'telf','required'=>true));
            
            $inputFilter->add(array('name'=>'cel','required'=>true));
            
            if(is_null($adp)){ throw new \Exception('usuarioTable->getInputFilter: Adapter es necesario'); }
            
            $inputFilter->add(array(
                'name'=>'mail',
                'required'=>true,
                /*'validators'=>array(
                    // Validando que el mail registro NO exista
                    new \Zend\Validator\Db\NoRecordExists(array(
                        'table'  => 'osi_usuario', 
                        'field'  => 'mail', 
                        'adapter'=> $adp,
                        'message'=> 'Correo ya existe!', 
                    )),
                ),*/
            ));
            
            $inputFilter->add(array('name'=>'pass','required'=>true));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}