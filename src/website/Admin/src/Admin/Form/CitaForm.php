<?php

namespace Admin\Form;

use Zend\Form\Form,
    Zend\ServiceManager\ServiceManager;

class CitaForm extends Form
{
    private $seguros = array(
        '- Elija tipo de seguro',
        'No, soy particular',
        'Pacifico EPS',
        'Pacifico Seguros',
        'Rimac EPS',
        'Rimac Seguros',
        'Mapfre',
        'La positiva',
        'La positiva sanitas',
    );

    private $sedes = array(
        '- Elija una Sede',
        'Miraflores',
        'Surco-Chacarilla',
        'Los Olivos'
    );

    private $refs = array(
        'Ninguna',
        'Web',
        'Bolantes'
    );

    private $tipoPago = array(
        '1'=>'Pago online con Visa',
        '2'=>'Pago online con MasterCard',
        '3'=>'Pago por bonos por internet',
        '4'=>'Pago en agentes y oficinas del banco',
        '5'=>'Sin pago a travez de admosi como codigo promocional',
    );

    public function __construct(ServiceManager $sm, $name=null, $options=[])
    {
        // we want to ignore the name passed
        parent::__construct($name, $options);

        // Selecciones tipo de seguro: 
        $this->add(array(
            'name' => 'idseguro',
            'type' => 'select',
            'options' => array('value_options'=>$this->seguros, 'label'=>'Tipo de Seguro'),
            'attributes' => array('id'=>'idseguro', 'class'=>'form-control'),
        ));
        
        // Seleccion de sede:
        $this->add(array(
            'name' => 'frmsede',
            'type' => 'select',
            'options' => array('value_options'=>$this->sedes, 'label'=>'Sede'),
            'attributes'=>array('id'=>'frmsede', 'class'=>'form-control', 'disabled'=>true),
        ));
        
        // Seleccion de Fecha:
        $this->add(array(
            'name' => 'frmhorario',
            'type' => 'select',
            'options' => array('value_options'=>$this->getOptionsFecha($sm), 'label'=>'Fecha'),
            'attributes' => array('id'=>'frmhorario', 'class'=>'form-control', 'disabled'=>true),
        ));
        
        // Seleccion de especialistas:
        $this->add(array(
            'name' => 'frmespec',
            'type' => 'select',
            'options' => array(
                //'value_options'=>$this->getEspecialistas($sm), // No se usa! se llena por FrontEnd
               'label' => 'Especialista',
            ),
            'attributes' => array('id'=>'frmespec', 'class'=>'form-control','disabled'=>true),
        ));
        
        // Seleccion de horario: (Se llena al elegir un tag de hora)
        $this->add(array(
            'name' => 'idhorario',
            'type' => 'hidden',
            'attributes' => array('id'=>'idhorario'),
            'options' => array('label'=>'Paso3: Elige horario y especialista'),
        ));

        // Seleccion de hora de cita: (Se llena al elegir un tag de hora)
        $this->add(array(
            'name' => 'datehour',
            'type' => 'hidden',
            'attributes' => array('id'=>'datehour'),
            'options' => array('label'=>'Elegir hora de la cita'),
        ));

        // Seleccion de Referencia: 
        $this->add(array(
            'name' => 'frmref',
            'type' => 'select',
            'options' => array('value_options'=>$this->refs, 'label'=>'Referencia Principal'),
            'attributes' => array('id'=>'frmref', 'class'=>'form-control'),
        ));
        
        // Seleccion codigo promocional: 
        /*$this->add(array(
            'name' => 'codprom',
            'type' => 'text',
            'attributes' => array('id'=>'frmcodprom', 'class'=>'form-control', 'autocomplete'=>'off'),
            'options' => array('label'=>'Codigo promocional'),
        ));*/

        // Ayuda telefonica de asistente
        $this->add(array(
            'name' => 'idasistente',
            'type' => 'select',
            'options' => array(
                'value_options' => array('Graciela Avila'),
            ),
            'attributes' => array('id'=>'idasistente', 'class'=>'form-control'),
            'options' => array('label'=>'Admision'),
        ));
        
        // Medio de pago 
        $this->add(array(
            'name' => 'codpago',
            'type' => 'radio',
            'attributes' => array('id'=>'frmcodpago'),
            'options' => array('label'=>'Seleccione una forma de pago', 'value_options'=>$this->tipoPago),
            
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
    
    private function getOptionsFecha($sm)
    {
        $citaTable = $sm->get('CitaTable');
        return $citaTable->getOptionsFecha();
    }
    
    /**
     * 
     * @param type $sm
     * @return string
     */
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