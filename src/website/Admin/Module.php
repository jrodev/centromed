<?php

namespace Admin;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class Module
{
    public function onBootstrap(MvcEvent $evt)
    {
        $em = $evt->getApplication()->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'authAdmin'),1);
        
        // Seteando estructura del menu en la vista
        $em->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController', 'dispatch', function(MvcEvent $e) {
            $sm   = $e->getApplication()->getServiceManager();
            $ctrl = $e->getTarget();
            $cnf  = $sm->get('config');
            $ctrl->layout()->setVariable('admMenu', $cnf['admin-menu']);
        }, 100);

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function authAdmin(MvcEvent $evt)
    {   
        $sm = $evt->getApplication()->getServiceManager();
        $vhPortal = $sm->get('viewhelpermanager')->get('portal');  
        $mca      = $vhPortal('mca'); flog('authAdmin->$mca:',$mca);
        
        if($mca['modu']!='admin'){ return ; } // Este metodo no hace nada

        $ctrl    = $evt->getTarget();
        $login   = $sm->get('LoginPortal');
        $isLogin = $login->isLoggedIn('admin');
        $isAjax  = $evt->getRequest()->isXmlHttpRequest();//flog("isAjax:",$isAjax);
        
        $secLogin = ($mca['ctrl']=='index' && $mca['acti']=='index'); 

        if(!$isLogin && !$secLogin){
            if($isAjax){
                $jm = new JsonModel();
                //$jm->setTerminal(true);
                $jm->setVariables(array('foo'=>'value!!'), TRUE);
                return $jm;
                //echo $content;
                //exit;
            }
            $response = $evt->getResponse();
            $response->setStatusCode(302);
            $ctrl->plugin('redirect')->toUrl('/admin');
        }
    }
    
}
