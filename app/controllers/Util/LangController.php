<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\Util;

class LangController extends \controllers\abstract_controller {

    protected function API_get_language() {
        $section = \DataMap\InputDataMap::F()->get_filtered("area",["Strip","Trim","NEString","DefaultNull"]);
        $section?0: \Errors\common_error::R("invalid request");
        $data = ['dummy' => 'dummy'];
        $rows = \DB\DB::F()->queryAll("SELECT token tm,translation ts FROM lang__tokens WHERE section=:P",[":P"=>$section]);
        foreach ($rows as $row) {
            $data[$row['tm']] = $row['ts'];
        }
        $this->out->add('tokens', $data);
        $marshall = $this->out->marshall();
        if(array_key_exists("authorization", $marshall)){
            unset($marshall["authorization"]);
        }
        $json = json_encode($marshall);
        $path = \Config\Config::F()->WEB_ROOT . "assets" . DIRECTORY_SEPARATOR . "language" . DIRECTORY_SEPARATOR . "{$section}.json";
        file_put_contents($path, $json);
    }

    protected function API_post_tokens() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'section' => ["Strip", "Trim", "NEString"],
            'tokens' => ["ArrayOfStrippedNEString", "NEArray"]
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        if ($data['tokens'] && is_array($data['tokens']) && count($data['tokens'])) {
            $params = [":Ps" => $data["section"]];
            $c = 0;
            $inserts = [];
            foreach ($data["tokens"] as $token) {
                $inserts[] = "(:Ps,:P{$c}t,:P{$c}t)";
                $params[":P{$c}t"] = $token;
                $c++;
            }
            if (false && count($inserts)) {
                $query = "INSERT INTO lang__tokens(section,token,translation) VALUES " . implode(",", $inserts) . " ON DUPLICATE KEY UPDATE token=VALUES(token);";
                \DB\DB::F()->exec($query, $params);
            }
        }
    }

}
