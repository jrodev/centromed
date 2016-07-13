<?php

return array(

    'gnupg' => array(
        'keys_path'   => '/var/www/.gnupg',

        'fingerprint' => '66CB122BCAD4C281FB9AB5615B92AE356DE7A468'//local -> '32C0C096C069361BB4AB43129E8D92FFDB2D5A12',
        // 'error_mode'  => GNUPG_ERROR_EXCEPTION, // GNUPG_ERROR_EXCEPTION, GNUPG_ERROR_SILENT

    ),
    
    'alignet' => array(
        'path_gpg'=> '/usr/bin/gpg',
        'action' => 'https://test2.alignetsac.com/VPOS2G/faces/pages/startPayme.xhtml',
        'inputs' => array(
            'acquirerId'        => '144',
            'idCommerce'        => '7078',
            'language'          => 'SP',
            
            'billing' => array(
                'FirstName'  => false,
                'LastName'   => false,
                'EMail'      => false,
                'Address'    => 'Lima', // Deberia ser dinamico
                'ZIP'        => '0001', // Deberia ser dinamico
                'City'       => 'Lima',
                'State'      => 'Pe',
                'Country'    => 'Peru',
                'Phone'      => false,                
            ),
            
            'shipping' => array(
                'FirstName' => false,
                'LastName'  => false,
                'Email'     => false,
                'Address'   => 'Lima',  // Deberia ser dinamico
                'ZIP'       => '0001',  // Deberia ser dinamico
                'City'      => 'Lima',
                'State'     => 'Pe',
                'Country'   => 'Peru',
                'Phone'     => false,                
            ),

            'descriptionProducts' => 'Pago de una cita en el centro medico OSI',
            'programmingLanguage' => 'PHP',
            'commerceAssociated'  => 'CENTRO OSI',
            'userCommerce'        => '00001',
            'userCodePayme'       => '5--89--1154',
            'mcc'                 => '517',
        ),
    ),
    
    'pagos' => array(
        'pago-efectivo' => array(
            /*
            'url'        => 'http://dev.2.pagoefectivo.pe/PagoEfectivoWSGeneral/WSCIP.asmx?wsdl',
            'urlGenPago' => 'http://dev.2.pagoefectivo.pe/GenPago.aspx?Token=',
            'urlCIPGen'  => 'http://dev.2.pagoefectivo.pe/CIPGenerado.aspx',
            'crypto' => array(
                'url'         => 'http://dev.2.pagoefectivo.pe/PagoEfectivoWSCrypto/WSCrypto.asmx?WSDL',
                'publicKey'   => 'public.key',
                'privateKey'  => 'private.key',
                'securityPath'=> __DIR__ . "/pagoefectivo",
            ),
            // portal params
            'capi'          => '3216fd0d-03c1-434d-b47b-1da8d2b44c15',
            'cclave'        => 'a382f211-def5-4fc4-8b52-8e94f3ca0caa',
            'apiKey'        => 'UR3',
            'mailAdmin'     => 'anderson.poccorpachi@orbis.pe',
            'paymentMeans'  => '1,2',
            'expirationDays'=> 14,
            'minExpiracion' => 15, //Expiracion sin GENERAR el CIP (En minutos)
            'minTransacion' => 4320, //->Rango de Tiempo de las transacciones a evaluar
            */
           'servPars' => array(
               'apiKey' => 'FTO',//MERCHAN_ID,
                'capi'  => 'd4a73250-4fa2-4f0f-b154-7d606ed2c5fc',//CAPI,
                'cclave'=> '4bbf4dce-c181-4296-9891-c27827bc9a1c',//CCLAVE,
                'url'   => 'http://pre.pagoefectivo.pe/PagoEfectivoWSGeneral/WSCIP.asmx?wsdl',//WSCIP,
                'url2'  => 'http://pre.pagoefectivo.pe/PagoEfectivoWSGeneralv2/service.asmx?wsdl',//WSGENCIP,
                'crypto' => array(
                    'securityPath'=> dirname(__FILE__).DIRECTORY_SEPARATOR.'key',//SECURITY_PATH
                    'publicKey'   => 'SPE_PublicKey.1pz',//PUBLICKEY,
                    'privateKey'  => 'FTO_PrivateKey.1pz',//PRIVATEKEY,
                    'url'         => 'http://pre.pagoefectivo.pe/PagoEfectivoWSCrypto/WSCrypto.asmx?wsdl',//WSCRYPTA,
                ),
                'gen' => array('url' => 'http://pre.pagoefectivo.pe/GenPago.aspx',/*WSGENPAGO*/),
                'mailAdmin' => 'amac@gmail.com',//EMAIL_CONTACTO,
                'medioPago' => '1,2',//MEDIO_PAGO,
                'imgbarra'  => 'http://pre.pagoefectivo.pe/CNT/GenImgCIP.aspx',//WSCIPIMG
            ),
            'extra' => array(
                'urlIfrmPago' => 'http://pre.pagoefectivo.pe/GenPagoIF.aspx',
                'urlGenPago'  => 'http://pre.pagoefectivo.pe/GenPago.aspx',
                'WSGENCIP'    => 'http://pre.pagoefectivo.pe/PagoEfectivoWSGeneralv2/service.asmx?wsdl',
                'WSCRYPTAB'   => 'http://pre.pagoefectivo.pe/pasarela/pasarela/crypta.asmx?wsdl'
            ),
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'pe-notifications' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/pago-efectivo/notifications[/]',
                    'defaults' => array(
                        'controller' => 'Payments\Controller\PagoEfectivo',
                        'action'     => 'notifications',
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Payments\Controller\PagoEfectivo' => 'Payments\Controller\PagoEfectivoController'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'error/404'          => __DIR__ . '/../view/error/404.phtml',
            'error/index'        => __DIR__ . '/../view/error/index.phtml',
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
