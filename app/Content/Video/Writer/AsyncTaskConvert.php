<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of AsyncTaskConvert
 *
 * @author eve
 */
class AsyncTaskConvert extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
       return 'uploader_video';
    }

    //put your code here
    protected function exec() {
        $file = $this->params->get_filtered("file", ['Trim', 'NEString', 'DefaultNull']);
        $temp_dir = $this->params->get_filtered("dir", ['Trim', 'NEString', 'DefaultNull']);
        $target_dir = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $this->params->get('id');
        $target_name_basis = str_ireplace("-", "", $this->params->get('uid'));
        $target_name = "{$target_name_basis}.webm";
        $target = $target_dir . DIRECTORY_SEPARATOR . $target_name;
        try {
            //$params = ['file' => $full_path, 'id' => $w->result_id, 'uid' => $conv['uid'], 'mime' => $mime,'dir'=>$x];
            try {
                if ($file && file_exists($file)) {
                    $target_dir = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $this->params->get('id');
                    if (!(file_exists($target_dir) && is_dir($target_dir) && is_writeable($target_dir))) {
                        @mkdir($target_dir, 0777, true);
                    }
                    if (!(file_exists($target_dir) && is_dir($target_dir) && is_writeable($target_dir))) {
                        \Errors\common_error::RF("cant create target dir %s", $target_dir);
                    }
                    $target_name_basis = str_ireplace("-", "", $this->params->get('uid'));
                    $target_name = "{$target_name_basis}.mp4";
                    $target = $target_dir . DIRECTORY_SEPARATOR . $target_name;
                    \Helpers\Helpers::rm_files_by_regex($target_dir, ["/^{$target_name_basis}/i"]);
                    if (file_exists($target) && is_file($target) && is_writeable($target)) {
                        @unlink($target);
                    }
                    //ffmpeg -i example.mp4 -f webm -c:v libvpx -b:v 1M -acodec libvorbis example.webm -hide_banner
                    //ffmpeg -i input.mp4 -c:v libvpx-vp9 -crf 30 -b:v 0 -b:a 128k -c:a libopus output.webm
                    $qual = 30;
                    $cmd = "ffmpeg -i {$file} -c:v libvpx-vp9 -crf {$qual} -b:v 0 -b:a 64k -c:a libopus {$target} -hide_banner";
                    $cmd="ffmpeg -i {$file} -c:v h264  -b:a 64k -c:a aac {$target} -hide_banner";
                    $this->log($cmd,'command');
                    ob_start();
                    $rv = 0;
                    passthru($cmd, $rv);
                    $output = ob_get_clean();
                    $rv === 0 ? 0 : \Errors\common_error::R($output);
                    $this->log("$output",'info');
                    \DB\SQLTools\SQLBuilder::F()->push("UPDATE video__group__item SET video=:Pn, mime=:Pm WHERE id=:Pi AND uid=:Pu;")
                            ->push_param(":Pn", $target_name)
                            ->push_param(":Pm", "video/mp4")
                            ->push_param(":Pi", $this->params->get("id"))
                            ->push_param(":Pu", $this->params->get('uid'))->execute();
                } else {
                    \Errors\common_error::RF("input file not found:`%s`", $file);
                }
            } catch (\Throwable $se) {
                \DB\SQLTools\SQLBuilder::F()->push("UPDATE video__group__item SET video='error' WHERE id=:P AND uid=:PP")
                        ->push_param(":P", $this->params->get('id'))
                        ->push_param(":PP", $this->params->get('uid'))->execute();
                throw $se;
            }
        } catch (\Throwable $e) {
            $this->log($e->getMessage(), 'error');
            if ($target && file_exists($target) && is_file($target) && is_writeable($target)) {
                @unlink($target);
            }
        }
        if ($file && file_exists($file) && is_file($file) && is_writeable($file)) {
            @unlink($file);
        }
        if ($temp_dir && file_exists($temp_dir) && is_dir($temp_dir) && is_writable($temp_dir)) {
            if ($this->dir_empty($temp_dir)) {
                @rmdir($temp_dir);
            }
        }
    }

    protected function dir_empty(string $dir_path): bool {
        $dirs = 0;
        $files = 0;
        $check_path = rtrim($dir_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $list = scandir($check_path);
        foreach ($list as $file_name) {
            if ($file_name !== '..' && $file_name !== '.') {
                if (is_file($check_path . $file_name)) {
                    $files++;
                } else if (is_link($check_path . $file_name)) {
                    $files++;
                } else if (is_dir($check_path . $file_name)) {
                    $dirs++;
                }
            }
        }
        return $dirs === 0 && $files === 0;
    }

}
