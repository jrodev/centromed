<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Zend\View\Model\ViewModel;

class HorarioController extends AbstractActionController
{
    public function indexAction()
    {
        $sm = $this->getServiceLocator();
        
        // No login
        $login = $sm->get('LoginPortal');
        $profile = $login->getProfile('admin');
        if(!$profile){ return $this->redirect()->toUrl('/admin'); }
        
        // Si es una peticion ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $sede      = $this->params()->fromRoute("sede",0);
            $room      = $this->params()->fromRoute("room",0);
            $weekStart = key_exists('startWeek', $_GET)?$_GET['startWeek']:0;
            $weekEnd   = key_exists('endWeek', $_GET)?$_GET['endWeek']:0;
            
            $resp = ['msg'=>'ok','data'=>[]];
            
            if(!$sede){ $sede['msg'] = 'Sede numeric mayor a 0'; }
            else {
                $resp['data'] = $sm->get('HorarioTable')->getHorarioByRoom($sede,$room,$weekStart,$weekEnd);
            }

            return new JsonModel($resp);
        }

        $aVm = ['sede'=>[], 'espc'=>[]]; // Array enviado a la vista
        
        $oSede = $sm->get('SedeTable')->fetchAll(); // Objetos Sede
        $oEspc = $sm->get('EspecialistaTable')->fetchAll(); // Objetos especialista
        
        foreach ($oSede as $s) $aVm['sede'][$s->idsede] = $s->nom;
        foreach ($oEspc as $e) $aVm['espc'][$e->idespecialista] = "$e->nom $e->ape";

        $vm = new ViewModel($aVm);
        //$vm->setTerminal(true);
        return $vm;
    }
    
    /***
     * Listado de especialistas
     */
    public function especialistasAction()
    {
        $oView = new ViewModel();
        $rq    = $this->getRequest();
        $sm    = $this->getServiceLocator();
        $esp   = $sm->get('EspecialistaTable');
        $oEspc = $esp->fetchAll();
        $oView->setVariable('oEspc',$oEspc);
        
        // Is Deleted
        if(key_exists('idesp', $_GET)){
            $res = $esp->deleteRow($_GET['idesp']);
            flog('delete espec->res:', $res);
            $this->redirect()->toUrl('/admin/horario/especialistas');
        }
        
        // No Post
        if (!$rq->isPost()){ return $oView; }
        
        // Es Post
        $dataPost = $rq->getPost()->toArray();
        
        $res = $esp->saveRow($dataPost);
        flog('save especialista->res',$res);
        $this->redirect()->toUrl('/admin/horario/especialistas');
        //return $oView;
    }
    
    /**
     * Listado de especialistas
     */
    public function sedesAction()
    {
        $oView = new ViewModel();
        $rq    = $this->getRequest();
        $sm    = $this->getServiceLocator();
        $esp   = $sm->get('SedeTable');
        $oSede = $esp->fetchAll();
        $oView->setVariable('oSede',$oSede);
        
        // Is Deleted
        if(key_exists('idsede', $_GET)){
            $res = $esp->deleteRow($_GET['idsede']);
            flog('delete sede->res:', $res);
            $this->redirect()->toUrl('/admin/horario/sedes');
        }
        
        // No Post
        if (!$rq->isPost()){ return $oView; }
        
        // Es Post
        $dataPost = $rq->getPost()->toArray();
        
        $res = $esp->saveRow($dataPost);
        flog('save sedes->res',$res);
        $this->redirect()->toUrl('/admin/horario/sedes');
        //return $oView;
    }
    
    /**
     * 
     */
    public function listRoomsAction()
    {
        $sm = $this->getServiceLocator();
        $idsede = $this->params()->fromRoute("idsede", 0);
        $resp = ['msg'=>'','data'=>[]];
        if(!$idsede)
            $resp['msg']="idsede debe ser numerico>0";
        else
            $resp['data'] = $sm->get('RoomTable')->getRoomsBySede($idsede); // Objetos especialista
        return new JsonModel($resp);
    }
    
    /**
     * 
     */
    public function saveHorarioAction()
    {   
        $sm = $this->getServiceLocator();
        $resp = ['msg'=>'','data'=>[]];
        $request = $this->getRequest();
        if ($request->isPost()) {
            $dataPost = $request->getPost()->toArray();
            $resp['msg'] = 'Is Post!';
            $resp['data']['rowAffected'] = $sm->get('HorarioTable')->saveRow($dataPost);
        }else {
            $resp['msg']='No es post!';
        }
        return new JsonModel($resp);
    }
    
    /**
     * 
     */
    public function citasByHorarioAction()
    {
        $sm = $this->getServiceLocator();
        $idHorario = $this->params()->fromRoute("idhorario", 0);
        $resp = ['msg'=>'','data'=>[]];
        
        if(!$idHorario){ $resp['msg']="idhorario debe ser numerico>0"; }
        else {
            $resp['data'] = $sm->get('HorarioTable')->getCitasByIdHorario($idHorario);
        }
        return new JsonModel($resp);
    }
    
}
