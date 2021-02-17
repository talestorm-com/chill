<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of CDNTranscoderRequest
 *
 * @author eve
 * @property string $response
 * @property array $result
 * @property bool $success
 */
class CDNTranscoderRequest {

    use \common_accessors\TCommonAccess;

    protected $path = "/";
    protected $success = false;
    protected $result;
    protected $response;
    protected $http_code;

    protected function __get__success() {
        return $this->success;
    }

    protected function __get__response() {
        return $this->response;
    }

    protected function __get__result() {
        return $this->result;
    }

    protected function __get__http_code() {
        return $this->http_code;
    }

    protected function __construct() {
        ;
    }

    protected function __get__path() {
        return $this->path;
    }

    protected function __set__path(string $value) {
        $this->path = CDNPathRecoverer::recover_path($value);
        return $this;
    }

    /**
     * 
     * @param string $new_path
     * @return $this
     */
    public function set_path(string $new_path) {
        $this->path = CDNPathRecoverer::recover_path($new_path);
        return $this;
    }

    public static function F() {
        return new static();
    }

    protected function mk_request(array $data, string $method = 'GET', string $cdn_id) {
        $encoded_data = [];
        foreach ($data as $key => $value) {
            $encoded_data[] = sprintf("%s=%s", urlencode($key), urlencode($value));
        }
        $query_hash = "{$method}+api.platformcraft.ru/1/transcoder/{$cdn_id}?" . implode("&", $encoded_data);
        $hash = hash_hmac('sha256', $query_hash, \Config\Config::F()->CDN_KEY);
        $encoded_data[] = "hash={$hash}";
        return implode("&", $encoded_data);
    }

    public function run(string $cdn_id,string $template = "5e1ba97c0e47cf2f7dfdbd18") {
        $data = [
            //"folder" => $this->path,
            "apiuserid" => \Config\Config::F()->CDN_API_ID,
            "timestamp" => time(),
        ];
        $url = "https://api.platformcraft.ru/1/transcoder/{$cdn_id}?" . $this->mk_request($data, 'POST', $cdn_id);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10
        ]);
        $fields = [
            "presets" => [$template], // # список id форматов для перекодирования. Допустимо пустое поле, если start>0 или duration>0
            "del_original" => false, //         # удалять/не удалять оригинальный файл после транскодирования (по умолчанию: false)   
        ];
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $this->response = curl_exec($curl);
        $this->http_code = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        curl_close($curl);
        if ($this->http_code === 200) {
            $this->result = json_decode($this->response, true);
            if (is_array($this->result)) {
                $result_map = \DataMap\CommonDataMap::F()->rebind($this->result);
                if ($result_map->get_filtered("status", ['Strip', 'Trim', 'NEString', 'DefaultNull']) === "success") {
                    if ($result_map->get_filtered("code", ['IntMore0', 'DefaultNull']) === 200) {
                        $this->success = true;
                    }
                }
            }
        }
    }

}
