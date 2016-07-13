<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    // Login admin
    public function indexAction()
    {
        $vm = new ViewModel();
        $vm->setTerminal(TRUE); // Deshabilitando layout (por ser login)
        
        return $vm;
    }
    
    // register admin
    public function registerAction()
    {
        $vm = new ViewModel();
        $vm->setTerminal(TRUE); // Deshabilitando layout (por ser register)
        
        return $vm;
    }
    
    /* En HorarioController
    public function horarioAction()
    {
        $sm = $this->getServiceLocator();
        
        // Si es una peticion ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $sede = $this->params()->fromRoute("sede",0);
            $room = $this->params()->fromRoute("room",0);
            $resp = ['msg'=>'ok','data'=>[]];
            
            if(!$sede){ $sede['msg'] = 'Sede numeric mayor a 0'; }
            else { $resp['data'] = $sm->get('HorarioTable')->getHorarioByRoom($room); }

            return new JsonModel($resp);
        }
        
        
        $aVm = ['sede'=>[], 'espc'=>[]]; // Array enviado a la vista
        
        $oSede = $sm->get('SedeTable')->fetchAll(); // Objetos Sede
        $oEspc = $sm->get('EspecialistaTable')->fetchAll(); // Objetos especialista
        
        foreach ($oSede as $s) $aVm['sede'][$s->idsede] = $s->nom;
        foreach ($oEspc as $e) $aVm['espc'][$e->idespecialista] = "$e->nom $e->ape";

        $vm = new ViewModel($aVm);
        $vm->setTerminal(true);
        return $vm;
    }*/
    
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
/* en admin controller
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
    }*/
}
