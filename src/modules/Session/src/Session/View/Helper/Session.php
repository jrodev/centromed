<?php

/**
 * @viewHelper
 * @author Jaime Rodriguez <jrodev@yahoo.es>
 */

namespace Session\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Session\Service\Login as LoginPortal,
    Zend\Mvc\MvcEvent;

class Session extends AbstractHelper {

    private $login;
    private $ns = 'portal';
    
    public function __construct(LoginPortal $login/*, MvcEvent $evt*/)
    {
        $this->login = $login;
    }
    
    public function user($key){
        $user = $this->login->getProfile($this->ns);
        return (count($user) && key_exists($key, $user))?$user[$key]:FALSE;
    }

    public function login()
    {
        return $this->login->isLoggedIn($this->ns);
    }


    public function profile()
    {
        return $this->login->getProfile($this->ns);
    }
    /**
     * Para invocar instacia de clase como un metodo.
     * @param String $index Indice del valor que deseamos (imagen|active).
     * @param Array $ca Array con el ctrl y acti segun eso enviar si es activo o no.
     */
    public function __invoke($NS)
    {
        $this->ns = $NS;
        return $this;
    }
}
