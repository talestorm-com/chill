<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of VideoUploader
 *
 * @author eve
 */
class VideoUploader {

    public function __construct() {
        ;
    }

    public static function F(): VideoUploader {
        return new static();
    }

    public function check_file_ffprobe(string $path, VideoGroupWriter $w): bool {
        if (file_exists($path) && is_readable($path) && is_file($path)) {
            try {
                $probe = new \ImageFly\FFProbe($path);
                $streams = array_key_exists("streams", $probe->metadata) ? $probe->metadata["streams"] : [];
                foreach ($streams as $stream) {
                    if (is_array($stream) && array_key_exists("codec_tag", $stream)) {
                        if (\Helpers\Helpers::NEString($stream["codec_tag"], null) && $stream["codec_tag"] !== "0x00000000" && $stream["codec_tag"] !== "0x0000") {
                            return true;
                        }
                    }
                }
            } catch (\Throwable $e) {
                $w->append_message(sprintf("video process error: file:%s, error:%s in:%s at %s", $path, $e->getMessage(), $e->getFile(), $e->getLine()));
            }
        }
        return false;
    }

    public function run(VideoGroupWriter $w) {
        //ищем загрузки:
        $uploads = [];        
        $reference = $w->runtime->get_filtered("item_writer_writed", ['ArrayOfNEString', 'NEArray', 'DefaultEmptyArray']);
        foreach ($reference as $uploaded_uid) {            
            $files = \DataMap\FileMap::F()->get_by_field_name("video_item_video_{$uploaded_uid}");            
            if ($files && count($files)) {
                $uploaded_item = [
                    'uid' => $uploaded_uid,
                    'file' => $files[0],
                    'convert' => $w->common_input->get_filtered("video_item_convert_{$uploaded_uid}", ["Boolean", "DefaultTrue"]),
                ];
                $uploads[] = $uploaded_item;
            }
        }
        
        
        $tasks_to_convert = [];
        $task_ro_shoot = [];
        $b = \DB\SQLTools\SQLBuilder::F();
        foreach ($uploads as $uploaded_item) {
            // проверяем файл, если ок - перемещаем во времянку
            /* @var $uploaded_item['file'] \DataMap\UploadedFile */
            if ($this->check_file_ffprobe($uploaded_item['file']->tmp_name,$w)) {
                if ($uploaded_item['convert']) {
                    $tasks_to_convert[] = $uploaded_item;
                    $b->push("UPDATE video__group__item SET video=:P{$b->c}video WHERE id=:P{$b->c}id AND uid=:P{$b->c}uid;");
                    $b->push_params([
                        ":P{$b->c}video" => "pending",
                        ":P{$b->c}id" => $w->result_id,
                        ":P{$b->c}uid" => $uploaded_item['uid'],
                    ]);
                    $b->inc_counter();
                } else {// конверсия не нужна, прямое перемещение
                    $fnnoext = str_ireplace('-', '', $uploaded_item['uid']);
                    $file_name = $fnnoext . $uploaded_item['file']->get_source_extension_dotted();
                    $file_dir = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $w->result_id . DIRECTORY_SEPARATOR;
                    if (!(file_exists($file_dir) && is_dir($file_dir) && is_writable($file_dir))) {
                        @mkdir($file_dir, 0777, true);
                    }
                    if (!(file_exists($file_dir) && is_dir($file_dir) && is_writable($file_dir))) {
                        \Errors\common_error::RF("target path is not accessible:%s", $file_dir);
                    }
                    \Helpers\Helpers::rm_files_by_regex($file_dir, ["/^{$fnnoext}/i"]);
                    $file_path = $file_dir . $file_name;
                    if (file_exists($file_path) && is_file($file_path) && is_writable($file_path)) {
                        @unlink($file_path);
                    }
                    if (file_exists($file_path) && is_file($file_path) && is_writable($file_path)) {
                        \Errors\common_error::RF("target path is not accessible:%s", $file_dir);
                    }
                    $uploaded_item['file']->move($file_path);
                    $b->push("UPDATE video__group__item SET mime=:P{$b->c}mime,video=:P{$b->c}video WHERE id=:P{$b->c}id AND uid=:P{$b->c}uid;");
                    $b->push_params([
                        ":P{$b->c}mime" => $uploaded_item['file']->type,
                        ":P{$b->c}video" => $file_name,
                        ":P{$b->c}id" => $w->result_id,
                        ":P{$b->c}uid" => $uploaded_item['uid'],
                    ]);
                    $b->inc_counter();
                    $task_ro_shoot[] = ['id' => $w->result_id, 'uid' => $uploaded_item['uid']];
                }
            }
            
        }
        // элемены которым нужна конверсия - перемещаем во времянку
        $safe_temp = tempnam(sys_get_temp_dir(), "VideoUploaderClass");
        if (file_exists($safe_temp) && is_file($safe_temp)) {
            @unlink($safe_temp);
        }
        @mkdir($safe_temp);
        if (!(file_exists($safe_temp) && is_dir($safe_temp) && is_writable($safe_temp) )) {
            \Errors\common_error::RF("cant access temporary path:%s", $safe_temp);
        }
        $task_to_launch = [];
        foreach ($tasks_to_convert as $conv) {
            $file_ne = str_ireplace("-", "", $conv['uid']);
            $file_name = $file_ne . $conv['file']->get_source_extension_dotted();
            $mime = $conv['file']->type;
            $full_path = $safe_temp . DIRECTORY_SEPARATOR . $file_name;
            $conv['file']->move($full_path);
            $params = ['file' => $full_path, 'id' => $w->result_id, 'uid' => $conv['uid'], 'mime' => $mime, 'dir' => $safe_temp];
            // и запускаем таск
            $task_to_launch[] = AsyncTaskConvert::mk_params()->add_array($params);
        }
        //Запуск отдельно - последний таск должен удалить времянку
        foreach ($task_to_launch as $task) {
            $task->run();
        }
        // если нет тасков на конверт, но есть таски на шут
        if (!count($tasks_to_convert) && count($task_ro_shoot)) {
            
        }
        if (!$b->empty) {
            $b->execute();
        }
    }

}
