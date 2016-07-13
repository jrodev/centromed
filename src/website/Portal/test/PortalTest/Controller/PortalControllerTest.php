<?php

namespace PortalTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class PortalControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = TRUE;
    
    public function setUp()
    {
        //$sm = \ExampleTest\Bootstrap::getServiceManager();
        $this->setApplicationConfig(\ExampleTest\Bootstrap::getConfig());
        parent::setUp();
    }
    
    public function testIndexActionCanBeAccessed()
    {
        echo "testIndexActionCanBeAccessed:\n\n";
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Portal');
        $this->assertControllerName('Portal\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }
    
    public function testAddActionRedirectsAfterValidPost()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        
        $loginMock = $this->getMockBuilder('Session\Service\Login')
                          ->setMethods(['isLoggedIn','getProfile'])->disableOriginalConstructor()->getMock();
        $loginMock->expects($this->any())->method('isLoggedIn')->will($this->returnValue(TRUE));
        $loginMock->expects($this->any())->method('getProfile')->will($this->returnValue(array('data'))); 
        $serviceManager->setService('Session\Service\Login', $loginMock);
        
        $citaTableMock = $this->getMockBuilder('CitaTable')->disableOriginalConstructor()->getMock();
        $citaTableMock->expects($this->once())->method('saveRow')->will($this->returnValue(null));
        $serviceManager->setService('CitaTable', $citaTableMock);

        $postData = array('var1'=>'val1','var2'=>'val2');
        $this->dispatch('/solicite-cita2', 'POST', $postData);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/solicite-cita2?ok');
    }
}