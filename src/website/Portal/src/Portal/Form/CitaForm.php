<?php

namespace Portal\Form;

use Zend\Form\Form,
    Zend\ServiceManager\ServiceManager;

class CitaForm extends Form
{
    public function __construct(ServiceManager $sm, $name=null, $options=[])
    {
        // we want to ignore the name passed
        parent::__construct($name, $options); //var_dump($this->getEspecialistas($sm)); exit;

        // Selecciones tipo de seguro: 
        $this->add(array(
           'name' => 'idseguro',
           'type' => 'select',
           'options' => array(
                'value_options' => array(
                    '- Elija tipo de seguro',
                    'No, soy particular',
                    'Pacifico EPS',
                    'Pacifico Seguros',
                    'Rimac EPS',
                    'Rimac Seguros',
                    'Mapfre',
                    'La positiva',
                    'La positiva sanitas',
                 ),
               'label' => 'Paso1: ¿Cuentas con algun seguro ?',
            ),
            'attributes' => array(
                'id' => 'idseguro',
                'class' => 'form-control input_width6', // Clases de bootstrap twiter
            ),
        ));
        
        // Seleccion de sede:
        $this->add(array(
            'name' => 'frmsede',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    '- Elija una Sede',
                    'Miraflores',
                    'Surco-Chacarilla',
                    'Los Olivos',
                 ),
                'label' => 'Paso2: Elige la sede mas cercana a ti',
            ),
            'attributes' => array(
                'id' => 'frmsede',
                'class' => 'form-control',
                'disabled' => true,
            ),
        ));
        
        // Seleccion de especialistas: 
        $this->add(array(
           'name' => 'frmespec',
           'type' => 'select',
           'options' => array(
                'value_options' => $this->getEspecialistas($sm),
               'label' => 'Paso3: Elige horario y especialista',
            ),
            'attributes' => array(
                'id' => 'frmespec',
                'class' => 'form-control input_width6', // Clases de bootstrap twiter
            ),
        ));
        
        // Seleccion de horario: 
        $this->add(array(
            'name' => 'idhorario',
            'type' => 'hidden',
            'attributes' => array('id'=>'idhorario'),
            'options' => array('label'=>'Paso3: Elige horario y especialista'),
        ));
        
        // Seleccion de hora de cita: 
        $this->add(array(
            'name' => 'datehour',
            'type' => 'hidden',
            'attributes' => array('id'=>'datehour'),
            'options' => array('label'=>'Elegir hora de la cita'),
        ));
        
        // Seleccion codigo promocional: 
        $this->add(array(
            'name' => 'codprom',
            'type' => 'text',
            'attributes' => array('id'=>'frmcodprom', 'class'=>'form-control', 'autocomplete'=>'off'),
            'options' => array('label'=>'Codigo promocional'),
        ));

        // Ayuda telefonica de asistente
        $this->add(array(
            'name' => 'idasistente',
            'type' => 'select',
            'options' => array(
                'value_options' => array('- Elija especialista'),
            ),
            'attributes' => array('id'=>'idasistente', 'class'=>'form-control input_width6'),
            'options' => array('label'=>'¿Recibio ayuda telefonica?'),
        ));
        
        // Medio de pago 
        $this->add(array(
            'name' => 'codpago',
            'type' => 'radio',
            'attributes' => array('id'=>'frmcodpago'),
            'options' => array(
                'label'=>'Seleccione una forma de pago',
                'value_options' => array(
                    '1'=>'Pago online con Visa',
                    '2'=>'Pago online con MasterCard',
                    '3'=>'Pago por bonos por internet',
                    '4'=>'Pago en agentes y oficinas del banco',
                    '5'=>'Sin pago a travez de admosi como codigo promocional',
                ),
            ),
            
        ));
        
        /*
        $this->add(array(
            'name' => 'artist',
            'type' => 'Text',
            'options' => array(
                'label' => 'Artist',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));*/
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