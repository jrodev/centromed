<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'base-admin' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
            
                        
            'dashb-admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/dashboard[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Dashboard',
                        'action'        => 'index',
                    ),
                ),
            ),
            
            'admin-citas-mapa' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin/mapa',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Admin\Controller','controller'=>'Citas','action'=>'mapa',
                    ),
                )
            ),
            
            
            'nueva-cita' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/nueva-cita[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Citas',
                        'action'        => 'nueva-cita',
                    ),
                ),
            ),
            
            
            
            
            
            
            'save-horario' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/horario/save-horario[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Horario',
                        'action'        => 'save-horario',
                    ),
                ),
            ),

            'horario' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/horario/index[/[:sede[/[:room[/]]]]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'sede'       => '[1-9][0-9]*',
                        'room'       => '[0-9]*', // Puede ser cero (Para el caso de todos los rooms)
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Horario',
                        'action'     => 'index',
                        'sede'       => 1,
                        'room'       => 0,
                    ),
                ),
            ),

            'list-rooms' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/horario/list-rooms[/[:idsede]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'idsede'     => '[1-9][0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Horario',
                        'action'     => 'list-rooms',
                    ),
                ),
            ),
            
            // Listado especialistas
            'list-espec' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/horario/especialistas',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Horario',
                        'action'     => 'especialistas',
                    ),
                ),
            ),
            
            // Listado sedes
            'list-sedes' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/horario/sedes',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Horario',
                        'action'     => 'sedes',
                    ),
                ),
            ),
            
            // CITAS
            'list-citas' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/citas[/[:sede[/[:esp[/[:dia[/[:pag[/[:text]]]]]]]]]]',
                    'constraints' => array(
                        'sede'=> '[0-9]*',
                        'esp' => '[0-9]*',
                        //'dia' => '[0-9\-\s]*', // ahora es range
                        'pag' => '[1-9][0-9]*',
                        'text'=> '[0-9a-zA-Z]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Citas',
                        'action'     => 'index',
                        'sede'       => 0,
                        'esp'        => 0,
                        'dia'        => 0,
                        'pag'        => 1,
                        'text'       => '',
                    ),
                ),
            ),
            
            // CITA: cambio de estado
            'estado-cita' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/estado-cita[/]',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Citas',
                        'action'     => 'estado-cita',
                    ),
                ),
            ),
            
            // CITA: get usuario
            'get-usuario' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/get-usuario[/]',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Citas',
                        'action'     => 'get-usuario',
                    ),
                ),
            ),
            
            // CITA: get usuario
            'citas-by-horario' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/admin/citas-by-horario/:idhorario',
                    'constraints' => array(
                        'idhorario'=> '[1-9][0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Horario',
                        'action'     => 'citas-by-horario',
                    ),
                ),
            ),
            
        ),
    ),
    
    // Menu de Admin ------------------------------------------------------
    'admin-menu' => array(
        // ctrl-name
        'citas' => array(
            'url'   => '#',
            'icon'  => 'fa-calendar-o',
            'text'  => 'Citas',
            'items' => array(
                //acti-name
                'nueva-cita' => array(
                    'text' => 'Citas consulta medica',
                    'icon' => 'fa-edit',
                    'url'  => 'admin/citas/nueva-cita',
                ),
                'terapia-fisica' => array(
                    'text' => 'Citas consulta medica',
                    'icon' => 'fa-plus-square',
                    'url'  => 'admin/citas/nueva-cita',
                ),
            ),
        ),
        'pascientes' => array(
            'url'   => '#',
            'text'  => 'Gestion de Pascientes',
            'icon'  => 'fa-th',
            'items' => array(),
        ),
        'ciclos' => array(
            'url'   => '#',
            'text'  => 'Gestion de Ciclos',
            'icon'  => 'fa-th',
            'items' => array(),
        ),
        'historia' => array(
            'url'   => '#',
            'text'  => 'Gestion de Historia',
            'icon'  => 'fa-th',
            'items' => array(),
        ),
    ),
    
    // ------------------------------------------------------------------------
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Dashboard' => 'Admin\Controller\DashboardController',
            'Admin\Controller\Horario' => 'Admin\Controller\HorarioController',
            'Admin\Controller\Citas' => 'Admin\Controller\CitasController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/admin'        => __DIR__ . '/../view/layout/admin.phtml',
            'renders/_head-adm'   => __DIR__ . '/../view/layout/renders/_head.phtml',
            'renders/_header-adm' => __DIR__ . '/../view/layout/renders/_header.phtml',
            'renders/_aside-adm'  => __DIR__ . '/../view/layout/renders/_aside.phtml',
            'renders/_footer-adm' => __DIR__ . '/../view/layout/renders/_footer.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
