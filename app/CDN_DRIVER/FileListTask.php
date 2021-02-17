<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of FileListTask
 *
 * @author eve
 */
class FileListTask {

    public static function run() {
        die('locked');
        $task = CDNListRequest2::F();
        $task->run();
        $map = \DataMap\CommonDataMap::F();
        $result = [];
        foreach ($task->result as $file_record) {
            $map->rebind($file_record);
            if (!$map->get_filtered('is_dir', ['Boolean', 'DefaultFalse'])) {
                $ct = $map->get_filtered('content_type', ['Strip', 'Trim', 'NEString']);
                if (preg_match("/^video\/.*/i", $ct)) {
                    $file_id = $map->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
                    if ($file_id) {
                        $result[] = $file_id;
                    }
                }
            }
        }
        $b = \DB\SQLTools\SQLBuilder::F()->push("DROP TABLE IF EXISTS `transcoder__task`;
           CREATE TABLE `transcoder__task`(id VARCHAR(255) NOT NULL,r420 VARCHAR(1024) NULL DEFAULT NULL,r720 VARCHAR(1024) NULL DEFAULT NULL,PRIMARY KEY(id))ENGINE=InnoDB; ");
        $ins = [];
        $p = [];
        $c = 0;
        foreach ($result as $rs) {
            $c++;
            $ins[] = "(:P{$c}id)";
            $p[":P{$c}id"] = $rs;
        }
        if (count($ins)) {
            $b->push(sprintf("INSERT INTO `transcoder__task` (id) VALUES %s ON DUPLICATE KEY UPDATE id=VALUES(id);", implode(",", $ins)))
                    ->push_params($p)->execute();
        }
        var_dump($result);
        die();
    }

}
