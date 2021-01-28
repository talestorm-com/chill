<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MediaVendor;

/**
 * Description of MediaVendorWriter
 *
 * @author eve
 * @property \DataMap\IDataMap $input
 * @property int $result_id
 */
class MediaVendorWriter {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var int */
    protected $result_id;

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return int */
    protected function __get__result_id() {
        return $this->result_id;
    }

    protected function __construct(\DataMap\IDataMap $input) {
        $this->input = $input;
    }

    /**
     * 
     * @return $this
     */
    public function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = "@a" . md5(__METHOD__);
        if ($data['id']) {
            $b->push("SET {$t} = :P{$b->c}id;");
            $b->push("UPDATE media__studio SET common_name=:P{$b->c}common_name,image=:P{$b->c}image WHERE id={$t};");
            $b->push_param(":P{$b->c}id", $data["id"]);
        } else {
            $b->push("INSERT INTO media__studio (common_name,image) VALUES(:P{$b->c}common_name,:P{$b->c}image);
                SET {$t} = LAST_INSERT_ID();
            ");
        }
        $b->push_params([
            ":P{$b->c}common_name" => $data["common_name"],
            ":P{$b->c}image" => $data["image"],
        ]);
        $b->inc_counter();
        $lang = \Language\LanguageList::F()->get_current_language();
        $b->push(sprintf("INSERT INTO media__studio__strings__lang_%s (id,name,html_mode,intro,info) VALUES({$t},:P{$b->c}name,:P{$b->c}html_mode,:P{$b->c}intro,:P{$b->c}info)
            ON DUPLICATE KEY UPDATE name=VALUES(name),html_mode=VALUES(html_mode),intro=VALUES(intro),info=VALUES(info);
        ", $lang));
        $b->push_params([
            ":P{$b->c}name" => $data['name'],
            ":P{$b->c}html_mode" => $data['html_mode'],
            ":P{$b->c}intro" => $data['intro'],
            ":P{$b->c}info" => $data['info'],
        ]);
        $b->inc_counter();
        $props = properties::F();
        $props->load_from_object_array($data['properties']);
        $props->save($b, $t);

        $this->result_id = $b->execute_transact($t);
        MediaVendor::reset_cached();
        return $this;
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'html_mode' => ['IntMore0', 'Default0'],
            'properties' => ['NEArray', 'DefaultEmptyArray'],
        ];
    }

    /**
     * 
     * @param \DataMap\IDataMap $in
     * @return \static
     */
    public static function F(\DataMap\IDataMap $in) {
        return new static($in);
    }

}
