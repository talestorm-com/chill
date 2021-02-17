<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ChillFosProcessor;

class Processor {

    /** @var \DataMap\IDataMap */
    protected $input;
    protected $result;

    protected function __construct(\DataMap\IDataMap $input) {
        $this->input = $input;
    }

    /**
     * 
     * @param \DataMap\IDataMap $input
     * @return \static
     */
    public static function F(\DataMap\IDataMap $input) {
        return new static($input);
    }

    public function run() {
        $b = \DB\SQLTools\SQLBuilder::F();
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        \Helpers\Helpers::csrf_check_throw('fos', $data['token'], false);
        $vn = "@a" . md5(__METHOD__);
        $b->push("INSERT INTO media_new_request (
            email,contact,common_name,
            name,year,link,
            ss_qty,series_length,director,
            producer,actor,trailer,
            facebook,vk,instagramm,
            youtube,annotation,festival)
            VALUES(
            :P{$b->c}email,:P{$b->c}contact,:P{$b->c}common_name,
                :P{$b->c}name,:P{$b->c}year,:P{$b->c}link,
                :P{$b->c}ss_qty,:P{$b->c}series_length,:P{$b->c}director,
                :P{$b->c}producer,:P{$b->c}actor,:P{$b->c}trailer,
                :P{$b->c}facebook,:P{$b->c}vk,:P{$b->c}instagramm,
                :P{$b->c}youtube,:P{$b->c}annotation,:P{$b->c}festival);
            SET {$vn} = LAST_INSERT_ID();
            ")->push_params([
            ":P{$b->c}contact" => $data['contact'],
            ":P{$b->c}email" => $data['email'],
            ":P{$b->c}common_name" => $data['common_name'],
            ":P{$b->c}name" => $data['name'],
            ":P{$b->c}year" => $data['year'],
            ":P{$b->c}link" => $data['link'],
            ":P{$b->c}ss_qty" => $data['ss_qty'],
            ":P{$b->c}series_length" => $data['series_length'],
            ":P{$b->c}director" => $data['director'],
            ":P{$b->c}producer" => $data['producer'],
            ":P{$b->c}actor" => $data['actor'],
            ":P{$b->c}trailer" => $data['trailer'],
            ":P{$b->c}facebook" => $data['facebook'],
            ":P{$b->c}vk" => $data['vk'],
            ":P{$b->c}instagramm" => $data['instagramm'],
            ":P{$b->c}youtube" => $data['youtube'],
            ":P{$b->c}annotation" => $data['annotation'],
            ":P{$b->c}festival" => $data['festival'],
        ]);
        $this->result = $b->execute_transact($vn);
        $this->process_images();
        $this->send_message();
        \Helpers\Helpers::csrf_remove($data['token'], 'fos');
    }

    protected function send_message() {
        Task::mk_params()->add("id", $this->result)->run();
    }

    protected function process_images() {
        if (!\ImageFly\MediaContextInfo::F()->context_exists('REQUEST_POSTER')) {
            \ImageFly\MediaContextInfo::register_media_context('REQUEST_POSTER', 1200, 1200, 300, 300);
        }
        if (!\ImageFly\MediaContextInfo::F()->context_exists('REQUEST_FRAME')) {
            \ImageFly\MediaContextInfo::register_media_context('REQUEST_FRAME', 1200, 1200, 300, 300);
        }
        $posters = \DataMap\FileMap::F()->get_by_field_name('posters');
        if (count($posters)) {
            $c = 0;
            foreach ($posters as $poster) {
                try {
                    $c++;
                    \ImageFly\ImageFly::F()->process_upload_manual('REQUEST_POSTER', $this->result, md5("R{$this->id}C{$c}"), $poster);
                } catch (\Throwable $e) {
                    
                }
            }
        }
        $frames = \DataMap\FileMap::F()->get_by_field_name('frames');
        if (count($frames)) {
            $c = 0;
            foreach ($frames as $frame) {
                try {
                    $c++;
                    \ImageFly\ImageFly::F()->process_upload_manual('REQUEST_FRAME', $this->result, md5("R{$this->id}C{$c}"), $frame);
                } catch (\Throwable $e) {
                    
                }
            }
        }
    }

    protected function get_filters() {
        return [
            'contact' => ['Strip', 'Trim', 'NEString'],
            'email' => ['Strip', 'Trim', 'NEString', 'EmailMatch'],
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'year' => ['IntMore0'],
            'link' => ['Strip', 'Trim', 'NEString'],
            'ss_qty' => ['Strip', 'Trim', 'NEString'],
            'series_length' => ['Strip', 'Trim', 'NEString'],
            'director' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'producer' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'actor' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'trailer' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'facebook' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'vk' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'instagramm' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'youtube' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'annotation' => ['Strip', 'Trim', 'NEString'],
            'festival' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'commit' => ['Boolean', 'DefaultFalse'],
            'token' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
