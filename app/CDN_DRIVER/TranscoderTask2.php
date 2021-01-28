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
class TranscoderTask2 extends \AsyncTask\AsyncTaskAbstract {

    const TASK_PER_LAUNCH = 1000;

    protected $executed_tasks = [];

    protected function get_log_file_name(): string {
        return 'transcoder_d';
    }

    protected function get_storepath() {
        return \Config\Config::F()->LOCAL_TMP_PATH . str_ireplace(["\\", '/'], '_', 'CDN_DRIVER_TranscoderTask');
    }

    protected function restore_executed() {
        $path = $this->get_storepath();
        if (file_exists($path) && is_file($path) && is_readable($path) && is_writeable($path)) {
            $this->executed_tasks = json_decode(file_get_contents($path), true);
        } else {
            $this->executed_tasks = [];
        }
    }

    protected function store_executed() {
        $path = $this->get_storepath();
        file_put_contents($path, json_encode($this->executed_tasks));
    }

    protected function exec() {
        $this->restore_executed();
        $id_list = $this->id_list();
        $id_list_2 = [];
        foreach ($id_list as $id) { // если файл уже был обработан - то его не надо трогать
            if (array_key_exists($id, $this->executed_tasks)) {
                continue;
            }
            $id_list_2[] = $id;
        }
        $id_list = $id_list_2;
        $this->log(sprintf("found %s records", count($id_list)));        
        $c = 0;
        foreach ($id_list as $id) {
            try {
                if (!array_key_exists($id, $this->executed_tasks)) {
                    $this->run_transcoder_task($id);
                    $c++;
                    if ($c > static::TASK_PER_LAUNCH) {
                        break;
                    }
                    usleep(300000);
                } else {
                    $this->log(sprintf("%s:skipped - alredy converted", $id), 'skip');
                }
            } catch (\Throwable $e) {
                $this->log("{$e->getMessage()},{$e->getFile()},{$e->getLine()}", 'error');
            }
        }
        $this->store_executed();
    }

    protected function run_transcoder_task(string $id) {
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
                        //  check if file alredy autoencoded (name ends with`(1920x1080)`)
                        if (!preg_match('/\(1920x1080\)\./i', $file_info_request->result['name'])) {
                            $this->run_transcoder_task_r($id, $width, $height);
                        }
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

    protected function run_transcoder_task_r(string $id, int $width, int $height) {
        if ($width === 1920 && $height === 1080) {
            $request = CDNTranscoderRequest::F();
            $request->run($id);
            if ($request->success) {
                $response = $request->result;
                $this->log(sprintf("%s:%s", $id, \DataMap\CommonDataMap::F()->rebind($response)->get_filtered('message', ["Strip", "Trim", 'NEString', 'DefaultEmptyString'])), 'task_success');
                $this->executed_tasks[$id] = $id;
            } else {
                $this->log(sprintf("%s:bad request, size:%sx%s", $id, $width, $height), 'warning');
            }
        } else {
            $this->log(sprintf("%s:wrong size:%sx%s", $id, $width, $height), 'warning');
        }
    }

    protected function id_list() {
        $result = [];
        $query = "SELECT cdn_id,size FROM media__content__cdn__file";
        $rows = \DB\DB::F()->queryAll($query);
        foreach ($rows as $row) {
            $id = \Filters\FilterManager::F()->apply_chain($row['cdn_id'], ['Trim', 'NEString', 'DefaultNull']);
            $id ? $result[] = $id : 0;
        }
        $query = "SELECT cdn_id FROM media__lent__video";
        $rows = \DB\DB::F()->queryAll($query);
        foreach ($rows as $row) {
            $id = \Filters\FilterManager::F()->apply_chain($row['cdn_id'], ['Trim', 'NEString', 'DefaultNull']);
            $id ? $result[] = $id : 0;
        }
        return $result;
    }

}
