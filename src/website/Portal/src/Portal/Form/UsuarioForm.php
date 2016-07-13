<?php

namespace Portal\Form;

use Zend\Form\Form,
    Zend\ServiceManager\ServiceManager;

class UsuarioForm extends Form
{
    public function __construct(ServiceManager $sm, $name=null, $options=[])
    {
        // we want to ignore the name passed
        parent::__construct($name, $options); //var_dump($this->getEspecialistas($sm)); exit;

        // ID usuario: 
        $this->add(array(
            'name'=>'idusuario',
            'type'=>'hidden',
            'attributes'=>array('id'=>'idusuario','class'=>'form-control','value'=>'0'),
        ));
        
        // Titulo (genero) usuario: 
        $this->add(array(
            'name'=>'sex',
            'type'=>'select',
            'attributes'=>array('id'=>'ususex','class'=>'form-control','tabindex'=>'2'),
            'options'=>array('value_options'=>array('2'=>'Sr','1'=>'Srta'),'label'=>'Titulo'),
        ));
        
        // Nombre usuario: 
        $this->add(array(
            'name'=>'nom',
            'type'=>'text',
            'attributes'=>array('id'=>'usunom','class'=>'form-control','placeholder'=>'Ingrese nombres','required'=>true,'tabindex'=>'3'),
            'options'=>array('label'=>'Nombres *'),
        ));
        
        // Apellido usuario: 
        $this->add(array(
            'name'=>'ape',
            'type'=>'text',
            'attributes'=>array('id'=>'usuape','class'=>'form-control','placeholder'=>'Ingrese apellidos','required'=>true,'tabindex'=>'4'),
            'options'=>array('label'=>'Apellidos *'),
        ));
        
        // Tipo dcumento usuario: 
        $this->add(array(
            'name'=>'tipodoc',
            'type'=>'select',
            'attributes'=>array('id'=>'usutipodoc','class'=>'form-control','tabindex'=>'5'),
            'options'=>array('value_options'=>array('1'=>'Dni','2'=>'Pasaporte'),'label'=>'Tipo de documento'),
        ));

        // Nro documento usuario: 
        $this->add(array(
            'name'=>'nrodoc',
            'type'=>'text',
            'attributes'=>array('id'=>'usunrodoc','class'=>'form-control','placeholder'=>'Número de documento','required'=>true,'tabindex'=>'6'),
            'options'=>array('label'=>'Numero documento *'),
        ));
        
        // Fecha Nacimiento usuario: 
        $this->add(array(
            'name'=>'fecnac',
            'type'=>'hidden',
            //'attributes'=>array('id'=>'usufecnac',... el id: Lo mantiene el input mask
            'attributes'=>array('class'=>'form-control','placeholder'=>'Fecha de nacimiento __/__/____','required'=>true), //'tabindex'=>'7'
            'options'=>array('label'=>'Fecha de nacimiento *'),
        ));

        // ID distrito usuario: 
        $this->add(array(
            'name'=>'iddistrito',
            'type'=>'select',
            'attributes'=>array('id'=>'iddistrito','class'=>'form-control','tabindex'=>'8'),
            'options'=>array('value_options'=>$this->getDistritos($sm),'label'=>'Distrito *'),
        ));
        
        // Telefono usuario: 
        $this->add(array(
            'name'=>'telf',
            'type'=>'text',
            'attributes'=>array('id'=>'usutelf','class'=>'form-control','placeholder'=>'Ingrese télefono','required'=>true,'tabindex'=>'9'),
            'options'=>array('label'=>'Teléfono *'),
        )); 
        
        // Celular usuario: 
        $this->add(array(
            'name'=>'cel',
            'type'=>'text',
            'attributes'=>array('id'=>'usucel','class'=>'form-control','placeholder'=>'Ingrese celular','required'=>true,'tabindex'=>'10'),
            'options'=>array('label'=>'Celular *'),
        ));
        
        // Mail usuario: 
        $this->add(array(
            'name'=>'mail',
            'type'=>'email',
            'attributes'=>array('id'=>'usumail','class'=>'form-control','placeholder'=>'Ingrese email','required'=>true,'tabindex'=>'11'),
            'options'=>array('label'=>'Email *'),
        ));
        
        // Clave usuario: 
        $this->add(array(
            'name'=>'pass',
            'type'=>'password',
            'attributes'=>array('id'=>'usupass','class'=>'form-control','placeholder'=>'Ingrese clave','required'=>true,'tabindex'=>'13'),
            'options'=>array('label'=>'Contraseña *'),
        ));
        
         $this->add(array(
            'name'=>'submit',
            'type' => 'button',
            'attributes' => array(
                'id' => 'btnRegistrar',
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ),
            'options'=>array('label'=>'Registrarse'), // para button sin esto error en render view
        ));
    }
    
    private function getDistritos($sm)
    {
        $oDist = $sm->get('UsuarioTable')->getDistritosLima();
        $dists = array('0'=>'- Elegir');
        foreach ($oDist as $dist){
            $dists[$dist['iddistrito']] = $dist['nombre'];
        }
        flog('gettype($oDist):',gettype($oDist));
        return $dists;
    }
    
    private function getEspecialistas($sm)
    {
        $oEsps = $sm->get('EspecialistaTable')->fetchAll();
        $esps = array('0'=>'-------------------');
        foreach ($oEsps as $esp){
            $esps[$esp->idespecialista] = $esp->nom." ".$esp->ape;
        }
        return $esps;
    }
}