<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

ini_set('date.timezone', 'America/Lima');

// Is ajax
if(
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
){
    // Condicionando segun la URI que hace la peticion (ajax de la seccion solicite-cita)
    $uri = $_SERVER['REQUEST_URI'];
    $aUri = explode('/',$uri); // $aUri[0]==''
    $isGetEspec      = (trim($aUri[1])==='get-espec');
    $isGetCitasEspec = (trim($aUri[1])==='get-citas-espec');
    
    if ($isGetEspec || $isGetCitasEspec) {
        
        /* Conexion a BD con PHP nativo */
        $cnf = require '../config/autoload/global.php';
        $pars = $cnf['db-ajax'];
        $db = new mysqli( $pars['hostname'],$pars['username'],$pars['password'],$pars['database'] );
        
        /* Get resultados de BD */
        function mysqlQuery($query, $db) {
            $db->query("SET NAMES 'utf8'");
            $result = $db->query($query);
            $results = array();
            while ($row = $result->fetch_object()) { $results[] = $row; }
            return $results;
        }
        
        // uri: /get-espec/[sede]/yyyy-mm-dd
        if ($isGetEspec) {
            $idsede  = $db->real_escape_string($aUri[2]);
            $fecha   = $db->real_escape_string($aUri[3]);
            $query   = "
                SELECT h.idhorario AS idhorario, h.idespecialista AS idespecialista, e.nom AS nom, e.ape AS ape, h.horaini AS horaini, h.horafin AS horafin 
                FROM osi_horario AS h 
                LEFT JOIN osi_room AS r ON h.idroom=r.idroom 
                LEFT JOIN osi_especialista AS e ON h.idespecialista=e.idespecialista 
                LEFT JOIN osi_sede AS s ON r.idsede=s.idsede 
                WHERE (r.idsede = '$idsede') AND h.horaini LIKE '%$fecha%' GROUP BY h.idespecialista ORDER BY e.nom ASC
            ";
        }
        
        // uri: /get-citas-espec/[sede]/[espec]/yyyy-mm-dd
        if ($isGetCitasEspec) {
            $idsede  = $db->real_escape_string($aUri[2]);
            $idespec = $db->real_escape_string($aUri[3]);
            $fecha   = $db->real_escape_string($aUri[4]);
            $query   = "
                SELECT h.idespecialista AS idespec, e.nom AS nom, 
                       GROUP_CONCAT(DISTINCT h.idhorario SEPARATOR '|') AS idshs, 
                       GROUP_CONCAT(DISTINCT h.horaini SEPARATOR '|') AS inis, 
                       GROUP_CONCAT(DISTINCT h.horafin SEPARATOR '|') AS fins, 
                       GROUP_CONCAT(c.datehour SEPARATOR '|') AS citas 
                FROM osi_horario AS h 
                INNER JOIN osi_room AS r ON h.idroom=r.idroom 
                INNER JOIN osi_especialista AS e ON e.idespecialista=h.idespecialista 
                INNER JOIN osi_sede AS s ON r.idsede=s.idsede 
                LEFT JOIN osi_cita AS c ON c.idhorario=h.idhorario 
                WHERE h.horaini LIKE '%$fecha%' AND (r.idsede = '$idsede' AND h.idespecialista = '$idespec') 
                GROUP BY e.idespecialista
            ";
        }

        $data = mysqlQuery($query, $db);
        header("Content-type: application/json; charset=utf-8");
        echo json_encode(array('msg'=>'ok','data'=>$data));
        exit();
    }
}

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
