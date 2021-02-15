<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\RequestRequest\Writer;

/**
 * Description of StatusWriter
 *
 * @author eve
 */
class StatusWriter {

    public function __construct() {
        
    }

    public static function F(): StatusWriter {
        return new static();
    }

    public function run(Writer $w) {
        $writer_data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($writer_data);
        $this->update_status($writer_data);
    }

    private function get_filters() {
        return [
            'id' => ["IntMore0"],
            'status' => ['IntMore0']
        ];
    }

    protected function update_status(array $writer_data) {
        static::up_status($writer_data["id"], $writer_data["status"]);
    }

    public static function up_status(int $order_id, int $new_status) {
        $old_request = \DB\DB::F()->queryRow("SELECT * FROM request WHERE id=:P", [":P" => $order_id]);

        if ($old_request) {
            $current_status = intval($old_request["status_id"]);
            if ($current_status !== $new_status) {
                $requested_status = \DB\DB::F()->queryRow("SELECT * FROM request__status WHERE id=:P", [":P" => $new_status]);
                if ($requested_status) {
                    \DB\DB::F()->exec("
                        UPDATE request SET status_id=:P,status_name=:PP,status_color=:PPP,finished=:PPPP
                        WHERE id=:Pi
                        ", [
                        ":P" => intval($requested_status['id']),
                        ":PP" => trim($requested_status["name"]),
                        ":PPP" => $requested_status["color"],
                        ":PPPP" => $requested_status["final"],
                        ":Pi" => $order_id,
                    ]);
                    \Content\RequestProfile\Async\OnStatusChanged::mk_params()->add("id", $order_id)->run();
                }
            }
        }
    }

}
