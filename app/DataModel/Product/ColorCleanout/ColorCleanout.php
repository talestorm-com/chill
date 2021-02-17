<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\ColorCleanout;

class ColorCleanout extends \AsyncTask\AsyncTaskAbstract {

    protected function exec() {
        $mutex = \Mutex\SimpleNamedMutex::F('color_cleanout');
        if ($mutex->get_if()) {
            try {
                $this->log("starting color cleanout");
                $this->remove_unused_colors();
                $this->remove_orphaned_files();
                $this->log("finish color cleanout");
            } catch (\Throwable $e) {
                $mutex->release();
                throw $e;
            }
        }
    }

    protected function get_log_file_name() {
        return "color_cleanout";
    }

    protected function remove_unused_colors() {
        $guids_raw = \DB\DB::F()->queryAll("SELECT guid FROM catalog__product__color");
        $guids = [];
        foreach ($guids_raw as $row) {
            $guids[] = $row['guid'];
        }
        \ImageFly\ImageInfoManager::F()->clear_orphaned_colors($guids);
    }

    protected function remove_orphaned_files() {
        \ImageFly\ImageFly::F()->clear_orphaned_color_files();
    }

}
