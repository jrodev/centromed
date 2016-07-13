<?php

return array(
    
    'session_config' => array(
        'params' => array(
            //'cookie_lifetime' => 0,   // session infinita (seteado en module.php)
            'use_cookies' => true,
            'use_only_cookies' => true,
            'cookie_httponly' => true,
            'name' => 'PHPSESS',
        ),
    ),
    'session_ns'   => array( 'portal', 'admin' ), // Namespace para las sesiones
    'session_temp' => array( 'time' => 60 * 60 * 8 ), // 8 horas
    'session_norm' => array( 'time' => 0 ),  // Tiempo indefinido
 
    // -------------------------------------------------------------------------------
    'router' => array(
        'routes' => array(
            'home' => array(),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            //'Session\Controller\Foo' => 'Session\Controller\FooController'
        ),
    ),

);
