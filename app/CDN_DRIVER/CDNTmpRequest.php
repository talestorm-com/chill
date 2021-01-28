<?php

namespace CDN_DRIVER;

/**
 * temp link requestor
 *
 * @author eve 
 * @property string $response
 * @property array $result
 * @property string $link_result
 * @property int $result_ttl
 * @property bool $success
 * @property int $http_code
 */
class CDNTmpRequest {

    use \common_accessors\TCommonAccess;

    const TTL = 60 * 60 * 12;

    protected $success = false;
    protected $result;
    protected $response;
    protected $http_code;
    protected $id;

    /** @var string */
    protected $link_result;
    protected $result_ttl;

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

    /** @return string */
    protected function __get__link_result() {
        return $this->link_result;
    }

    protected function __get__result_ttl() {
        return $this->result_ttl;
    }

    protected function __construct() {
        ;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    protected function mk_request(array $data, string $method = 'GET') {
        $encoded_data = [];
        foreach ($data as $key => $value) {
            $encoded_data[] = sprintf("%s=%s", urlencode($key), urlencode($value));
        }
        $query_hash = "{$method}+api.platformcraft.ru/1/temp?" . implode("&", $encoded_data);
        $hash = hash_hmac('sha256', $query_hash, \Config\Config::F()->CDN_KEY);
        $encoded_data[] = "hash={$hash}";
        return implode("&", $encoded_data);
    }

    public function run(string $cdn_id, int $deadline) {
        $data = [
            "apiuserid" => \Config\Config::F()->CDN_API_ID,
            "timestamp" => time(),
        ];
        $this->id = $cdn_id;
        $url = "https://api.platformcraft.ru/1/temp?" . $this->mk_request($data, 'POST');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10
        ]);
        $fields = [
            "object_id" => $cdn_id,
            "endless" => false, #true - бесконечная ссылка, false - срок действия ссылки ограничен параметром exp
            "exp" => $deadline, #timestamp окончания срока действия (учитывается, если endless = false)
            "secure" => false,       #включить защиту для временной ссылки (hmac-sha256)
            "geo"=> new \stdClass()
        ];
        $encoded_fields = json_encode($fields);
       // \Out\Out::F()->add("mmmo" . spl_object_hash($this), $encoded_fields);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($encoded_fields))
        );
        $this->response = curl_exec($curl);
     //   \Out\Out::F()->add("mmms" . spl_object_hash($this), $this->response);
        $this->http_code = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        curl_close($curl);
        if ($this->http_code === 200) {
            $this->result = json_decode($this->response, true);
           // \Out\Out::F()->add("mmm" . spl_object_hash($this), $this->result);
            if (is_array($this->result)) {
                $result_map = \DataMap\CommonDataMap::F()->rebind($this->result);
                if ($result_map->get_filtered("status", ['Strip', 'Trim', 'NEString', 'DefaultNull']) === "success") {
                    if ($result_map->get_filtered("code", ['IntMore0', 'DefaultNull']) === 200) {
                        $this->success = true;
                        $this->result = $result_map->get_filtered("link", ["NEArray", "DefaultEmptyArray"]);
                        $result_map->rebind($this->result);
                        $this->link_result = $result_map->get_filtered('href', ['Trim', 'NEString', 'DefaultNull']);
                        $this->result_ttl = $result_map->get_filtered('exp', ['IntMore0', 'Default0']);
                    }
                }
            }
        }
    }

}
