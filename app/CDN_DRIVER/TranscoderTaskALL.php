<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of TranscoderTask
 *
 * @author eve
 */
class TranscoderTaskALL extends \AsyncTask\AsyncTaskAbstract {

    const TASK_PER_LAUNCH = 100000;

    protected $executed_tasks = [];

    protected function get_log_file_name(): string {
        return 'transcoder_all';
    }

    protected function get_all_files() {
        $query = "SELECT * FROM `transcoder__task` WHERE r420 IS NULL OR r720 IS NULL";
        return \DB\DB::F()->queryAll($query);
    }

    protected function exec() {
        $files = $this->get_all_files();
        $this->log(sprintf("found %s records", count($files)));
        $c = 0;
        foreach ($files as $fileinfo) {
            try {
                $this->run_transcoder_task($fileinfo['id'], ($fileinfo['420'] ? false : true), ($fileinfo['720'] ? false : true));
            } catch (\Throwable $e) {
                $this->log("{$e->getMessage()},{$e->getFile()},{$e->getLine()}", 'error');
                \DB\DB::F()->exec("UPDATE `transcoder__task` SET r420=COALESCE(r420,:P1),r720=COALESCE(r720,:P1) WHERE id=:P2;", ["P2" => $fileinfo['id'], ":P1" => $e->getMessage()]);
            }
            $c++;
            if ($c > static::TASK_PER_LAUNCH) {
                break;
            }
            usleep(300000);
        }
    }

    protected function run_transcoder_task(string $id, $u480, $u720) {
        $file_info_request = CDNInfoRequest::F();
        $file_info_request->run($id);
        if (!$file_info_request->success) {
            $this->log("error on file info:{$id}", 'error');
            return;
        }
        if (is_array($file_info_request->result) && array_key_exists('advanced', $file_info_request->result) && (is_array($file_info_request->result['advanced']))) {
            if (array_key_exists('video_streams', $file_info_request->result['advanced']) && is_array($file_info_request->result['advanced']['video_streams']) && count($file_info_request->result['advanced']['video_streams'])) {
                $vsi = $file_info_request->result['advanced']['video_streams'][0];
                if (array_key_exists('width', $vsi) && array_key_exists('height', $vsi)) {
                    $width = intval($vsi['width']);
                    $height = intval($vsi['height']);
                    if ($width && $height) {
                        $this->log("{$id}:{$width}x{$height}");
                        $this->run_transcoder_task_r($id, $width, $height, $u480, $u720);
                        return;
                    }
                    \Errors\common_error::RF("%s: bas width or height; %sx%s", $id, $vsi['width'], $vsi['height']);
                }
                \Errors\common_error::RF("%s: no width or height; %s", $id, $file_info_request->response);
            }
            \Errors\common_error::RF("%s: no video streams; %s", $id, $file_info_request->response);
        }
        \Errors\common_error::RF("%s: no advanced info; %s", $id, $file_info_request->response);
        die();
    }

    protected function run_transcoder_task_r(string $id, int $width, int $height, $u480, $u720) {
        if ($width === 1920 && $height === 1080) {
            if ($u480) {
                $request480 = CDNTranscoderRequest::F();
                $request480->run($id, "5e1c6cdaef3db50e0f8efe68"); //480p
                if ($request480->success) {
                    $response = $request480->result;
                    $this->log(sprintf("%s:%s", $id, \DataMap\CommonDataMap::F()->rebind($response)->get_filtered('message', ["Strip", "Trim", 'NEString', 'DefaultEmptyString'])), 'task_success');
                    \DB\DB::F()->exec("UPDATE `transcoder__task` SET r420=:P1 WHERE id=:P2;", ["P2" => $id, ":P1" => \DataMap\CommonDataMap::F()->rebind($response)->get_filtered('message', ["Strip", "Trim", 'NEString', 'DefaultEmptyString'])]);
                } else {
                    $this->log(sprintf("%s:bad request, size:%sx%s,%s", $id, $width, $height, $request480->response), 'warning');
                    \DB\DB::F()->exec("UPDATE `transcoder__task` SET r420=:P1 WHERE id=:P2;", ["P2" => $id, ":P1" => sprintf("%s:bad request, size:%sx%s", $id, $width, $height)]);
                }
            }
            if ($u720) {
                $request720 = CDNTranscoderRequest::F();
                $request720->run($id, "5e1c6cdaef3db50e0f8efe67"); //720p//5e1c6cdaef3db50e0f8efe67
                if ($request720->success) {
                    $response = $request720->result;
                    $this->log(sprintf("%s:%s", $id, \DataMap\CommonDataMap::F()->rebind($response)->get_filtered('message', ["Strip", "Trim", 'NEString', 'DefaultEmptyString'])), 'task_success');
                    \DB\DB::F()->exec("UPDATE `transcoder__task` SET r720=:P1 WHERE id=:P2;", ["P2" => $id, ":P1" => \DataMap\CommonDataMap::F()->rebind($response)->get_filtered('message', ["Strip", "Trim", 'NEString', 'DefaultEmptyString'])]);
                } else {
                    $this->log(sprintf("%s:bad request, size:%sx%s,%s", $id, $width, $height, $request720->response), 'warning');
                    \DB\DB::F()->exec("UPDATE `transcoder__task` SET r720=:P1 WHERE id=:P2;", ["P2" => $id, ":P1" => sprintf("%s:bad request, size:%sx%s", $id, $width, $height)]);
                }
            }
        } else {
            $this->log(sprintf("%s:wrong size:%sx%s", $id, $width, $height), 'warning');
            \DB\DB::F()->exec("UPDATE `transcoder__task` SET r720=COALESCE(r720,:P1),r420=COALESCE(r420,:P1) WHERE id=:P2;", ["P2" => $id, ":P1" => sprintf("%s:bad request, size:%sx%s", $id, $width, $height)]);
        }
    }

}
