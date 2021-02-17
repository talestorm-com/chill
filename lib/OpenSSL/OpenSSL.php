<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OpenSSL;

/**
 * Класс шифрует переданные данные и опционально сжимает их
 * @property string $pubKey
 * @property string $prvKey;
 */
class OpenSSL {

    use \common_accessors\TCommonAccess;

    protected $key;
    protected $openKey;

    /**
     * 
     * @param string $key_id
     * @param int $size_on_create
     * @return \OpenSSL\OpenSSL
     */
    public static function F(string $key_id, int $size_on_create = 512): OpenSSL {
        $base_key_path = \Config\Config::F()->OPENSSL_KEY_PATH;
        $keypath = "{$base_key_path}{$key_id}.private";
        if (!file_exists($keypath)) {
            $key = static::createKey($size_on_create);
            file_put_contents($keypath, $key, LOCK_EX);
        }
        return new static(file_get_contents($keypath));
    }

    public static function createKey($keySize = 512) {
        $key = openssl_pkey_new(['private_key_bits' => $keySize, 'encrypt_key' => false, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        $out = '';
        openssl_pkey_export($key, $out);
        return $out;
    }

    protected function __construct($key) {
        $this->key = openssl_pkey_get_private($key);
        $this->key ? false : OpenSSLException::R("InvalidKey");
        $this->openKey = openssl_pkey_get_details($this->key)['key'];
    }

    public function encryptRaw($data) {
        $dtc = serialize($data);
        $result = '';
        $env_keys = [];
        $r = openssl_seal($dtc, $result, $env_keys, [$this->openKey]);
        $r ? false : OpenSSLException::R(openssl_error_string());
        return [$result, $env_keys[0]];
    }

    public function encryptStringPack($str) {
        $result = '';

        if (!openssl_public_encrypt($str, $result, $this->pubKey)) {
            return 'error';
        }
        $ood = "";
        return unpack('H*sign', $result)['sign'];
    }

    public function decrypt_packed_string($enc) {
        $result = "";
        $encup = pack('H*', $enc);
        if (!openssl_private_decrypt($encup, $result, $this->key)) {
            return 'error';
        }
        return trim($result);
    }

    public function encryptData($data) {
        $resultA = $this->encryptRaw($data);
        $result = Base64Url::base64url_encode($resultA[0]) . Base64Url::base64url_encode($resultA[1]);
        $result = implode('.', [unpack('H*string', $result)['string'], unpack('H*string', $env_keys[0])['string']]);
        return $result;
    }

    public function encryptPack($data) {
        $u = $this->encryptRaw($data);
        $a = gzcompress($u[0], 9);
        $b = gzcompress($u[1], 9);
        return Base64Url::base64url_encode($a) . '.' . Base64Url::base64url_encode($b);
        //return unpack('H*string', $a)['string'];
    }

    public function decryptData($encr) {
        $result = '';
        openssl_private_decrypt($encr, $result, $this->key, OPENSSL_PKCS1_PADDING);
        return unserialize($result);
    }

    protected function __get__pubKey() {
        return $this->openKey;
    }

    protected function __get__prvKey() {
        $a = '';
        $b = openssl_pkey_export($this->key, $a);
        return $b ? $a : null;
    }

    public function sign(string $data): string {
        $signature = '';
        if (!openssl_sign($data, $signature, $this->key, 'md5')) {
            OpenSSLException::R(openssl_error_string());
        }
        return unpack('H*sign', $signature)['sign'];
    }

    public function checkSign(string $data, string $signature): bool {
        $r = openssl_verify($data, pack('H*', $signature), $this->openKey, 'md5');
        if ($r < 0) {
            OpenSSLException::R(openssl_error_string());
        }
        return $r == 1 ? true : false;
    }

}
