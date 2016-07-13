<?php

namespace BaseApp\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/*
 * NOTE: todos los metodos empezaran con get o set sino sera tomando como un plugin
 *       y lanzara error: plugin no registrado en la aplicacion.
 */
abstract class PortalController extends AbstractActionController {
    
    /**
     * Proceso de pago general
     * @param Array $aPost Datos del formulario de producto o servicio a comprar.
     * @param Array $dataUser Datos de usuario en sesion para generar el pago.
     * @param Bool $ext Si el pago se hace desde otra pagina (como centromedicoosi)
     * @return String QueryString para la redireccion en el action solicitarCita 
     */
    public function setPagoCita($aPost=array(), $dataUser=array(), $ext = FALSE)
    {
        if(!is_array($aPost) || !is_array($dataUser) || !count($aPost) || !count($dataUser)){
            throw new Exception('PortalCtrll->setPagoCita: $aPost or $dataUser vacios');
        }
        
        $sm = $this->getServiceLocator();
        $citaTbl= $sm->get('CitaTable');
        
        
        
        $saveRow = $citaTbl->saveRow($aPost); // pre-guardando registro,...
        
        $uuid = $this->genUuid();
        $dataPay = array('idpago'=>'pay-'.$uuid, 'codtrans'=>$uuid, 'petoken'=>FALSE);
        
        if($saveRow['codpago']=='4'){
            $dataPay = $this->setPayWithPagoEfectivo($dataUser);
        }
        $saveRow['idpago']   = $dataPay['idpago'];
        $saveRow['codtrans'] = $dataPay['codtrans'];
        $citaTbl->saveRow($saveRow); // Actualizando registro pre-guardado
        
        $uriVars = array();
        if($ext){ $uriVars[] = 'ext'; }
        $uriVars[] = $dataPay['petoken']?'petoken='.$dataPay['petoken']:'ok='.$saveRow['codpago'];
        $queryStr = count($uriVars)?'?'.implode('&',$uriVars):'';

        flog('$saveRow,$aPost',[$saveRow,$aPost]);

        return $queryStr;
    }
    
    /***
     * 
     */
    public function setPayWithAlignet($dataUser=array(), \gnupg $gnupg)
    {
        if(!is_array($dataUser) || !count($dataUser)){
            throw new Exception('PortalCtrll->setPayWithAlignet: $dataUser vacios');
        }
        
        $sm = $this->getServiceLocator();
        $gnupg = $sm->get('AlignetGnupg');

        $purchaseOperationNumber = $gnupg->encrypt("".$this->random_numbers(5));
        $purchaseAmount = $gnupg->encrypt(4500);
        $purchaseCurrencyCode = $gnupg->encrypt(604);

        $count;
        $purchaseOperationNumber = str_replace('%5Cn', '%0A', urlencode($purchaseOperationNumber), $count);
        $purchaseAmount          = str_replace('%5Cn', '%0A', urlencode($purchaseAmount), $count);
        $purchaseCurrencyCode    = str_replace('%5Cn', '%0A', urlencode($purchaseCurrencyCode), $count);
        
    }
    
    /**
     * 
     * @return type
     */
    public function setAlignetParams()
    {
        $config = $this->getServiceLocator()->get('config');
        $gpg    = $this->getServiceLocator()->get('AlignetGpg');
        
        $encOperNumber = trim($gpg->encrypt("".$this->randomNumbers(5),'vpososiALG'));
        $encAmount     = trim($gpg->encrypt(3500,'vpososiALG'));
        $encCurrCode   = trim($gpg->encrypt(604,'vpososiALG'));

        return array(
            'action' => $config['alignet']['action'],
            'inputs' => $config['alignet']['inputs'] + array(
                'purchaseOperationNumber'=> str_replace('%5Cn', '%0A', urlencode( $encOperNumber )),
                'purchaseAmount'         => str_replace('%5Cn', '%0A', urlencode( $encAmount )),
                'purchaseCurrencyCode'   => str_replace('%5Cn', '%0A', urlencode( $encCurrCode )),
            ),
        );
    }
    
