<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Portal;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    //Portal\Model,
    //Portal\Model\CitaTable,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
        
    Portal\View\Helper\Portal;

class Module
{
    public function onBootstrap(MvcEvent $evt)
    {
        $eventManager = $evt->getApplication()->getEventManager();
        
        $sm = $evt->getApplication()->getServiceManager();
        $cnf = $sm->get('config');
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // Seteando Layout segun el ambiente (Portal o Admin)
        $eventManager->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController', 'dispatch', function(MvcEvent $e) {
            
            $controller      = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            //var_dump($moduleNamespace);
            if (isset($moduleNamespace) && $moduleNamespace=='Admin') {
                $controller->layout('layout/'.strtolower($moduleNamespace));
            }
        }, 100);
        
        // Cargando formReg, formcita para todos los controller (Seteandolo en las variables para view)
        $eventManager->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController', 'dispatch', function(MvcEvent $e) {
            $sm   = $e->getApplication()->getServiceManager();
            $ctrl = $e->getTarget();
            $formreg = new \Portal\Form\UsuarioForm($sm, 'frmregistrar');
            $formcita = new \Portal\Form\CitaForm($sm, 'frmcita');
            $ctrl->layout()->setVariable('formreg', $formreg);
        }, 100);
        
        // Definiendo view helper Portal
        $sm->get('viewhelpermanager')->setFactory('portal', function ($sm) use ($cnf,$evt){
            return new Portal($cnf, $evt);
        });
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
}
