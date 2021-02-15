<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of CDNEncoderTask
 *
 * @author eve
 */
class CDNEncoderTaskV4 extends \AsyncTask\AsyncTaskAbstract {

    protected $file_id = null;

    protected function get_log_file_name(): string {
        return "encoder_async+v4";
    }

    protected function exec() {
        $this->file_id = $this->params->get_filtered('id', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($this->file_id) {
            $info_request = CDNInfoRequest::F();
            $info_request->run($this->file_id);
            if ($info_request->success) {
                $info = $info_request->result;
                if (is_array($info) && array_key_exists('content_type', $info) && is_string($info['content_type'])) {
                    if (preg_match("/^video.*/i", $info['content_type'])) {
                        $advanced = array_key_exists('advanced', $info) && is_array($info['advanced']) ? $info['advanced'] : null;
                        if ($advanced) {
                            $video_streams = array_key_exists('video_streams', $advanced) && is_array($advanced['video_streams']) ? $advanced['video_streams'] : null;
                            if ($video_streams && count($video_streams) && array_key_exists(0, $video_streams)) {
                                $stream = $video_streams[0];
                                if ($stream && is_array($stream)) {
                                    if (array_key_exists('width', $stream) && array_key_exists('height', $stream) && is_scalar($stream['width']) && is_scalar($stream['height'])) {
                                        $width = intval($stream['width']);
                                        $height = intval($stream['height']);
                                        if ($width && $height) {
                                            $template = $width >= $height ? "5e1ba97c0e47cf2f7dfdbd18" : "5f2a6cf30709f79ac0f4fce9";
                                            $template_2 = $width>=$height?"5e1c6cdaef3db50e0f8efe67":null;
                                            $template_3 = $width>=$height?"5e1c6cdaef3db50e0f8efe68":null;
                                            $mode = $width >= $height ? "horizontal" : "vertical";
                                            $this->log("try_autoencoding:{$this->file_id}({$width}x{$height}):{$template}({$mode}))", 'encoding');
                                            CDNTranscoderRequest::F()->run($this->file_id, $template);
                                            if($template_2){
                                                CDNTranscoderRequest::F()->run($this->file_id, $template_2);
                                            }
                                            if($template_3){
                                                CDNTranscoderRequest::F()->run($this->file_id, $template_3);
                                            }
                                        } else {
                                            $this->log("wrong size:{$stream['width']}x{$stream['height']}", 'error');
                                        }
                                    } else {
                                        $this->log("wrong stream:" . print_r($stream, true), 'error');
                                    }
                                } else {
                                    $this->log("stream is not array:" . print_r($video_streams, true), 'error');
                                }
                            } else {
                                $this->log("videostreams is not valid:" . print_r($advanced, true), 'error');
                            }
                        } else {
                            $this->log("advanced is not valid:" . print_r($info, true), 'error');
                        }
                    } else {
                        $this->log("wrong content_type:{$info['content_type']}", 'error');
                    }
                } else {
                    $this->log("no content_type or bad info:" . print_r($info, true), 'error');
                }
            } else {
                $this->log("request_error", 'error');
            }
        }
    }

}
