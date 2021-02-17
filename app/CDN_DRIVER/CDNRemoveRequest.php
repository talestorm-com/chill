<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of CDNRemoveRequest
 *
 * @author eve
 * @property string $response
 * @property array $result
 * @property bool $success
 * @property int $http_code
 */
class CDNRemoveRequest {

    use \common_accessors\TCommonAccess;

    protected $success = false;
    protected $result;
    protected $response;
    protected $http_code;
    protected $run_url;
    protected $cnd_id;

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

    public static function F() {
        return new static();
    }

    protected function mk_request(array $data, string $method = 'GET', $cdn_id) {
        $encoded_data = [];
        foreach ($data as $key => $value) {
            $encoded_data[] = sprintf("%s=%s", urlencode($key), urlencode($value));
        }
        $query_hash = "{$method}+api.platformcraft.ru/1/objects/{$cdn_id}?" . implode("&", $encoded_data);
        $hash = hash_hmac('sha256', $query_hash, \Config\Config::F()->CDN_KEY);
        $encoded_data[] = "hash={$hash}";
        return implode("&", $encoded_data);
    }

    public function run(string $cdn_id) {
        $data = [
            "apiuserid" => \Config\Config::F()->CDN_API_ID,
            "timestamp" => time(),
        ];
        $url = "https://api.platformcraft.ru/1/objects/{$cdn_id}?" . $this->mk_request($data, 'DELETE', $cdn_id);
        $this->cnd_id = $cdn_id;
        $this->run_url = $url;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10
        ]);
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
