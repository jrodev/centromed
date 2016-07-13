<?php

namespace Payments;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Payments\Service\PagoEfectivo;
//use Payments\Service\AlignetGnupg;
use Payments\Service\AlignetGpg;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sm = $e->getApplication()->getServiceManager();
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Payments\Service\PagoEfectivo' => function(\Zend\ServiceManager\ServiceManager $sm){
                    $cnfPay = $sm->get('config');
                    return new PagoEfectivo($cnfPay['pagos']['pago-efectivo']);
                },
                'Payments\Service\PEXmlRequest' => function(\Zend\ServiceManager\ServiceManager $sm){
                    return new Service\PEXmlRequest();
                },
                'Payments\Service\AlignetGpg' => function(\Zend\ServiceManager\ServiceManager $sm){
                    $cnfAlignet = $sm->get('config');
                    return new AlignetGpg($cnfAlignet['alignet']);
                }
                // No usando (en VPS no funciona decrypt)
                /*
                'Payments\Service\AlignetGnupg' => function(\Zend\ServiceManager\ServiceManager $sm){
                    $cnfGnupg = $sm->get('config');
                    return new AlignetGnupg($cnfGnupg['gnupg']);
                },*/
            ),
            'aliases' => array(
                'PagoEfectivo' => 'Payments\Service\PagoEfectivo',
                'PEXmlRequest' => 'Payments\Service\PEXmlRequest',
                'AlignetGpg'   => 'Payments\Service\AlignetGpg',
                //'AlignetGnupg' => 'Payments\Service\AlignetGnupg',
            ),
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
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
