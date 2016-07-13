<?php

namespace JavaScript\Service;

use Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch;

class StoreScript {
    
    static $store = array();
    
    function __construct(MvcEvent $e) {
        if(!count(StoreScript::$store))
            StoreScript::$store = $this->getNamesMVC($e);
    }
    
    /**
     * @return Array Retorna lo almacenado (StoreScript::$store)
     */
    public function getStore()
    {
        return StoreScript::$store;
    }

    /**
     * @param Variant $i Indice para el nuevo item a guardar en Store.
     *                   Si es de tipo array se suma al Store Array.
     * @param Variant $v Valor asociado a $i(indice).
     * @return Array Retorna lo almacenado (StoreScript::$store)
     */
    public function setStore($i='', $v='')
    {
        if($i)
            StoreScript::$store += is_array($i)?$i:array($i=>$v);
        return StoreScript::$store;
    }
    
    /**
     * @param MVCEvent $e MVC event Object
     * @return Array nombre de module, controller y action con datos adicionales.
     */
    private function getNamesMVC(MvcEvent $e)
    {
        $rm = $e->getRouteMatch();
        $sm = $e->getApplication()->getServiceManager();
        
        if (!($rm instanceof RouteMatch)) {
            $rm = new RouteMatch(array(
                'module'        => 'Application',
                '__NAMESPACE__' => 'Application\Controller',
                '__CONTROLLER__'=> 'index',
                'controller'    => 'Application\Controller\Index',
                'action'        => 'index',
            ));
        }

        $params = $rm->getParams();
        $modulo     = isset($params['__NAMESPACE__']) ? $params['__NAMESPACE__']:"";
        $controller = isset($params['__CONTROLLER__']) ? $params['__CONTROLLER__']:"";

        if (isset($params['controller'])) {
            $paramsArray = explode("\\", $params['controller']);
            $modulo = $paramsArray[0];
            $controller = $paramsArray[2];
        }

        $action = isset($params['action']) ? $params['action'] : null;

        return array(
            'module' => strtolower($modulo),
            'controller' => strtolower($controller),
            'action' => strtolower($action),
            'isLogin'=> $sm->get('LoginPortal')->isLoggedIn('portal'),
            'min' => '',
            'AppCore' => [],
            'AppSandbox' => [],
            'AppSchema' => [ 'modules'=>[], 'requires'=>[] ]
        );
    }
}
