<?php

namespace DataBase;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    
    Zend\Mvc\ModuleRouteListener,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway,
        
    Zend\View\Model\ViewModel,
    Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap(MvcEvent $evt)
    {
        $em = $evt->getApplication()->getEventManager();
        $mrl = new ModuleRouteListener();
        $mrl->attach($em);
        $sm = $evt->getApplication()->getServiceManager();
        
        //using '*' to make $callback available in all events.
        $em->attach('*', array($this, 'dbError'), 1000);
        
        $cnf = $sm->get('config');
        
        // Creando service's factorie's para las tablas de module portal
        foreach ($cnf['tables'] as $ns=>$tbls) {
            
            foreach($tbls as $tblName=>$fields){

                $tblName    = strtolower(trim($tblName));
                $classTable = ucfirst($tblName).'Table';
                // $classModel = ucfirst($tblName).'Model';
                $sm->setFactory($ns.$classTable, 
                function($sm) use ($tblName, $classTable, $ns, $fields/*, $classModel*/){

                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    //$classModel = $ns.$classModel;
                    //$resultSetPrototype->setArrayObjectPrototype(new $classModel());
                    $tg = new TableGateway("osi_$tblName", $dbAdapter, null, $resultSetPrototype);

                    $classTable = $ns.$classTable;
                    return new $classTable($tg, $fields);

                })->setAlias($classTable, $ns.$classTable);

            }
        }
        
    }
    
    public function dbError(MvcEvent $evt)
    {
        $sm  = $evt->getApplication()->getServiceManager();
        
        try {
            
            $adp = $sm->get('Zend\Db\Adapter\Adapter');
            $adp->getDriver()->getConnection()->connect();
            
        } catch (\Exception $exp) {
            
            $ViewModel = $evt->getViewModel();
            $ViewModel->setTemplate('layout/layout');
            
            $content = new ViewModel(['evtname'=>$evt->getName()]);
            $content->setTemplate('error/dberror');
            
            //set $this->layout()->"content" variable with error/dberror.phtml
            $ViewModel->setVariable('content', $sm->get('ViewRenderer')->render($content));
            
            echo $sm->get('ViewRenderer')->render($ViewModel);
            //echo "<hr><b> * ".$evt->getName().": </b>";
            //$evt->stopPropagation(); 
            exit; // Cancelando siguiente event
        }
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