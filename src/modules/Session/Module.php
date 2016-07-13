<?php

namespace Session;

use Zend\Mvc\ModuleRouteListener,
        
    //Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter,
    //Zend\Authentication\AuthenticationService,
    Zend\ServiceManager\ServiceManager,
    Zend\Session\Config\SessionConfig,
    Zend\View\Helper\Navigation\PluginManager,
    Zend\Session\SessionManager,
    Zend\Session\Container,
    Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sm = $e->getApplication()->getServiceManager();

        // Session_onbootstrap ------------------------------------------------------------------------
        $sessConf = $sm->get('SessionConfig'); // Llamando a un service Factory que setea la sesion.
        $sessStrg = $sm->has('session_storage'     , false) ? $gsm->get('session_storage')     : null;
        $sessSHdl = $sm->has('session_save_handler', false) ? $gsm->get('session_save_handler'): null;
        
        // @example para un predefinido session storage:
        // $sessStrg = new \Zend\Session\Storage\ArrayStorage(['foo'=>['valfoo'=>'naa']]);
        $sessManager = new SessionManager($sessConf, $sessStrg, $sessSHdl);
        // OR $sessManager->setConfig($sessConf)
        // OR $sessManager->setStorage($sessStrg)
        // OR $sessManager->setSaveHandler($sessSHdl)

        $sessManager->start(); // session_start()y verificando cumplimiento de validadores de session
        Container::setDefaultManager($sessManager);
        // entonces para: (new Container('foo'))->offsetGet('valfoo') // return 'naa'

        // --------------------------------------------------------------------------------------------
        
        // Definiendo view helper Session
        $sm->get('viewhelpermanager')->setFactory('session', function ($pm) use ($e) {
            $login = $pm->getServiceLocator()->get('LoginPortal');
            //var_dump('create plugin session:'.get_class($login)); exit;
            return new View\Helper\Session( $login, $e );
        });
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                
                'Zend\Session\Config\SessionConfig' => function ($sm) {
            
                    $config  = $sm->get('Config');
                    $sessConf = $config['session_config']['params'];
                    $timeTemp = $config['session_temp']['time'];
                    $timeNorm = $config['session_norm']['time'];
                    
                    $sessConf['cookie_lifetime'] = TRUE ? $timeNorm : $timeTemp;
                    
                    $sessionConfig = new SessionConfig();
                    if (isset($sessConf)){ $sessionConfig->setOptions($sessConf); }
                    
                    return $sessionConfig;
                },
                        
                // Login into portal
                'Session\Service\Login' => function(ServiceManager $sm){
                    $config  = $sm->get('Config');
                    return new Service\Login($sm->get('UsuarioTable'), $config['session_ns']);
                },
  
            ),
   
            'aliases' => array(
                'SessionConfig' => 'Zend\Session\Config\SessionConfig',
                'LoginPortal'   => 'Session\Service\Login',
                //'MyAuthStorage' => 'Application\Model\MyAuthStorage',
                //'AuthService'   => 'Zend\Authentication\AuthenticationService',
            ),
                    
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(/*
                'session' => function (PluginManager $pm) {
                    $login = $pm->getServiceLocator()->get('LoginPortal');
                    return new View\Helper\Session( $login); // ,$e MVCevent Inject is imposible
                }
            */),
        );   
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
