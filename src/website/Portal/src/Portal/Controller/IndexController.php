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
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Portal\Form\CitaForm
;

class IndexController extends PortalController
{
    
    protected $citaTable;
    
    public function indexAction()
    {
        //$this->layout()->setTemplate("layout/layout");
        flog('indexAction upd');
        $sm = $this->getServiceLocator();
        //$formreg = new \Portal\Form\UsuarioForm($sm, 'frmregistrar');
        $storeScript = $sm->get('storeScript');
        $storeScript->setStore('fooIndex', array('item'=>2,'item2'=>array(1,2)));
        flog(getenv('APP_ENV'));
        flog(getenv('APPLICATION_ENV'));
        
        //$fa = $this->getCitaTable()->fetchAll();
        //foreach ($fa as $r) var_dump($r);
        //$this->layout()->formreg = $formreg;
        return new ViewModel(array('citas'=>''));
    }
    
    public function porqueOsiAction()
    {
        ;
    }
    
    public function trabajeConNosotrosAction()
    {
        ;
    }
    
    public function avisoDePrivacidadAction()
    {
        ;
    }
    
    public function blogAction()
    {
        ;
    }
    
    public function soliciteCitaAction()
    {
        ;
    }

    public function soliciteCita2Action()
    {
        $sm = $this->getServiceLocator();
        $login = $sm->get('Session\Service\Login');
        $sm->get('storeScript')->setStore('dateNow', date("Y-m-d"));
        $sm->get('storeScript')->setStore('alignet', $this->setAlignetParams());

        $form = new CitaForm($sm, 'frmcita');
        $formreg = new \Portal\Form\UsuarioForm($sm, 'frmregistrar');
        $request = $this->getRequest();
        
        $dataUser = FALSE;
        if ($login->isLoggedIn('portal')) {
            $dataUser = $login->getProfile('portal');
            $formreg->populateValues($login->getProfile('portal'));
        }
        $pars = array('form'=>$form,'formreg'=>$formreg,'days'=>$this->getOptionsHorario(),'user'=>$dataUser);
        $oModel = new ViewModel($pars);
        
        $ext = (bool)$this->getRequest()->getQuery('ext'); // uso externo (para el caso de centromericoosi.com)
        if($ext){ $this->layout()->setTemplate('layout/layoutCita'); }
        
        // No POST
        if (!$request->isPost()) { return $oModel; } // if(!$form->isValid()){return $oModel;}
        // SI NO dataUser
        if(!$dataUser){ echo "<br /><h3>No hay sesion de usuario</h3><br />"; return $oModel; }
        $aPost = $request->getPost()->toArray();
        // Pagando la cita (Podria ser visa o pago efectivo)
        $queryStr = $this->setPagoCita($aPost, $dataUser, $ext); flog('$queryStr:',$queryStr);
        
        return $this->redirect()->toUrl('/solicite-cita2'.$queryStr);

    }

    /**
     * Obteniendo Especialistas de una sede para una fecha dada.
     */
    public function getEspecAction()
    {
        $sm = $this->getServiceLocator();
        $idsede = (int)$this->params()->fromRoute("idsede", 0);
        $fecha = $this->params()->fromRoute("fecha", 0);

        $resp = ['msg'=>'ok','data'=>[]];
        if(!$idsede || !$fecha){
            $resp['msg']="idsede and fecha obligatorios";
        } else{
            $resp['data'] = $sm->get('EspecialistaTable')->getEspecByFecha($idsede, $fecha);
        }
        return new JsonModel($resp);
    } 

    /**
     * Obteniendo citas para una fecha, sede y especialista dado.
     */
    public function getCitasEspecAction()
    {
        $sm = $this->getServiceLocator();
        $idsede  = (int)$this->params()->fromRoute("idsede", 0);
        $idespec = (int)$this->params()->fromRoute("idespec", 0);
        $fecha   = $this->params()->fromRoute("fecha", 0);
        //var_dump($idespec,$idespec,$fecha);
        $resp = ['msg'=>'ok','data'=>[]];
        if (!$idsede || !$idespec || !$fecha) {
            $resp['msg']="idsede,idespec,fecha obligatorios";
        } else {
            $resp['data'] = $sm->get('HorarioTable')->getCitasByEspec($idsede, $idespec, $fecha);
        }
        return new JsonModel($resp);
    }




    public function contactenosAction()
    {
        
    }
    
    /**
     * Mapa que se ve en la seccion de contactenos
     */
    public function mapaAction()
    {
        $vm = new ViewModel();
        $vm->setTerminal(true);
        return $vm;
    }
    
    /**
     * 
     
    public function calendarAction()
    {
        $sm = $this->getServiceLocator();
        //$hr = $sm->get('HorarioTable')->fetchAll();
        //foreach($hr as $r) var_dump($r);
        $vm = new ViewModel();
        $vm->setTerminal(true);
        return $vm;
    }*/

    // module/Cita/src/Cita/Controller/CitaController.php:
    public function getCitaTable()
{
    if (!$this->citaTable) {
            $sm = $this->getServiceLocator();
            $this->citaTable = $sm->get('Portal\Model\CitaTable');
}
        return $this->citaTable;
    }
    
}
