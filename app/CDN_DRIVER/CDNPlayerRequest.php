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
 * @property string $player_id
 */
class CDNPlayerRequest {

    use \common_accessors\TCommonAccess;

    protected $path = "/";
    protected $success = false;
    protected $result;
    protected $response;
    protected $http_code;
    protected $player_id;

    protected function __get__player_id() {
        return $this->player_id;
    }

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

    protected function mk_request(array $data, string $method = 'GET') {
        $encoded_data = [];
        foreach ($data as $key => $value) {
            $encoded_data[] = sprintf("%s=%s", urlencode($key), urlencode($value));
        }
        $query_hash = "{$method}+api.platformcraft.ru/1/players?" . implode("&", $encoded_data);
        $hash = hash_hmac('sha256', $query_hash, \Config\Config::F()->CDN_KEY);
        $encoded_data[] = "hash={$hash}";
        return implode("&", $encoded_data);
    }

    protected function get_files_data(array $cdn_ids) {
        $result = [];
        foreach ($cdn_ids as $cdn_id) {
            $m = [];
            $vid = $cdn_id;
            $tid = $cdn_id;
            if (preg_match("/^(?P<vid>.{1,}):(?P<tid>.{1,})$/i", $cdn_id, $m)) {
                $vid = $m['vid'];
                $tid = $m['tid'];
            }
            $infoRequest = CDNInfoRequest::F();
            $infoRequest->run($vid);
            $info = $infoRequest->result;
            if (is_array($info) && array_key_exists('content_type', $info) && is_string($info['content_type'])) {
                if (preg_match("/^video.*/i", $info['content_type'])) {
                    $advanced = array_key_exists('advanced', $info) && is_array($info['advanced']) ? $info['advanced'] : null;
                    if ($advanced) {
                        $video_streams = array_key_exists('video_streams', $advanced) && is_array($advanced['video_streams']) ? $advanced['video_streams'] : null;
                        if ($video_streams && count($video_streams) && array_key_exists(0, $video_streams)) {
                            $stream = $video_streams[0];
                            if ($stream && is_array($stream)) {
                                if (array_key_exists('width', $stream) && array_key_exists('height', $stream) && is_scalar($stream['width']) && is_scalar($stream['height'])) {
                                    $width = intval($stream['width']);
                                    $height = intval($stream['height']);
                                    if ($width && $height) {
                                        $result["{$width}"] = $tid;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 
     * @param string[] $cdn_id
     */
    public function run(array $cdn_ids) {
        $files_data = $this->get_files_data($cdn_ids);
        $ids = array_values($files_data);
        count($ids) ? 0 : \Errors\common_error::R("empty player");
        $data = [
            //"folder" => $this->path,
            "apiuserid" => \Config\Config::F()->CDN_API_ID,
            "timestamp" => time(),
        ];
        $url = "https://api.platformcraft.ru/1/players?" . $this->mk_request($data, 'POST', $cdn_id);

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
            "name" => "player_" . md5(implode("", $ids)),
            "videos" => $files_data,
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
                        $player = $result_map->get_filtered('player', ['NEArray', 'DefaultNull']);
                        if ($player) {
                            $player_map = \DataMap\CommonDataMap::F()->rebind($player);
                            $player_id = $player_map->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                            if ($player_id) {
                                $this->player_id = $player_id;
                                $this->success = true;
                            }
                        }
                    }
                }
            }
        }
    }

}
