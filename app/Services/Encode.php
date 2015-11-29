<?php namespace App\Services;

class Encode {

    public function encode($ses)
    {
        $sesencoded = $ses;
        $num = mt_rand(3, 9);
        for ($i = 1; $i <= $num; $i++) {
            $sesencoded = base64_encode($sesencoded);
        }
        $aphaArray = array('Y', 'D', 'U', 'R', 'P', 'S', 'B', 'M', 'A', 'T', 'H');
        $sesencoded = $sesencoded . "+" . $aphaArray[$num];
        $sesencoded =
            base64_encode($sesencoded);
        return $sesencoded;
    }

    public function decode($str)
    {
        $aphaArray = array('Y', 'D', 'U', 'R', 'P', 'S', 'B', 'M', 'A', 'T', 'H');
        $decoded = base64_decode($str);
        list($decoded, $letter) = preg_split('\+', $decoded);
        for ($i = 0; $i < count($aphaArray); $i++) {
            if ($aphaArray[$i] == $letter)
                break;
        }
        for ($j = 1; $j <= $i; $j++) {
            $decoded = base64_decode($decoded);
        }
        return $decoded;
    }

//------------------------------- needs php_mcrypt.dll

    public function encrypt($data, $iv = null, $key = null)
    {
        global $config;
        /* Open the cipher "ecb", "cbc", "cfb", "ofb", "nofb" or "stream" */
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'ofb', ''); // http://php.net/manual/en/mcrypt.ciphers.php
        /* Create the IV and determine the keysize length */
        $iv = $iv ? $iv : $config['site']['iv'];
        if (preg_match('/^[a-zA-Z0-9\/\+]+\={0,2}$/', $iv)) {
            $iv = base64_decode($iv);
        }
        if (strlen($iv) < mcrypt_enc_get_iv_size($td)) {
            $iv = str_repeat($iv, round(mcrypt_enc_get_iv_size($td) / strlen($iv)));
        }
        $iv = substr($iv, 0, mcrypt_enc_get_iv_size($td));
        $ks = mcrypt_enc_get_key_size($td);
        /* Create key */
        $key = $key ? $key : $config['site']['key'];
        $key = substr(hash('sha256', $key), 0, $ks);
        /* Intialize encryption */
        mcrypt_generic_init($td, $key, $iv);
        /* Encrypt data */
        $encrypted = mcrypt_generic($td, $data);
        /* Terminate encryption handler */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return base64_encode($encrypted);
    }

    public function decrypt($encrypted_data, $iv = null, $key = null)
    {
        global $config;
        /* Open the cipher */
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'ofb', ''); //
        /* Create the IV and determine the keysize length */
        $iv = $iv ? $iv : $config['site']['iv'];
        if (preg_match('/^[a-zA-Z0-9\/\+]+\={0,2}$/', $iv)) {
            $iv = base64_decode($iv);
        }
        if (strlen($iv) < mcrypt_enc_get_iv_size($td)) {
            $iv = str_repeat($iv, round(mcrypt_enc_get_iv_size($td) / strlen($iv)));
        }
        $iv = substr($iv, 0, mcrypt_enc_get_iv_size($td));
        $ks = mcrypt_enc_get_key_size($td);
        /* Create key */
        $key = $key ? $key : $config['site']['key'];
        $key = substr(hash('sha256', $key), 0, $ks);
        /* Initialize encryption module for decryption */
        mcrypt_generic_init($td, $key, $iv);
        /* Decrypt encrypted string */
        $decrypted = mdecrypt_generic($td, base64_decode($encrypted_data));
        /* Terminate decryption handle and close module */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $decrypted;
    }

    public function makeIV()
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'ofb', ''); //
        $sz = mcrypt_enc_get_iv_size($td);
        $iv = base64_encode(substr(sha1(rand() . time() . rand(), true) . sha1(rand() . time() . rand(), true), 0, $sz));
        //$iv = base64_encode(mcrypt_create_iv(mcrypt_enc_get_iv_size($td), PHP_OS=='Linux' ? MCRYPT_DEV_RANDOM : MCRYPT_RAND));
        mcrypt_module_close($td);
        return $iv;
    }

    public function localHash($data, $glue = null)
    {
        global $config;
        return hash('sha1', $data . '_' . $glue . '_' . $config['site']['key']);
    }
}