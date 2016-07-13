<?php

namespace JavaScript;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,        
    Zend\ServiceManager\ServiceManager,
    //Application\Model\Data\Cookie,
    Zend\Mvc\Router\RouteMatch,
    Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $sm  = $e->getApplication()->getServiceManager();
        $gem = $e->getApplication()->getEventManager();
        
        // Definiendo ServiceFactory aca, para inyectar MVCEvent
        $sm->setFactory('JavaScript\Service\StoreScript', function($sm) use ($e) {
            return new \JavaScript\Service\StoreScript($e);
        })->setAlias('storeScript','JavaScript\Service\StoreScript');

        // Definiendo Event Render
        $gem->attach(MvcEvent::EVENT_RENDER, function(MvcEvent $e){
            flog('eventRender');
            $sm = $e->getApplication()->getServiceManager();
            $view = $sm->get('ViewRenderer');
            $cnf = $sm->get('config');
            
            $store = $sm->get('storeScript')->getStore();
            $params = ['baseHost'=>$cnf['view_manager']['base_path']]+$store;
            $view->headScript()->appendScript(
                "window.Global=".json_encode($params, JSON_FORCE_OBJECT)
            );
        }, 100);
        
        file_exists($filename);
/*
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, $callback , 100
        );

        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR, $callback , 100
        );*/
    }

    public function getServiceConfig()
    {
        return array(/*
            'factories' => array(
                'JavaScript\Service\StoreScript' => function(ServiceManager $sm){
                    return new \JavaScript\Service\StoreScript();
                }
            ),*/
            /*'aliases' => array(
                'storeScript' => 'JavaScript\Service\StoreScript',
            ),*/
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            )
        );
    }
    


}