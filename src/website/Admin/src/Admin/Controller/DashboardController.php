<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Zend\View\Model\ViewModel;

class DashboardController extends AbstractActionController
{
    // Login admin
    public function indexAction()
    {
        $vm = new ViewModel();
        
        return $vm;
    }

    // Login admin
    public function allAction()
    {
        $vm = new ViewModel();
        
        return $vm;
    }

}
