<?php

$env = getenv('APPLICATION_ENV') ?: 'production';

$devModules = array();
if ($env == 'local') {
    $devModules = array(
        'BjyProfiler',
        'OcraServiceManager',
    );
}

return array(
    'modules' => array_merge($devModules, array(
        'DataBase',    // in ./depends // Manipulation de BD
        'JavaScript',   // in ./depends
        'Payments',
        'BaseApp',
        'Admin',
        'Portal',
        'Session',
    )),

    'module_listener_options' => array(

        'module_paths' => array(
            './depends',  // con ./ o ../ o ../../ o ../../../ o sin nada, funciona! q raro?
            '../modules',
            '../website',
            '../vendor',
        ),

        'config_glob_paths' => array(
            '../../../config/autoload/{,*.}{global,local}.php',
        ),

    ),

);