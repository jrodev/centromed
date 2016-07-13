<?php

namespace Payments\BaseService;

abstract class PEService//App_Service
{
    protected $_options = array();
    protected static $_instance;
    /*
     * Ejecutar el servicio
     */
    public function _loadService($service, $data)
    {
        //$this->_options['url2'] = WSGENCIP;
        //$this->_options['url3'] = WSCRYPTAB;
        
        switch($service){            
            case 'GenerarCIPMod1':  $url = $this->_options['url2'];break; 
            case 'BlackBox': $url = $this->_options['url3']; 
                break; 
            default :  $url = $this->_options['url']; break;
        }        

        try{
            
            $soap = new \SoapClient($url);  
//var_dump($soap); echo '<br><br>';
            $info = $soap->$service($data);            
            return $info;
        } catch (Exception $e){ var_dump($e);
            return false;
        }
    }
    /*
     * Extraer una instancia de la aplicación
     * @param string $securityPath Carpeta donde se almacenan public.key y private.key
     * @return PagoEfectivo retorna la instancia de la clase
     */
    final public static function getInstance($options = null)
    {
        $class = static::getClass();
        if (!isset(static::$_instance[$class])){	    
            static::$_instance[$class] = new $class($options);
        }
        return static::$_instance[$class];
    }
    /*
     * Captura el nombre de la clase
     */
    final public static function getClass(){
        return get_called_class();
    }
    public function getOptions()
    {
        return $this->_options;
    } 
}