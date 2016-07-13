<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Portal\Controller;

use BaseApp\Controller\PortalController,
    Zend\Form\Annotation\AnnotationBuilder,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Portal\Form\UsuarioForm,
    Portal\Form\CitaForm
;

class UsuarioController extends PortalController
{
    // no implementado
    public function indexAction()
    {
        return new ViewModel();
    }
    
    // no implementado
    public function cuentaAction()
    {
        $sm = $this->getServiceLocator();
        $cita  = $sm->get('CitaTable');
        $login = $sm->get('LoginPortal');

        $profile = $login->getProfile('portal');

        if(!$profile){ return $this->redirect()->toUrl('/'); }

        // Formlario para para actualizar datos
        flog('$profile:',$profile);
        $formReg = new UsuarioForm($sm, 'frmusuario');
        $formReg->populateValues($profile);
        $formReg->get('submit')->setLabel('ACTUALIZAR'); // Setting innerText

        // Formulario para reagendamiento
        $formCita = new CitaForm($sm, 'formcita');
        $formCita->get('idseguro')->setLabel('¿Cuentas con algun seguro ?');
        $formCita->get('frmsede')->setLabel('Elige la sede mas cercana a ti');
        $formCita->get('idhorario')->setLabel('Elige horario y especialista');

        // Paginando listado de citas
        $page = key_exists('page', $_GET)?$_GET['page']:1;
        $citas = $cita->getCitaByUser($profile['idusuario'], $page);

        return new ViewModel(array(
            'citas'   => $citas,
            'formreg' => $formReg,
            'formcita'=> $formCita,
            'days'    => $this->getOptionsHorario(), // De BaseController
        ));
    }
    
    /**
     * 
     */
    public function reagendarAction()
    {
        $log = ['session'=>FALSE, 'success'=>FALSE, 'msg'=>'reagendarAction', 'data'=>[]];
        $oModel = new JsonModel();
        
        $sm = $this->getServiceLocator();
        $rq = $this->getRequest();
        
        $cita  = $sm->get('CitaTable');
        $login = $sm->get('LoginPortal');
        
        $reqAdm = key_exists('adm', $_GET);
        $profile = $login->getProfile( $reqAdm?'admin':'portal' );
        flog("profileeee------>>>",$profile);  
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
        if(!$profile){
            $log['msg'].='|No hay sesion';
            return $oModel->setVariables($log);
        }
        
        // Hay Session
        // idusuario desconocido
        if( !$reqAdm && $profile['idusuario']!=$dataPost['idusuario']){
            $log['msg'].='|id de usuario desconocido';
            return $oModel->setVariables($log);
        }

        // Actualizando
        $rowCita = $cita->getRow($dataPost['idcita']);
        if(!$reqAdm && (int)$rowCita->reagen){
            $log['msg'].='|Cita ya reagendada!';
            return $oModel->setVariables($log);
        }

        if($cita->saveRow($dataPost+['reagen'=>1])){
            $log['msg'].='|Cita Reagendada!';
            $log['success'] = TRUE;
        }
        else $log['msg'].='|No cambios o error al reagendar!';
        
        return $oModel->setVariables($log);
    }
    
    /**
     * 
     */
    public function loginAction()
    {
        $log = ['session'=>FALSE, 'msg'=>'loginAction', 'data'=>[]];
        $rq  = $this->getRequest();
        $web = key_exists('web', $_GET); // Si se pide desde la web
        $adm = key_exists('adm', $_GET); // Si se loguea desde admin
        
        // Estableciendo Model Object a retornar
        $oModel = new ViewModel();
        if($rq->isXmlHttpRequest()){
            $oModel = new JsonModel();
            if($web){
                $oModel = new ViewModel();
                $oModel->setTerminal(true);
            }
        }
        
        // No es POST
        if(!$rq->isPost()){
            $log['msg'].='|nopost';
            
            return $oModel->setVariables($log);
        }
        
        // Es POST
        $log['msg'].='|isPost';
        $dataPost = $rq->getPost()->toArray();
        $log['data']['formVal'] = $dataPost;

        $espclogin = new \Session\Form\Login();
        $AnnBuilder = new AnnotationBuilder();
        $loginForm  = $AnnBuilder->createForm($espclogin);
        $loginForm->setData($rq->getPost());
        
        // Form INVALID !
        if (!$loginForm->isValid()){
            $log['msg'].='|Invalid!';
            $log['data']['formErr'] = $loginForm->getMessages();
            return $oModel->setVariables($log);
        }
        
        // Form VALID !
        $log['msg'] .= '|Valid!';
        $sm  = $this->getServiceLocator();
        $login = $sm->get('Session\Service\Login');
        
        // NO login!
        $ns = $adm?'admin':'portal'; // Nombre de namespace
        if(!$login->isLoggedIn($ns)){
            $log['msg'] .= '|NoIsLoggenIn!';
            if($adm){ $dataPost['rol'] = 2; }
            $log['data']['affectedRows'] = $login->start($dataPost,$ns); // Logeando
            $log['session'] = $login->isLoggedIn($ns); // status de login
            if($log['session']){ $log['msg'] .= '|NowLoggenIn!'; }
            return $oModel->setVariables($log);
        }
        
        // Login!
        $log['msg'] .= '|isLoggedIn!';
        $log['session'] = TRUE;

        return $oModel->setVariables($log);

    }
    
