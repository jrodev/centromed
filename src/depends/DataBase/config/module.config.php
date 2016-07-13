<?php

return array(
    
    /* ... */
    
    'view_manager' => array(
        'template_map' => array(
            'layout/dblayout' => __DIR__ . '/../view/layout/dblayout.phtml',
            'error/dberror'   => __DIR__ . '/../view/error/dberror.phtml',
        ),
    ),
    
    'tables'=>array(
        "Admin\\Model\\" => array(
            'cita' => array(
                array('name'=>'idcita'     , 'value'=>NULL, 'null'=>0), // El primero es el PK (Convencion)
                array('name'=>'idusuario'  , 'value'=>NULL, 'null'=>0),
                array('name'=>'idseguro'   , 'value'=>NULL, 'null'=>0),
                //array('name'=>'idsede'     , 'value'=>0, 'null'=>0),
                array('name'=>'idhorario'  , 'value'=>NULL, 'null'=>0),
                array('name'=>'datehour'   , 'value'=>NULL, 'null'=>0),
                array('name'=>'codprom'    , 'value'=>NULL, 'null'=>1),
                array('name'=>'idasistente', 'value'=>NULL, 'null'=>1),
                array('name'=>'codpago'    , 'value'=>NULL, 'null'=>0),
                
                array('name'=>'estpago'    , 'value'=>1, 'null'=>0),
                array('name'=>'estcita'    , 'value'=>1, 'null'=>0),
                array('name'=>'reagen'     , 'value'=>0, 'null'=>0),
                array('name'=>'idpago'     , 'value'=>0, 'null'=>0),
                array('name'=>'codtrans'   , 'value'=>0, 'null'=>0),
                // 'title'  => $cita->fechareg, // automatico en la BD
            ),
            'especialista' => array(
                array('name'=>'idespecialista', 'value'=>0, 'null'=>0),
                array('name'=>'nom'           , 'value'=>0, 'null'=>0),
                array('name'=>'ape'           , 'value'=>0, 'null'=>0),
                array('name'=>'esp'           , 'value'=>0, 'null'=>0),
            ),
            'horario' => array(
                array('name'=>'idhorario'     , 'value'=>0, 'null'=>0),
                array('name'=>'idespecialista', 'value'=>0, 'null'=>0),
                array('name'=>'idroom'        , 'value'=>0, 'null'=>0),
                array('name'=>'fecha'         , 'value'=>0, 'null'=>0),
                array('name'=>'coddia'        , 'value'=>0, 'null'=>0),
                array('name'=>'nomdia'        , 'value'=>0, 'null'=>0),
                array('name'=>'horaini'       , 'value'=>0, 'null'=>0),
                array('name'=>'horafin'       , 'value'=>0, 'null'=>0),
            ),
            'sede' => array(
                array('name'=>'idsede' , 'value'=>0, 'null'=>0),
                array('name'=>'nom'    , 'value'=>0, 'null'=>0),
                array('name'=>'direc'  , 'value'=>0, 'null'=>0),
                array('name'=>'telf'   , 'value'=>0, 'null'=>0),
            ),
            'room' => array(
                array('name'=>'idroom' , 'value'=>0, 'null'=>0),
                array('name'=>'idsede' , 'value'=>0, 'null'=>0),
                array('name'=>'descr'  , 'value'=>0, 'null'=>0),
            ),
        ),
        "Portal\\Model\\"=>array(
            'usuario' => array(
                array('name'=>'idusuario' , 'value'=>0, 'null'=>0),
                array('name'=>'sex'       , 'value'=>0, 'null'=>0),
                array('name'=>'nom'       , 'value'=>0, 'null'=>0),
                array('name'=>'ape'       , 'value'=>0, 'null'=>0),
                array('name'=>'tipodoc'   , 'value'=>0, 'null'=>0),
                array('name'=>'nrodoc'    , 'value'=>0, 'null'=>0),
                array('name'=>'fecnac'    , 'value'=>0, 'null'=>0),
                array('name'=>'iddistrito', 'value'=>0, 'null'=>0),
                array('name'=>'telf'      , 'value'=>0, 'null'=>0),
                array('name'=>'cel'       , 'value'=>0, 'null'=>0),
                array('name'=>'mail'      , 'value'=>0, 'null'=>0),
                array('name'=>'pass'      , 'value'=>0, 'null'=>0),
            )
        ),
    ),
);
