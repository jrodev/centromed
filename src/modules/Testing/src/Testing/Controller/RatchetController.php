<?php

namespace Testing\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Console\Request as ConsoleRequest
;

use Ratchet\Server\IoServer,
    Ratchet\Http\HttpServer,
    Ratchet\WebSocket\WsServer,
    Testing\Ratchet\Chat
;

class RatchetController extends AbstractActionController
{
    /**
     * Crea un server en el puerto 8084 
     * si abrimos varias terminales y en la primera digitamos:
     * php index.php testing ratchet index // por ser zend 2
     * en las demas: telnet localhost 8084, y escribimos texto en 
     * cada terminal telnet, el texto escrito se refrescara para
     * todas las ventanas.
     */
    public function indexAction()
    {
        //echo "ratchet!!!"; exit;
        
        // Al querer acceder por el browser
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
            return ;
        }
        
        // Al acceder por consola
        $server = IoServer::factory(new Chat(), 8084);
        $server->run();
        exit;
    }
    
    /**
     * Si se accede por la terminal
     */
    public function browserWorkAction()
    {
        // Al querer acceder por el browser
        if (!$this->getRequest() instanceof ConsoleRequest) {
            return new ViewModel();
        }
        
        // Al acceder por consola
        $server = IoServer::factory(new HttpServer(new WsServer(new Chat())), 8089);
        $server->run();
        exit;
    }
}
