<?php

namespace Admin\Controller;

use Zend\View\Model\JsonModel,
    Zend\View\Model\ViewModel,
    Admin\Form\CitaForm,
    Admin\Form\UsuarioForm
;   

class CitasController extends \BaseApp\Controller\PortalController
{
    public function indexAction()
    {   
        $sm = $this->getServiceLocator();

        // Array enviado a la vista
        $aVm = [
            'sede'=>'',
            'espc'=>'<option value="0">Especialista</option>',
            'dias'=>'',
            'formreg' => new \Portal\Form\UsuarioForm($sm, 'frmregistrar'),
        ];
        
        // Objetos especialista
        $oEspc = $sm->get('EspecialistaTable')->fetchAll();
        
        $aVm['idsede'] = $sede= (int)$this->params()->fromRoute("sede",0);
        $aVm['esp']    = $esp = (int)$this->params()->fromRoute("esp",0);
        $aVm['dia']    = $dia = trim($this->params()->fromRoute("dia",0));
        $aVm['text']   = $text= $this->params()->fromRoute("text",'');
        $aVm['pag']    = $pag = (int)$this->params()->fromRoute("pag",1);
        
        // Listado de sedes
        $dias = ['Sedes','Miraflores','Surco-Chacarilla','Los Olivos'];
        foreach ($dias as $i=>$v){
            $sel = ($i==$sede)?' selected':'';
            $aVm['sede'] .= "\n<option value='$i' $sel>$v</option>";
        }
        
        // Listado de dias SE CAMBIO POR RANGEDATE
        /*$dias = ['Dias','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'];
        foreach ($dias as $i=>$v){
            $sel = ($i==(int)$dia)?' selected':'';
            $aVm['dias'] .= "\n<option value='$i' $sel>$v</option>";
        }*/
        
        foreach ($oEspc as $e){
            $sel = ($e->idespecialista==$esp)?' selected':'';
            $aVm['espc'] .= "\n<option value='$e->idespecialista' $sel>$e->nom $e->ape</option>";
        }
        
        // Formulario para reagendamiento
        $formCita = new \Portal\Form\CitaForm($sm, 'formcita');
        $formCita->get('idseguro')->setLabel('¿Cuentas con algun seguro ?');
        $formCita->get('frmsede')->setLabel('Elige la sede mas cercana a ti');
        $formCita->get('idhorario')->setLabel('Elige horario y especialista');

        $aCitas = $sm->get('CitaTable')->getCitaByEsp($sede, $esp, $dia=='0'?0:$dia, $pag, $text);
        flog('CitasController->indexAction->$aCitas',$aCitas);
        $aVm['formcita'] = $formCita;
        $aVm['citas']    = $aCitas['res'];
        $aVm['cant']     = $aCitas['cant'];
        $aVm['days']     = $this->getOptionsHorario();
        
        return new ViewModel($aVm);
    }
    
    public function processAction()
    {
        $sm = $this->getServiceLocator();
        $login = $sm->get('Session\Service\Login');

        // Sesion en Admin        
        $sm->get('storeScript')->setStore('dateNow', date("Y-m-d"));
        $sm->get('storeScript')->setStore('alignet', $this->setAlignetParams());

        $form    = new CitaForm($sm, 'frmcita');
        $formreg = new UsuarioForm($sm, 'frmregistrar');
        $oModel  = new ViewModel(array('form'=>$form,'formreg'=>$formreg));
        $request = $this->getRequest();
        
        // No POST
        if (!$request->isPost()) { return $oModel; } // if(!$form->isValid()){return $oModel;}
        
        // Si POST
        $aPost = $request->getPost()->toArray();
        $user  = $this->getServiceLocator()->get('UsuarioTable');
        $rows  = $user->andWhere(array('idusuario'=>$aPost['idusuario']));
        $dataUser = count($rows)?$rows[0]:FALSE;

        // SI NO dataUser
        if(!$dataUser){
            echo "<br /><h3>Usuario no seleccionado o no registrado</h3><br />";
            return $oModel;
        }
        
        // Pagando la cita (Podria ser visa o pago efectivo)
        $queryStr = $this->setPagoCita($aPost, $dataUser, $ext); flog('$queryStr:',$queryStr);
        
        return $this->redirect()->toUrl('/admin/citas/form'.$queryStr);
    }

    public function estadoCitaAction()
    {
        $log = ['session'=>FALSE, 'success'=>FALSE, 'msg'=>'estadoCitaAction', 'data'=>[]];
        $oModel = new JsonModel();
        
        $sm = $this->getServiceLocator();
        $rq = $this->getRequest();
        
        $cita  = $sm->get('CitaTable');
        $login = $sm->get('LoginPortal');
        
        $profile = $login->getProfile('admin');
        
        // No es POST
        if(!$rq->isPost()){
            $log['msg'].='|nopost';
            return $oModel->setVariables($log);
        }
        
        // Es POST
        $log['msg'].='|isPost';
        $dataPost = $rq->getPost()->toArray();
        $log['data']['formVal'] = $dataPost;
        
        // No Sesion 
        flog('$profile:',$profile);
        if(!$profile || $profile['rol']!=2){
            $log['msg'].='|No hay sesion Admin';
            return $oModel->setVariables($log);
        }

        // Actualizando
        if($cita->saveRow($dataPost)){
            $log['msg'].='|Estado cita cambiado!';
            $log['success'] = TRUE;
        }
        else $log['msg'].='|No cambios o error al cambiar estado!';
        
        return $oModel->setVariables($log);
    }
    
    public function getUsuarioAction(){
        $log = ['session'=>FALSE,'success'=>FALSE,'msg'=>'Admin->Cita->getUsuario','data'=>[]];
        $oModel = new JsonModel();
        
        $idUsu = key_exists('idusu', $_GET)?$_GET['idusu']:0;

        $user = $this->getServiceLocator()->get('UsuarioTable');
        $rows = $user->andWhere(['idusuario'=>$idUsu]);
        
        if(count($rows)){
            $log['success'] = TRUE;
            $log['data']['formVal'] = $rows[0];
        }else{
            $log['data']['formVal'] = [];
        }
        return $oModel->setVariables($log);
    }
    
    /**
     * Mapa que se ve en la seccion /admin/citas/process
     */
    public function mapaAction()
    {
        $vm = new ViewModel();
        $vm->setTerminal(true);
        return $vm;
    }
}
