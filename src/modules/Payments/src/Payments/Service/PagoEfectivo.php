<?php

namespace Payments\Service;

use Payments\BaseService\PEService,
    Payments\Service\PECrypto;

class PagoEfectivo extends PEService {
    
    public static $_instance;
  
    protected $_options = array();
    protected $_crypto;
    protected $_lastPayRequest;
    
    /*
     * Constructor de la aplicación
     * @param string $securityPath Carpeta donde se almacenan public.key y private.key
     */
    public function __construct($options = null)
    {	
        if (isset($options['servPars'])){
            $this->_options = array_merge($this->_options, $options['servPars']);
            $this->_options['url2'] = $options['extra']['WSGENCIP'];  // Seteando prop de class parent
            $this->_options['url3'] = $options['extra']['WSCRYPTAB']; // Seteando prop de class parent
        }
        
        // Correcion de dummy example
        $this->_options['crypto']['url2'] = $this->_options['url2'];
        $this->_options['crypto']['url3'] = $this->_options['url3'];
        $this->_crypto = PECrypto::getInstance($this->_options['crypto']);
    }
    
    /*
     * Solicitar Pago
     * @param string $xml XML de envio de solicitud de pago
     * @return SimpleXMLElement Resultado de Servicio Ejm:
     * SimpleXMLElement Object
     * (
     *     [iDResSolPago] => 33
     *     [CodTrans] => 3300020
     *     [Token] => 2a3848a4-183a-490c-813a-40d90e82ef96
     *     [Fecha] => 21/02/2012 11:26:27 a.m.
     * )
     */
    public function solicitarPago( $xml )
    {  //var_dump( $this->_options['apiKey'], $this->_crypto->signer($xml), $this->_crypto->encrypt($xml)); exit();
       $funcion = 'GenerarCIPMod1';
		$info = $this->_loadService($funcion,
			array( 'request' =>
			array('CodServ' => $this->_options['apiKey'],
				'Firma' => $this->_crypto->signer($xml),
				'Xml' => $this->_crypto->encrypt($xml))));    
		$info = $info->GenerarCIPMod1Result;                                 
     
        if ($info->Estado != 1) throw new \Exception('Pago Efectivo : ' . $info->Mensaje);
        return simplexml_load_string($this->_crypto->decrypt($info->Xml));
    }
    
    /*
     * Solicitar Pago
     * @param string $xml XML de envio de solicitud de pago
     * @return SimpleXMLElement Resultado de Servicio Ejm:
     */
    public function eliminarPago($CIP)
    {
        $info = $this->_loadService('EliminarCIP',
                        array( 'request' =>
                        array('CAPI' => $this->_options['capi'],
                            'CClave' => $this->_options['cclave'],
                            'CIP' => (string)$CIP)));        
        
        $info = $info->EliminarCIPResult;
        return $info;
    }
    
    /*
     * Solicitar Pago
     * @param string $xml XML de envio de solicitud de pago
     * @return SimpleXMLElement Resultado de Servicio Ejm:
     */
    public function consultarCip($CIP)
    {        
        $info = $this->_loadService('ConsultarCIP',
                        array( 'request' =>
                        array('CAPI' => $this->_options['capi'],
                            'CClave' => $this->_options['cclave'],
                            'CIPS' => (string)$CIP)));
        
        $info = $info->ConsultarCIPResult;
        return $info;
    }
    
    /*
     * 
     */
    public function consultarSolicitudPago($xml)
    {
        
        if (gettype($xml) == 'integer'){ 
            $xml = '<?xml version="1.0" encoding="utf-8" ?><ConsultarPago> <idResSolPago>'.$xml.'</idResSolPago></ConsultarPago>';
        }
        
        $info = $this->_loadService('ConsultarSolicitudPago',
                        array( 'request' =>
                        array('cServ' => $this->_options['apiKey'],
                            'CClave' => $this->_crypto->signer($xml),
                            'Xml' => $this->_crypto->encrypt($xml))));                      
        $info = $info->ConsultarSolicitudPagoResult;
        
        if ($info->Estado != 1) throw new \Exception('Pago Efectivo : ' . $info->Mensaje);
        return simplexml_load_string($this->_crypto->decrypt($info->Xml));
    }
    
    public function desencriptarData($string)
    {
        return $this->_crypto->decrypt($string);
    }
    
    //Adicional para generar un log - pruebas locales de notificacion
    //Para generar una nueva linea en el archivo de LOG
        public function addRowFileLog($file, $data){
            $fp = fopen($file, 'a+') or die ("Error opening file in write mode!");  
            
            fwrite($fp, str_pad($data, 55));            
            fwrite($fp, "\n\r");
            fclose($fp);
        }
        
    //Para la modalidad 1
     //Obtener la imagen de codigo de barras 
        public function getCodigoBarra($cip){            
           $img = $this->_options['imgbarra'] . '?codigo=' . $this->_crypto->codifica('cip=' . $cip . '|capi=' . $this->_options['capi']. '|cclave=' . $this->_options['cclave']);
           return $img;
       }   
        
}