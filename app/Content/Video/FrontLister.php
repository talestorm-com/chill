<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video;

/**
 * Description of FrontLister
 *
 * @author eve
 */
class FrontLister extends Lister {

    protected function pre_request(array &$params, int &$c) {
        $this->filter->addDirectCondition("( A.active=1 )");
    }

    protected function after_process(array $items, array &$result, \DataMap\IDataMap $input, \Out\Out $out = null) {
        if ($input->get_filtered("with_acl", ["Boolean", "DefaultFalse"])) {
            $access_list = [];
            $am = \Auth\ProductAccessMonitor::F();
            foreach ($items as $row) {
                $cost = floatval($row["cost"]);
                $key = implode("", [VideoGroup::ACCESS_KEY, $row['id']]);
                if ($cost > 0) {
                    $access_list[$key] = $am->has_access_to_tutorial((string) $row['id']);
                } else {
                    $access_list[$key] = true;
                }
            }
            $result["acl"] = $access_list;
            $out ? $out->add("acl", $access_list) : 0;
        }
    }

}
