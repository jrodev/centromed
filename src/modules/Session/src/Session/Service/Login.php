<?php

/**
 * Description of Login
 * @author jrodev
 */
namespace Session\Service;

use Zend\Session\Container,
    Portal\Model\UsuarioTable;


class Login {

    private $usu = null;
    private $ns  = [];
    
    /**
     * @param UsuarioTable $usu Instancia de tabla de usuarios.
     * @param Array $NS Array de nombres de espacio para las sesiones.
     */
    public function __construct(UsuarioTable $usu, $NS=[]) 
    {
        if( !(is_array($NS) && count($NS)) ){
            throw new \Exception('Session->Login->constr:$NS no array o vacio');
        }
        
        $this->usu = $usu;
        
        foreach ($NS as $ns) {
            
            $this->ns[$ns] = new Container($ns); // Creando NameSpace
            if(is_null($this->ns[$ns]->offsetGet('session'))){
                $this->ns[$ns]->offsetSet('session', FALSE);
                $this->ns[$ns]->offsetSet('profile', []);
            }
        }
    }
    
    /**
     * @param Array $colsVals array con los nombres de columnas y valores para
     *                        matching con la la DB (si matching => login!)
     * @return Boolean Log array
     */
    public function start($colsVals=array(), $NS=FALSE){
        
        if( !(is_array($colsVals) && count($colsVals)) || !$NS ){
            throw new \Exception('Session->Login->start: No array o $NS vacio');
        }
        
        $res = $this->usu->andWhere($colsVals);
        flog('Login->start:colsVals',$colsVals);
        flog('Login->start:res',$res);
        
        if(count($res)){
            $this->ns[$NS]->offsetSet('session', TRUE);
            $this->ns[$NS]->offsetSet('profile', $res[0]);
        }
        
        return count($res)?$res : FALSE;
    }
    
    /**
     * 
     */
    public function getProfile($NS=FALSE)
    {
        if( !$NS ){
            throw new \Exception('Session->Login->getContainer: $NS incorrecto');
        } return $this->ns[$NS]->offsetGet('profile');
    }
    
    /**
     * 
     */
    public function setProfile($NS=FALSE, $data=array())
    {
        if( !$NS || !is_array($data) || !count($data) ){
            throw new \Exception('Session->Login->setProfile: Datos vacios');
        }
        $this->ns[$NS]->offsetSet('profile', $data);
        return $this->getProfile($NS);
    }
    
    /**
     * Obtenemos el container segun el namespace
     * @param String $NS El nombre del namespace.
     * @return Container Devuelve del container asociado al NS.
     */
    public function getContainer($NS=FALSE)
    {
        if( !$NS ){
            throw new \Exception('Session->Login->getContainer: $NS incorrecto');
        } return $this->ns[$NS];
    }
    
    /**
     * Existe Session para el NS indicado
     */
    public function isLoggedIn($NS=FALSE)
    {
        if( !$NS ) {
            throw new \Exception('Session->Login->getContainer: $NS incorrecto');        
        } return (!is_null($this->ns[$NS]) && $this->ns[$NS]->offsetGet('session'));
    }
    
    /**
     * Logout
     */
    public function logout($ns)
    {
        if( !$ns ){
            throw new \Exception('Session->Login->logout: $NS incorrecto');
        }
        $this->ns[$ns]->getManager()->forgetMe();
        $this->ns[$ns]->getManager()->getStorage()->clear($ns);
        $this->ns[$ns]->getManager()->destroy();
    }
    
}