    /**
     * Registra o Actualiza segun sea el caso (Si se envia id de tabla)
     * devuelve JsonModel o ViewModel Segun la peticion que se haga.
     */
    public function registrarAction()
    {
        $sm = $this->getServiceLocator();
        $rq = $this->getRequest(); // var_dump($rq->getHeaders()->get('Content-Type')); exit;
        $login = $sm->get('Session\Service\Login');
        $formReg = new UsuarioForm($sm, 'frmregistro');
        
        $log = ['state'=>false,'valid'=>false,'msg'=>'registrarAction','data'=>[]];
        $log['session'] = $login->isLoggedIn('portal'); // session value
        
        // SET ObjectModel
        $oModel = $rq->isXmlHttpRequest()?new JsonModel():new ViewModel();
        
        // $oModel->setVariable('formreg',$formReg); Si esto se comenta-> error al hacer submit a esta ruta
        
        // NO Post !
        if(!$rq->isPost()){
            $log['msg'] .= '|NoPost';
            $oModel->setVariable('formreg',$formReg);
            return $oModel->setVariables($log);
        }
        
        // ES Post !
        $log['data']['formVal'] = $rq->getPost()->toArray();
        $log['msg'] .= '|isPost';
        
        // Id usuario Post (mayor>0 si es UPDATE)
        $idUserPost = (int)trim($log['data']['formVal']['idusuario']); // Si manda idusuario
        
        // Setting Form !
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $userTbl = $sm->get('UsuarioTable');
        
        $formReg->setInputFilter( $userTbl->getInputFilter($adapter, (bool)$idUserPost) );
        $formReg->setData($rq->getPost());
        
        // INVALID Form !
        if(!$formReg->isValid()){
            $log['data']['formErr'] = $formReg->getMessages();
            return $oModel->setVariables($log);
        }
        
        // VALID Form !
        $log['valid'] = TRUE;

        // Desea UPDATE
        if($idUserPost){
            $log['msg'] .= '|deseaUPD';
            $getAdm     = $this->params()->fromQuery('adm', FALSE);
            $profile    = $login->getProfile($getAdm?'admin':'portal'); // Array datos usuario sesion
            $idUserSess = count($profile)?$profile['idusuario']:FALSE; // usuario en sesion
            $isAdm      = ($profile['rol']==='2');
            $isUpdate   = ( $idUserPost == (int)$idUserSess || $isAdm );
            flog("profile['rol']:",$profile['rol']);
            // Incorrecto UPDATE
            if(!$isUpdate){
                $log['msg'] .= '|incorrectUPD';
                $log['data']['idUserSess'] = $idUserSess;
                return $oModel->setVariables($log);
            }
        }
        
        // Correcto UPDATE o Desea INSERT
        $data = $userTbl->saveRow($formReg->getData());
        $log['data']['affectedRows'] = $data; // para UPDATE $data is numeric|bool
        
        // UPDATE (Si es cierto, por la validacion anterior, ya hay sesion)
        if($idUserPost){
            $log['msg'] .= $isAdm?'|ExistialoginAdm!':'|Existialogin!';
            $log['msg'] .= '|UPD:'.(!$data?'NoChanges':'OK');
            $log['state'] = (bool)$data;
            
            // Seteando profile con nuevos datos
            $newProfile = $userTbl->andWhere(['idusuario'=>$profile['idusuario']]);
            if(!$isAdm) { $profile = $login->setProfile('portal',$newProfile[0]); }
            flog('UsuarioController->registrarAction->profile:',$profile);
            
            return $oModel->setVariables($log);
        }
        
        // INSERT (Hacer logica cuando ADM quiera crear usuario - FALTA!!!!)
        $log['msg'] .= '|INS:'.(!$data?'ERROR':'OK');
        $res = $data ? $login->start(
            ['tipodoc'=>$data['tipodoc'],'nrodoc'=>$data['nrodoc'],'pass'=>$data['pass']], 'portal'
        ): FALSE; //Si guardo Logeamos!
        $log['session'] = $res;
        $log['msg'] .= $res?'|Login!':'ErrLogin';
        
        unset($data['data']['affectedRows']['pass']); // Eliminado valor para password

        return $oModel->setVariables($log);
        
    }
    
    /**
     * Close sesion
     */
    public function logoutAction()
    {
        $adm = key_exists('adm', $_GET); // Si se loguea desde admin
        $ns = $adm?'admin':'portal';     // setting namespace
        
        $sm = $this->getServiceLocator();
        $sm->get('LoginPortal')->logout($ns);
        $this->redirect()->toUrl($adm?'/admin':'/');
    }
}
