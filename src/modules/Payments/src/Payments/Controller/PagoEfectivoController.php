<?php

namespace Payments\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Admin\Model\CitaTable,
    Zend\View\Model\ViewModel;

class PagoEfectivoController extends AbstractActionController
{
    public function notificationsAction(CitaTable $citaTable=null)
    {
        $DEBUG = FALSE;

        (new ViewModel())->setTerminal(true);
        $pagoEfectivo = $this->getServiceLocator()->get('pagoEfectivo');
        $citaTable    = $this->getServiceLocator()->get('CitaTable');
        $request      = $this->getRequest();
        
        //$res = $citaTable->updateRow(['codpago'=>1],['idcita'=>3]);
        //var_dump($res); exit;
        
        // No es POST
        if(!$request->isPost()){ echo "No es POST!"; exit; }
        
        // Es POST
        if ($DEBUG) {
            $post = array(
                'version' => '2',
                'data' => '4A6766D906D5C9E9395AC3C3AD9473C0B0645E91596D48D201AA8EDED6C18FBF3746E3ED668933A8F6A72434A871D6127903CB995EBF92AC4511CE8362D4E915406A64AF96EE99DF95A4C6C4B0DDDE6B9E446B2ED323B597CE48916740735670F118A7782B288F1AA476836DB6BAB82087A956C3662F327E1B4F57B15B214C6D62D3DF599EBBB0F8C8B8FFD31D6323FF340690102B2C0C81E0381ABCEB370106586163E13BF2CFA218FF04BAA555778B1E4164126477FB4800893755D2075E631E4164126477FB4835FF2A78E335231A233D50D69F97DD6DDA4EF15D8F006AE707204EE6DA920E16700E23A5B89B3DEA7894B74C2D94141D39BE7D55F85268BAC0E8BF946B7A5E06FCD28F15BD72AD4E07FF14DCFD1537D9A514364C48AE8B0BDDDB4E352BA71F48DD85CAF9969E00F7F69583C4ECF3355D8A3FE19A54E4CF029FCD18C4BB5821D7F845784C750F85813B345D2432623317B9B6BD478EDB13474392F45706CA5F707753B19FAFA489465AC3921D131B49ABE778FF86E4DFA12338ABDFD9E5EB1C5FBB9104C4D08D0B4581E691A2CA48759F426CB42BBC7B9C976B0DCBC7EF3FD61593FF418050EE31A9574533C049E855847FF637B98A13B7CF653BFD1E34697EA7E8F5972D24C755EFBA80DC2B3CCCA4C27FF637B98A13B7CF08AED279066CFEBAAE837D7A58F05C80C50E18EBBA2457CC8B7A49BC7E3AC727E2DF204B0FAA88B4D8D3305D7FC3CAAC6C8E8A39E6D186D349374F8DC8697276D3F43CCD886C63A6B9760D58BF0BCFB14085378E13BFCC872B0E682C324D997A4085378E13BFCC872198926F350F80F8A421BD308B90E5A0F8BF6ADA851894048E0D9335DA705C5F9C1EC16FAE4CAB3FAF57874D6ED25338ED8B1D1F79B9C74F2576E6BDCE4A247BF1BA946A0E5E26FACEF265BA7C88749F5708B4B4D67FF63F372A6EA162F1EB659595CC3ADDE336901C7A3555FBD1791940822039AD431C2FF3C1244DD08BA2329595CC3ADDE33690C31C21B91C683573C9AD25DE1473E52642A81F0D8A4E373CFADB07E1E08F162D42A81F0D8A4E373C3C56511F579503E129E1B0E5C7B1495EFADB07E1E08F162D3029ED870C0E99076E85C9420D3A49DEE49DEF7AC7EF16CC3A401C026B3B30A8FD7329CCF8E89795D7FA621094795284250ED374E0C834A520669FD2DA80CB986E15701503FC1BCCBB6736E98845BCB3AAA0F00FBCA007999E9080D58DF070F6AC0FBCBD24136EB1BA4F28094A4E032394EB6E3E879EA4506EF16C6018B54C67B1BC3A9163F35E66632A4CE2FC4729C465294CAFD41C52BCBF7B4C9E969F8500A3CA6B45C585BBF1295C0F65EA354CA64DA89E2A4F21AA51C22175EEDC0ADF481AE5653D91E218E9C22175EEDC0ADF48F1E5B4B2B9D83FAF7ED402F67F5BE356ACB1365D6F85EEF25EED39D1F1235A0869E28E5A88F16EF5587F78FFF1F0F3284A1A6E020D4F540BA11E10AC5FF98CD16890D6F0014C23CF4A1A6E020D4F540B163DBA6EA6788E097DCBC8926E02592ED2C7490E4DB0D8B387177733ADD384E71F7272BE5C26C4EDA83AF3F513FB2BD50C7DE34D33C212B8F7A108FFF61C07FA0ED3FE2F2EB2EB80CE0C575E76D1C987FCC17AB719B4C6084CBBC80ED7C431E58061C67284860264CD7E6A98DD854CC6DF8A4EA9FDA0B0B8C0595874259441CED43851350D1CDEA0F3FC2657B583D9F1327E3EBD81F3ABBB660E6B5E2A2AFD9E97ADC578884CAF752E8DBE68A3023D7DA56013687160F8B8CD8E7DA098CFCC6AC2A34AFF47C183B887177733ADD384E7E172347A67E3118B0B9D779AD32CE1BD912DD676F2E2E52796EA67BF554606272C50DA15CA8E8DFE4A2533F2C80F4C40DFCCCC1B97B9890F5F89B63F8738F3709093D6568C2D21651AEA2B44A349556206904FB01523B86C956469DBBBAE3EDBEE1EECB5F2D538E49932E2C98E3F2ACF8E7D9FB6F67A7758382520A1A10C356DA7D5864EE1D724D7C8745FFE167D2EBCD2476079ACDA61C0E400562B3BDFE4B95070FF7CCCDD3EEB912A74148B9722A585FB8F48529C484E4EF2EE2E440907DDF06DC9F0A92B27734EF2EE2E440907DDCA5B6B145888DF92CB8AE62CA8AB4AF2F87FD8066CCF6F79B8BB96B8BD2EDC72E68326C2A05D684A49FB4106C690701F422D8BBC9F6ECEECD60D2E9FAD15E6599EF0D3D66490566CE5937D57C854D29A9AA5C9D82D0B07EED2985A64EA111C91AE29FC3EAB49679A12FFC07424E700E0557FEFFB77B015305B068ED39974D34B64DD44F76DF83FEF6DD5B7662119EDB33D7E9FB71334C74675D81BF7661ADA8CB32D25248F1F7D21CDF89C3CB2D1A85A475D836C4FA52EE2A5484FA170FB1F54C9DBC108FA028FF222440CC12749EF00A31E1286E33E7FF43A9B014EF91EA0BC8BA4BFDFB2917465A020B0F6A8E458EC8BA4BFDFB29174659F57961EF08A52A6FA45D765C6B9787C85859A7744550459883ACDA712403F7683F93B9A6AA4D5EF4D5FAE9610365A19AEA910D4F7C8F711FA58BD480805596C9551BB09E7332733388D65D4763FC722CF8F2CBB38E465DBF179365BE8783CE97DC5E2FB78829B405E114211ADA678255E114211ADA67825F6A72434A871D612E79F730340B608C1|387BA7FA6A68E69747B959371CE239AB8BA36290AA3162E9C6401DE6141D883C54038F4B5ABF512703C0C22EF3E5823F7125E5001BC19E29840D7044450D7DCB727F62DA6F38BAFB3560ECF8AD0E7359210B32B364B7D914A0FB3C43E130B707BD0812CA58C3A97FD2CBEFA1A55D22533FADB4717219AEE2CD6D50FE50F355118200F8C2849F566DE5BCC0DF2BBC64FB8851D462A5514D106726CABB8318572230D2AD6D4FC9A5DBFC9218EFA8EF75C0BA650F4E2AE07108A815236185EA23E39502F44B389219B30C319545BF4A5A0EF7B4F69708FDF2F8CA67B904D270C59FD51D2D89E1324DA1D67F5C2AE03C018A47EC03EB42450A1B495B2BBFCBCEBE11',
            );
            $data    = $post['data'];
            $version = $post['version'];    
        } else {
            $data    = $this->params()->fromPost('data');
            $version = $this->params()->fromPost('version');            
        }

        // Version y data correctos
        if ($data && $version=='2') {
        
            $solData = simplexml_load_string($pagoEfectivo->desencriptarData($data)); 
            $payStates = [
                592=>['est'=>1, 'msg'=>'Pendiente'],
                593=>['est'=>2, 'msg'=>'Pagado'],
                595=>['est'=>3, 'msg'=>'Caducado'],
            ];
            
            // Actualizando estado en la BD
            if(key_exists((int)$solData->Estado, $payStates)){
                $states = $payStates[(int)$solData->Estado];
                $res = $citaTable->updateRow(
                    ['estpago'=>$states['est']], ['idpago'=>trim($solData->CIP->NumeroOrdenPago)]
                );
            }
            // Sino
            echo '$solData->Estado No existe en $payStates!';
            exit;
        }
        // Sino
        echo 'ERROR PARAMETROS: Version o data incorrecta!';
        exit;
    }
    
