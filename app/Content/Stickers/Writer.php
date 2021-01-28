<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Stickers;

/**
 * Description of 
 * @property int $operation_id
 * @author eve
 */
class Writer {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;
    protected $operation_id;
    protected $created = false;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return int */
    protected function __get__operation_id() {
        return $this->operation_id;
    }

    /** @return int */
    protected function __get__id() {
        return $this->operation_id;
    }

    /** @return bool */
    protected function __get__created() {
        return $this->created;
    }

    //</editor-fold>

    /**
     * 
     * @return $this
     */
    public function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = '@a' . md5(__FILE__ . __LINE__);
        if ($data['id']) {
            $b->push("SET {$t} = :P{$b->c}id;");
            $b->push("UPDATE chill__review__sticker SET name=:P{$b->c}name,cdn_id=:P{$b->c}cdn_id,cdn_url=:P{$b->c}cdn_url WHERE id={$t};");
            $b->push_param(":P{$b->c}id", $data['id']);
        } else {
            $b->push("INSERT INTO chill__review__sticker (name,cdn_id,cdn_url) VALUES(:P{$b->c}name,:P{$b->c}cdn_id,:P{$b->c}cdn_url);");
            $b->push("SET {$t} = LAST_INSERT_ID();");
        }

        $b->push_params([
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}cdn_id" => $data["cdn_id"],
            ":P{$b->c}cdn_url" => $data["cdn_url"],
        ]);
        $b->inc_counter();
        $this->operation_id = $b->execute_transact($t);
        StickerItem::reset_cache(); 
       return $this;
    }

    private function __construct(\DataMap\IDataMap $input) {
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

    protected function get_filters() {
        return[
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
