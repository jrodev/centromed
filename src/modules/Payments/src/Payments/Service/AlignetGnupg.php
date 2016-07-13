<?php

namespace Payments\Service;

class AlignetGnupg extends \gnupg {

    /**
     * 
     * @param array $config
     */
    public function __construct($config = null)
    {
        putenv("GNUPGHOME={$config['keys_path']}");
        $this->seterrormode($config['error_mode']);
        $this->addencryptkey($config['fingerprint']);
    }

}