    /**
     * Notificaciones solicitud de pago de pago efectivo
     * @param array  $input  Data de la solicitud de pago
     * @return void
     */
    private function pagoefectivoNotificacion($input)
    {
        $return = new \stdClass();
        $return->status = false;
        $return->success = new \stdClass();
        $return->success->isPago = false;
        $return->success->isFinalizado = false;
        $return->error = new \stdClass();

        $isExtorno = false;

        $mTransaccion = $this->mTransaccion;

        try {

            if (empty($input)) {
                throw new \Exception("Intruso.");
            }

            $inpData = $input['data'];
            $inpVersion = $input['version'];

            $this->getEvent()->trigger(
                \Pagos\Event\Listener::NOTIFICACION_HISTORIAL_EVENT, $this,
                array(
                    'idTransaccion' => "",
                    'msj' => "notificacion pago efectivo intento",
                    'service' => "",
                    'input' => $input,
                )
            );
            
            
            $objData = $this->peSrv->desencriptarData($inpData);

            if (!$objData->status) {
                throw new \Exception($objData->error . ' data:'.$inpData);
            }
            $objData = $objData->success;

            $idTransaction = (int) $objData->CodTrans;

            $this->getEvent()->trigger(
                \Pagos\Event\Listener::NOTIFICACION_HISTORIAL_EVENT, $this,
                array(
                    'idTransaccion' => $idTransaction,
                    'msj' => "notificacion pago efectivo",
                    'service' => (array) $objData,
                    'input' => $inpData,
                )
            );

            $arrTrans = $mTransaccion->getTransaccionById($idTransaction);

            if (empty($inpVersion) || empty($objData) || empty($arrTrans)) {
                throw new \Exception("Intruso.");
            }

            if ($arrTrans['estado'] == $mTransaccion::TRANS_EST_PAGADO) {
                throw new \Exception('Error estado transaccion');
            }

            $nrCip = (string) $objData->CIP->NumeroOrdenPago;

            //$idPE = $objData->idResSolPago;
            //$encode = Zend_Json::encode($objData);
            $dataEncode = Json::encode($objData);

            $estadoPE = (int) $objData->Estado;

            $estGenerado = $mTransaccion::TRANS_EST_GENERADO;
            $estPagado = $mTransaccion::TRANS_EST_PAGADO;
            $estSolicitudExpirada = $mTransaccion::TRANS_EST_SOLICITUD_EXPIRADO;
            $estVencido = $mTransaccion::TRANS_EST_PAGO_VENCIDO;
            $estExtornado = $mTransaccion::TRANS_EST_EXTORNADO_PE;

            $apCondicion['592'] = $estGenerado;
            $apCondicion['593'] = $estPagado;
            $apCondicion['594'] = $estSolicitudExpirada;
            $apCondicion['595'] = $estVencido;

            $estado = $apCondicion[$estadoPE];

            if ($estadoPE == 593) {
                $return->success->isPago = true;
            }

            if ($estadoPE == 594 || $estadoPE == 595) {
                $return->success->isFinalizado = true;
            }

            if (empty($estado)) {
                throw new \Exception("Estado incorrecto.");
            }

            if ($arrTrans['estado'] == $estPagado && $estado == $estGenerado) {
                $estado = $estExtornado;
                $isExtorno = true;
            }

            $idAvisoDet = empty($arrTrans['idAvisoDetalle']) ? 0 : $arrTrans['idAvisoDetalle'];
            $idPackAgeDet = empty($arrTrans['idPaqAgeDet']) ? 0 : $arrTrans['idPaqAgeDet'];
            $idPqProyecto = empty($arrTrans['idPqProyecto']) ? 0 : $arrTrans['idPqProyecto']; //--
            $idPerfilPago = empty($arrTrans['idPerfilPago']) ? 0 : $arrTrans['idPerfilPago'];

            $updDatos['nrOrden'] = $nrCip;
            $updDatos['estado'] = $estado;

            $success = $mTransaccion->updDatos($idTransaction, $updDatos);

            if (!$success) {
                throw new \Exception("update estado.");
            }

            //>Extornado procesos para los avisos
            if ($isExtorno) {
                $extornoModel = $this->serviceManager->get('Extorno\Service\Extorno');
                $extornoModel->extornar($idTransaction);
            }

//            $mTransaccionHistorial->saveLog(
//                    $idTransaction, $arrTrans['idUser'],
//                    "pagoefectivoNotificacion", $inpVersion . "&" . $dataEncode
//            );

            $return->success->idTransaction = $idTransaction;
            $return->success->idAvisoDetalle = $idAvisoDet;
            $return->success->idPackAgeDet = $idPackAgeDet;
            $return->success->idPqProyecto = $idPqProyecto; //--
            $return->success->idPerfilPago = $idPerfilPago;
            $return->status = true;
        } catch (\Exception $exc) {
            $return->error->msgHid = $exc->getMessage();
//            $mTransaccionHistorial->saveLog(
//                    "", 0,
//                    "/pagos/plugin/pagos->pagoefectivoNotificacion error",
//                    $return->error->msgHid
//            );
        }

        return $return;
    }
}