    /**
     * Proceso de pago por pago efectivo
     * @param Array $aPost Datos del formulario de producto o servicio a comprar.
     * @param Array $dataUser Datos de usuario en sesion para generar el pago.
     * @return Array ( idpago(CIP), contrans [, petoken(para la url de PEf)] )
     */
    public function setPayWithPagoEfectivo($dataUser=array())
    {
        if(!is_array($dataUser) || !count($dataUser)){
            throw new Exception('PortalCtrll->setPayWithPagoEfectivo: $dataUser vacios');
        }
        
        $sm = $this->getServiceLocator();

        $pagoEf = $sm->get('PagoEfectivo');
        $xmlReq = $sm->get('PEXmlRequest');
        
        $config   = $sm->get('config');
        $servPars = $config['pagos']['pago-efectivo']['servPars'];
        
        $codTrans = $servPars['apiKey'].'-'.$this->genUuid(); // Codigo de transaccion autogenerado por nuestra webApp.

        $payReq = $pagoEf->solicitarPago($this->setXmlPE($xmlReq, $codTrans, $dataUser));
        flog('PortalController->setPayWithPagoEfectivo->$payReq:',$payReq);
        
        return array(
            'idpago'   => trim($payReq->CIP->NumeroOrdenPago), // $payReq->CIP->IdOrdenPago; // sin ceros a la izq
            'codtrans' => trim($codTrans),
            'petoken'  => trim($payReq->Token),
        );
        
    }
    
    /**
     * Llenado de datos XML para envias a pagoefectivo
     */
    private function setXmlPE($xmlReq, $codTrans, $dataUser)
    {
        $merchanId = explode('-', $codTrans);
        $xmlReq->addContenido(array(
            'IdMoneda'=>'1', 'Total'=>'35', 'MetodosPago'=>'1,2', 'CodServicio'=>$merchanId[0],
            'Codtransaccion'=>$codTrans, 'EmailComercio'=>'amac@centromedicoosi.com',
            'FechaAExpirar' => date('d/m/Y').' 23:00:00', //Este valor debe ser dinamico
            'UsuarioId'=>$dataUser['idusuario'], 'DataAdicional'=>'Testing pre cip fisioterapiaosi',
            'UsuarioNombre'=>$dataUser['nom'], 'UsuarioApellidos'=>$dataUser['ape'],
            'UsuarioLocalidad'=>'LIMA', 'UsuarioProvincia'=>'LIMA', 'UsuarioPais'=>'PERU',
            'UsuarioAlias'=>'Anonimo'.$dataUser['idusuario'], 'UsuarioTipoDoc'=>$dataUser['tipodoc'],
            'UsuarioNumeroDoc'=>$dataUser['nrodoc'], 'UsuarioEmail'=>$dataUser['mail'],
            'ConceptoPago'=>'Pago Cita en OSI a travez de fisioterapiaosi.com'
        ))->addDetalle(array(array(
            'Cod_Origen'=>'CT','TipoOrigen'=>'TO','ConceptoPago'=>'Pago Cita en OSI','Importe'=>'35'
        )));
        return $xmlReq;
    }

    /**
     * Obtiene los options para el select frmhorario
     * @return String Cadena de options html separados por '\n'
     */
    public function getOptionsHorario()
    {
        $days=array(); 
        $dias=array(
            'Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miercoles','Thursday'=>'Jueves',
            'Friday'=>'Viernes','Saturday'=>'Sabado','Sunday'=>'Domingo',
        ); 
        
        $date=date("Y-m-d");
        
        for($i=0; $i<8; $i++){
            $day  = strftime('%A', strtotime("$date +$i day")); // Nombre dia
            $new  = date('Y-m-d', strtotime("$date +$i day"));  // Nueva fecha
            $newP = date('d-m-Y', strtotime("$date +$i day"));  // Nueva fecha preformateada
            
            $text = "{$dias[$day]}, $newP";
            if($i==0) $text = "Hoy, $text";
            if($i==1) $text = "Ma&ntilde;ana, $text";
            
            $days[] = "<option value='$new'>$text</option>";
        }
        return "\n".implode("\n", $days)."\n";
    }
    
    /**
     * Generando alfanumericos aleatorios
     * @param Integer $len Longitud del numero aleatorio resultante.
     * @return String Alfanumerico unico generado.
     */
    public function genUuid($len=8) {

        $hex = md5("fisioosi" . uniqid("", true));
        $pack = pack('H*', $hex);
        $tmp =  base64_encode($pack);
        $uid = preg_replace("#(*UTF8)[^A-Za-z0-9]#", "", $tmp);
        $len = max(4, min(128, $len));
        while (strlen($uid) < $len){ $uid .= gen_uuid(22); }
        return substr($uid, 0, $len);
    }
    
    /**
     * Retorna numeros aleatorios de longitud definida
     * @param Number $digits longitud del numero aleatorio
     * @return Number numero aleatorio
     */
    function randomNumbers($digits)
    {
        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;
        return mt_rand($min, $max);
    }
}
