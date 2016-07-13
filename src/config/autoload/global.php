<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$env = getenv('APPLICATION_ENV') ?: 'production';

$database  = ($env == 'local')?'centromedicoosi':'fisioterapia_web';
$username  = ($env == 'local')?'root'           :'fisioterapia_web';
$password  = ($env == 'local')?'123456'         :'Dwc3e$53';
$hostname  = ($env == 'local')?'localhost'      :'localhost';

return array(
    // ...
    'cf-item'=>'global',
    'js' => array(
        'base_url'=>''
    ),
    'view_manager' => array(
        'base_path' => 'http://www.centromedicoosi.com',
    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),

    'db' => array(
        'driver'   => 'Pdo',
        'dsn'      => "mysql:dbname=$database;host=$hostname",
        'username' => $username,
        'password' => $password,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    
    // credeciales para conexion mysql de procesos ajax
    'db-ajax' => array(
        'database'  => $database,
        'username'  => $username,
        'password'  => $password,
        'hostname'  => $hostname,
    ),
);
