<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Testing\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

// Para envio de email
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Message;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $CONFIG = array();
        $CONFIG['gnupg_home']        = '/home/jrodev/Projects/PHP/Zend2/fisioterapiaosi/src/.gnupg';
        $CONFIG['gnupg_fingerprint'] = '91518A728C2ACA3738BFD34E176DA2AEDF6F2D3E';//'7C9FEB6D9ADB87FBF2C4422C9BDAF8E7D40B0B72';

        $data = 'Informacion confidencial';

        //putenv("GNUPGHOME={$CONFIG['gnupg_home']}");
        $gpg = new \gnupg();//$res = gnupg_init();
        $gpg->seterrormode(GNUPG_ERROR_EXCEPTION); //gnupg_seterrormode($res, GNUPG_ERROR_EXCEPTION); 
        $gpg->addencryptkey($CONFIG['gnupg_fingerprint']); //gnupg_addencryptkey($res, $CONFIG['gnupg_fingerprint']); 
        $encrypted = $gpg->encrypt($data); //gnupg_encrypt($res, $data);
        echo "Encrypted text: \n<pre>$encrypted</pre>\n";

        $passphrase = 'CENTROOSI';
        
        echo "<hr>Other method: text decrypt<br>".$this->decript($encrypted, $passphrase)."<hr>";
        /*
        $gpg->adddecryptkey($CONFIG['gnupg_fingerprint'], $passphrase); //gnupg_adddecryptkey($res, $CONFIG['gnupg_fingerprint'], $passphrase);
        $decrypted = $gpg->decrypt($encrypted);//gnupg_decrypt($res, $encrypted); 
        echo "<hr><hr>Method formal: text decrypt<br> $decrypted";*/

        var_dump($this->encript('Hello', $passphrase));
        exit;
        /* ---------------------------------------------------------------- */
        $commerce = '89';
        $cardHolderCommerce = $gpg->encrypt('18185');
        $names     = $gpg->encrypt('Jose');
        $lastNames = $gpg->encrypt('Rosas');
        $mail      = $gpg->encrypt('abc@midominio.com');
        $wsdl = 'https://test2.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
        $client = new \SoapClient($wsdl);
        $params = array(
            'idEntCommerce'=>$commerce,
            'codCardHolderCommerce'=>$cardHolderCommerce,
            'names'=>$names,
            'lastNames'=>$lastNames,
            'mail'=>$mail
        );
        //var_dump($params);
        $result = $client->RegisterCardHolder($params);
        var_dump($result);

        return new ViewModel();
    }
    
    
    public function walletAction()
    {
        //$CONFIG['gnupg_fingerprint'] = '91518A728C2ACA3738BFD34E176DA2AEDF6F2D3E';
        //putenv("GNUPGHOME=/var/www/.gnupg");
        $gpg        = $this->getServiceLocator()->get('AlignetGpg');
        $string     = 'Informacion confidencial';
        $passphrase = 'walletosi';
        
        if(TRUE){
            $encrypted = $gpg->encrypt($string, $passphrase);
            echo "<pre>";
            echo "Encrypted text:"; 
            var_dump($encrypted);
            echo "<hr>Other method: text decrypt<br>";var_dump($gpg->decrypt(trim($encrypted), $passphrase));
            echo "</pre>";
            
            putenv("GNUPGHOME=/var/www/.gnupg");
            $gnpg = new \gnupg();//$res = gnupg_init();
            $gnpg->seterrormode(GNUPG_ERROR_EXCEPTION);
            $gnpg->addencryptkey('A08BDF77D85C56D8161999835A62307397A7BA10');
            $encrypted = $gnpg->encrypt('informacion confidencial2');
            echo "<pre>"; var_dump($encrypted); echo "</pre>";
            $gnpg->adddecryptkey('A08BDF77D85C56D8161999835A62307397A7BA10','walletosi');
            
            $decrypted = $gnpg->decrypt($encrypted);
            var_dump($gnpg->geterror());
            echo "<hr />"; var_dump($decrypted);
        }
        exit;
        $wsdl = 'https://test2.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
        $client = new \SoapClient($wsdl);
        $params = array(
            'idEntCommerce'        => '89',
            'codCardHolderCommerce'=> trim($gpg->encrypt('18185', $passphrase)),
            'names'                => trim($gpg->encrypt('Jose', $passphrase)),
            'lastNames'            => trim($gpg->encrypt('Rosas', $passphrase)),
            'mail'                 => trim($gpg->encrypt('abc@midominio.com', $passphrase)),
        );
        echo "<h3>PARAMETROS:</h3><hr><pre>";
        var_dump($params);
        echo "</pre><br>";
        $result = $client->RegisterCardHolder($params);
        echo "<h3>RESPUESTA:</h3><hr><pre>";
        var_dump($result);
        echo "</pre><br>";
        exit;
    }

    
    public function vpostAction()
    {
        var_dump((bool)$this->getRequest()->getQuery('foo'));
        $CONFIG = array();
        $CONFIG['gnupg_home'] = '/var/www/.gnupg';
        $CONFIG['gnupg_fingerprint'] = '94518726E2BE8AA07B814A75A4E3DED1A2FB8103';
        
        $gpg = new \gnupg();
        putenv("GNUPGHOME={$CONFIG['gnupg_home']}");
        $gpg->seterrormode(GNUPG_ERROR_SILENT);
        $gpg->addencryptkey($CONFIG['gnupg_fingerprint']);

        $purchaseOperationNumber = $gpg->encrypt("".$this->random_numbers(5));
        $purchaseAmount = $gpg->encrypt(3500);
        $purchaseCurrencyCode = $gpg->encrypt(604);

        $count;
        $purchaseOperationNumber = str_replace('%5Cn', '%0A', urlencode($purchaseOperationNumber), $count);
        $purchaseAmount          = str_replace('%5Cn', '%0A', urlencode($purchaseAmount), $count);
        $purchaseCurrencyCode    = str_replace('%5Cn', '%0A', urlencode($purchaseCurrencyCode), $count);

        $viewParams = array(
            'purchaseOperationNumber' => $purchaseOperationNumber,
            'purchaseAmount'          => $purchaseAmount,
            'purchaseCurrencyCode'    => $purchaseCurrencyCode,
        );

        var_dump($viewParams);

        return new ViewModel($viewParams);
        
    }

    public function vposResponseAction(){
        //var_dump($_POST);
        $htmlBody = "<p><h3>Respuesta de pago de ALIGNET:</h3>";
        foreach($_POST as $i=>$v){
            $htmlBody .= "<b>$i:</b>$v<br />";
        }
        $htmlBody .= "</p>";
        
        $this->sendMail($htmlBody, 'text-Body', 'Alignet testing', 'abc@testpay.com', array('jrodev@yahoo.es','aries_19_mad@yahoo.es'));
        echo "<hr />".$htmlBody;
    }
    
    public function sendMail($htmlBody, $textBody, $subject, $from, $to) {
        $htmlPart = new MimePart($htmlBody);
        $htmlPart->type = "text/html; charset=UTF-8";

        $textPart = new MimePart($textBody);
        $textPart->type = "text/plain; charset=UTF-8";
        
        /*
        $attachment = new MimePart(fopen("file_path", "r"));
        $attachment->type = "application/pdf";
        $attachment->filename = $fileName;
        $attachment->encoding = Mime\Mime::ENCODING_BASE64;
        $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;*/

        $body = new MimeMessage();

        $body->setParts(array($textPart, $htmlPart/*, $attachment*/));

        $message = new Mail\Message();
        $message->setFrom($from);
        $message->addTo($to);
        $message->setSubject($subject);

        $message->setEncoding("UTF-8");
        $message->setBody($body);

        $transport = new SendmailTransport();
        $transport->send($message);
    }
    
    public function localEncrDecrAction()
    {
        echo "<h3>Prueba 11</h3>";
        echo "<p>".get_current_user()."<p>";

        try {
            //set_include_path("/usr/share/pear"); //ADD PEAR FOLDER TO INCLUDE PATH
            require_once 'Crypt/GPG.php'; //INCLUDE PEAR LIBRARY
            $options = array('homedir'=>'/var/www/.gnupg','debug'=>FALSE); //KEYRING DIRECTORY
            $gpg = new \Crypt_GPG($options);
            $gpg->addEncryptKey('91518A728C2ACA3738BFD34E176DA2AEDF6F2D3E');
            $encrypted = $gpg->encrypt('Informacion confidencial');
            echo "Encrypted text: \n<pre>$encrypted</pre>\n";

            $gpg->addDecryptKey('91518A728C2ACA3738BFD34E176DA2AEDF6F2D3E','CENTROOSI');
            $decrypted = $gpg->decrypt($encrypted); //$gpg->decrypt($encrypted);
            echo "<hr>text decrypt<br> $decrypted";
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        exit;
    }
            
    function random_numbers($digits)
    {
        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;
        return mt_rand($min, $max);
    }
    
    
    private function encript($string, $passphrase)
    {
        $gpg = '/usr/bin/gpg';
        $data = shell_exec("echo $string | $gpg --armor -e -r $passphrase 2>&1");
        return $data;
    }
    
    private function decript($encrypt_message, $passphrase)
    {
        $gpg = '/usr/bin/gpg';
        $filename = tempnam('/tmp', 'pgp');
        $fp = fopen($filename, 'w');
        fwrite($fp, $encrypt_message);
        fclose($fp);
        
        $random = substr(md5(rand()), 0, 3);
        $unencrypted_file = sys_get_temp_dir() . '/' . 'foo' . $random . '.tmp';
        //var_dump($unencrypted_file);
        $command = "echo |" . $gpg . " --batch --no-tty --passphrase $passphrase -o $unencrypted_file -d $filename 2>&1";
        $decrypted_message = shell_exec($command);
        //var_dump($decrypted_message);
        $fp2 = fopen($unencrypted_file, 'r');
        $contenido = fread($fp2, filesize($unencrypted_file));
        fclose($fp2);
        unlink($filename);
        unlink($unencrypted_file);
        return $contenido;
    }

}
