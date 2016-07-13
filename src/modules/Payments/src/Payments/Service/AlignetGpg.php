<?php

namespace Payments\Service;

class AlignetGpg {

    private $gpg = '/usr/bin/gpg';
    private $homedir = '/home/jrodev/Projects/PHP/Zend2/fisioterapiaosi/src/.gnupg';
    /**
     * @param array $config
     */
    public function __construct($cnfAlignet = null)
    {
        $this->gpg = $cnfAlignet['path_gpg'];
        /*$cmd = "/usr/bin/gpg --list-public-keys";
        var_dump($cmd);
        var_dump(shell_exec($cmd));*/
    }
    

    public function encrypt($string, $passphrase)
    {
        $cmd = "echo $string | {$this->gpg} --always-trust --armor -e -r $passphrase 2>&1";
        //var_dump($cmd);
        $data = shell_exec($cmd); //var_dump($data);
        
        return $data;
    }
    
    public function decrypt($encryptMessage, $passphrase)
    {
        // Escribiendo $encryptMessage en un archivo temporal unico
        $tempFile = tempnam('/tmp', 'pgp');
        $fp = fopen($tempFile, 'w');
        fwrite($fp, $encryptMessage);
        fclose($fp);
        
        // Ruta de archivo donde se alacenara mensaje desencriptado
        $random = substr(md5(rand()), 0, 3);
        $decryptedFile = sys_get_temp_dir().'/'.'foo'.$random.'.tmp'; //var_dump($decryptedFile);
        
        // Creando y ejecutando comando de desencriptacion para guardar resultado en: $decryptedFile
        $cmd = "{$this->gpg} --textmode --batch --no-tty --passphrase $passphrase -o $decryptedFile -d $tempFile 2>&1";
        //var_dump($cmd);
        $shellRes = shell_exec($cmd); //var_dump($shellRes);
        
        // Obteniendo mensaje desencriptado y borrando archivos creados
        $fp2 = fopen($decryptedFile, 'r');
        $decryptedMessage = fread($fp2, filesize($decryptedFile));
        fclose($fp2);
        unlink($tempFile);
        unlink($decryptedFile);
        
        return $decryptedMessage;
    }

}