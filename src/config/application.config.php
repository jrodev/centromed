<?php

$env = getenv('APPLICATION_ENV') ?: 'production';

// Use the $env value to determine which modules to load
$devModules = array();

if(\Zend\Console\Console::isConsole()){
    $devModules = array('Testing'); // Para probar nuevos modulos
}

if ($env == 'local') {
    $devModules = array(
        'Testing', // Para probar nuevos modulos
        // Debug de BD (github - BjyProfiler)
        'BjyProfiler',
        // UI developerTools (github - ZendDeveloperTools)
        //'ZendDeveloperTools', 
        // tracking dependencies between services (github - OcraServiceManager)
        'OcraServiceManager',
    );
}

return array(
    // This should be an array of module namespaces used in the application.
    'modules' => array_merge($devModules, array(
        'DataBase',    // in ./depends // Manipulation de BD
        'JavaScript',   // in ./depends
        'Payments',
        'BaseApp',
        'Admin',
        'Portal',
        'Session',
        'Testing',
    )),

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => array(
            './depends',
            './modules',
            './website',
            './vendor',
        ),

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),

        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        //'config_cache_enabled' => $booleanValue,

        // The key used to create the configuration cache file name.
        //'config_cache_key' => $stringKey,

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        //'module_map_cache_enabled' => $booleanValue,

        // The key used to create the class map cache file name.
        //'module_map_cache_key' => $stringKey,

        // The path in which to cache merged configuration.
        //'cache_dir' => $stringPath,

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        // 'check_dependencies' => true,
    ),

    // Used to create an own service manager. May contain one or more child arrays.
    //'service_listener_options' => array(
    //     array(
    //         'service_manager' => $stringServiceManagerName,
    //         'config_key'      => $stringConfigKey,
    //         'interface'       => $stringOptionalInterface,
    //         'method'          => $stringRequiredMethodName,
    //     ),
    // )

   // Initial configuration with which to seed the ServiceManager.
   // Should be compatible with Zend\ServiceManager\Config.
   // 'service_manager' => array(),
);