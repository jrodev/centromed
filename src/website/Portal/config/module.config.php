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
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Portal\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /portal/:controller/:action
            'portal' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/portal',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
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
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            // Routing Index -------------------------------------------------
            // Last
            //'index-blog-cita-contactenos-mapa' => array(
            //    'type' => 'segment',
            //   'options' => array(
            //        'route' => '/:action[/]',
            //        'defaults' => array(
            //            '__NAMESPACE__'=>'Portal\Controller','controller'=>'Index','action'=>'blog|solicite-cita|contactenos|solicite-cita2',
            //        ),
            //    )
            //),
            'portal-index-blog' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Index','action'=>'blog',
                    ),
                )
            ),
            'portal-index-cita' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/solicite-cita',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Index','action'=>'solicite-cita',
                    ),
                )
            ),
            'portal-index-cita2' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/solicite-cita2',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Index','action'=>'solicite-cita2',
                    ),
                )
            ),
            'portal-index-contactenos' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/contactenos',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Index','action'=>'contactenos',
                    ),
                )
            ),
            'portal-index-mapa' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/mapa',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Index','action'=>'mapa',
                    ),
                )
            ),
            
            // Routing for Nosotros ------------------------------------------
            'portal-index-nosotros' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/nosotros',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Nosotros','action'=>'nosotros',
                    ),
                )
            ),
            'portal-index-staff' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/staff',
                    'defaults' => array(
                        '__NAMESPACE__'=>'Portal\Controller','controller'=>'Nosotros','action'=>'staff',
                    ),
                )
            ),

            // Routing for Enfermedades --------------------------------------
            'portal-enfermedades-dolecias' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/fisioterapia-para-:action[/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller' => 'Enfermedades',
                        'action' => 'artritis-reumatoidea',
                    ),
                )
            ),
            
            // Routing for Especialidades -------------------------------------
            'portal-especialidades-index' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/especialidades',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller' => 'Especialidades',
                        'action' => 'index',
                    ),
                )
            ),
            
            // Especialidades segun el Action
            'portal-especialidades' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/especialidad-:action[/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*', // '[a-zA-Z]+[-]?[a-zA-Z0-9_]*' solo para abc-cde|abcde
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller' => 'Especialidades',
                        'action' => 'traumatologica',
                    ),
                )
            ),
            
            // Porque elegir osi
            'portal-porque-osi' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/porque-osi',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller' => 'Index',
                        'action' => 'porque-osi',
                    ),
                )
            ),
            
            // Trabaje con nosotros
            'portal-trabaje-nosotros' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/trabaje-con-nosotros',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller' => 'Index',
                        'action' => 'trabaje-con-nosotros',
                    ),
                )
            ),     
            
            // Porque elegir osi
            'portal-aviso-privacidad' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/aviso-de-privacidad',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller' => 'Index',
                        'action' => 'aviso-de-privacidad',
                    ),
                )
            ),
            
            
            'get-espec' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/get-espec[/[:idsede[/[:fecha[/]]]]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'idsede'     => '[1-9][0-9]*',
                        'fecha'      => '[0-9][0-9\-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Portal\Controller\Index',
                        'action'     => 'get-espec',
                        'idsede'     => 1,
                        'fecha'     => '2014-01-01',
                    ),
                ),
            ),
            
            'get-citas-espec' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/get-citas-espec[/[:idsede[/[:idespec[/[:fecha]]]]]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'idsede'     => '[1-9][0-9]*',
                        'idespec'    => '[1-9][0-9]*',
                        'fecha'      => '[0-9][0-9\-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Portal\Controller\Index',
                        'action'     => 'get-citas-espec',
                        'idsede'     => 1,
                        'idespec'    => 1,
                        'fecha'      => '2014-01-01',
                    ),
                ),
            ),
            
            // Usuario
            'cuenta-usuario' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/mi-cuenta[/citas-[:page]]',
                    'constraints' => array(
                        'page' => '[1-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller'    => 'Usuario',
                        'action'        => 'cuenta',
                        'page'          => 1,
                    ),
                ),
            ),
            
            // logout usuario
            'logout-usuario' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'=>'/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Portal\Controller',
                        'controller'    => 'Usuario',
                        'action'        => 'logout',
                    ),
                ),

            ),
        ),
    ),
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
            'Portal\Controller\Index' => 'Portal\Controller\IndexController',
            'Portal\Controller\Nosotros' => 'Portal\Controller\NosotrosController',
            'Portal\Controller\Especialidades' => 'Portal\Controller\EspecialidadesController',
            'Portal\Controller\Enfermedades' => 'Portal\Controller\EnfermedadesController',
            'Portal\Controller\Usuario' => 'Portal\Controller\UsuarioController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'  => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layoutCita'=> __DIR__ . '/../view/layout/layoutCita.phtml',
            'renders/_head'  => __DIR__ . '/../view/layout/renders/_head.phtml',
            'renders/_header'=> __DIR__ . '/../view/layout/renders/_header.phtml',
            'renders/_footer'=> __DIR__ . '/../view/layout/renders/_footer.phtml',
            
            'usuario/login'    => __DIR__ . '/../view/portal/usuario/login.phtml',
            'usuario/registrar'=> __DIR__ . '/../view/portal/usuario/registrar.phtml',
            'usuario/reagendar'=> __DIR__ . '/../view/portal/usuario/reagendar.phtml',
            
            'portal/index/index' => __DIR__ . '/../view/portal/index/index.phtml',
            'error/404'      => __DIR__ . '/../view/error/404.phtml',
            'error/index'    => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        // Para respuestas json (Los actions no necesitan archivo phtml asociado)
        'strategies' => array(
            'ViewJsonStrategy',
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
