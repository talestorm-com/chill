<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of CDNMKDirRequest
 *
 * @author eve
 * @property string $path
 * @property string $response
 * @property array $result
 * @property bool $success
 * @property int $http_code
 */
class CDNUploadRequest {

    use \common_accessors\TCommonAccess;

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

    public static function F() {
        return new static();
    }

    protected function mk_request(array $data, string $method = 'GET') {
        $encoded_data = [];
        foreach ($data as $key => $value) {
            $encoded_data[] = sprintf("%s=%s", urlencode($key), urlencode($value));
        }
        $query_hash = "{$method}+api.platformcraft.ru/1/objects?" . implode("&", $encoded_data);
        $hash = hash_hmac('sha256', $query_hash, \Config\Config::F()->CDN_KEY);
        $encoded_data[] = "hash={$hash}";
        return implode("&", $encoded_data);
    }

    protected function get_local_file_name($local) {
        $pi = pathinfo($local);
        return $pi['basename'];
    }

    public function run(string $remote_dir, string $local_file_name, string $remote_name = null, bool $private = false) {
        $data = [
            //"folder" => $this->path,
            "apiuserid" => \Config\Config::F()->CDN_API_ID,
            "timestamp" => time(),
                //"show_folders" => "true"
        ];
        $dname = CDNPathRecoverer::recover_path($remote_dir . "/" . ($remote_name ? $remote_name : $this->get_local_file_name($local_file_name)));
        $url = "https://api.platformcraft.ru/1/objects?" . $this->mk_request($data, 'POST');

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
            'file' => new \CurlFile($local_file_name, 'application/octet-stream', $remote_name ? $remote_name : $this->get_local_file_name($local_file_name)),
            'name' => $dname,
            'private' => $private ? "true" : "false",
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        $this->response = curl_exec($curl);
        $this->http_code = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        curl_close($curl);
        if ($this->http_code === 200) {
            $this->result = json_decode($this->response, true);
            if (is_array($this->result)) {
                $result_map = \DataMap\CommonDataMap::F()->rebind($this->result);
                if ($result_map->get_filtered("status", ['Strip', 'Trim', 'NEString', 'DefaultNull']) === "success") {
                    if ($result_map->get_filtered("code", ['IntMore0', 'DefaultNull']) === 200) {
                        $this->result = $result_map->get_filtered("object", ['NEArray', 'DefaultEmptyArray']);
                        $result_map->rebind($this->result);
                        $object_id = $result_map->get_filtered_def('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                        if ($object_id) {
                            $this->success = true;
                        }
                    }
                }
            }
        }
    }

}
