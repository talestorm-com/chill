<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ChillComment;

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
            $b->push("UPDATE chill__review SET enabled=:P{$b->c}enabled,author=:P{$b->c}author,sticker=:P{$b->c}sticker,
                content=:P{$b->c}content,r=:P{$b->c}r
                WHERE id={$t};");
            $b->push_param(":P{$b->c}id", $data['id']);
        } else {
            $b->push("INSERT INTO chill__review (enabled,author,datum,sticker,content,r) VALUES(:P{$b->c}enabled,:P{$b->c}author,NOW(),:P{$b->c}sticker,:P{$b->c}content,:P{$b->c}r);");
            $b->push("SET {$t} = LAST_INSERT_ID();");
        }

        $b->push_params([
            ":P{$b->c}enabled" => $data["enabled"] ? 1 : 0,
            ":P{$b->c}author" => $data["author"],
            ":P{$b->c}sticker" => $data["sticker"],
            ":P{$b->c}content" => $data["content"],
            ":P{$b->c}r" => $data["r"],
        ]);
        $b->inc_counter();
        $b->push("INSERT INTO chill__review__rating (id,rating) VALUES ({$t},:P{$b->c}rate) ON DUPLICATE KEY UPDATE rating=VALUES(rating); ")
                ->push_param(":P{$b->c}rate", $data['rating']);
        $this->operation_id = $b->execute_transact($t);
        ChillCommentItem::reset_cache();
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
            'author' => ['Strip', 'Trim', 'NEString'],
            'enabled' => ['Boolean', 'DefaultFalse',],
            'content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'r' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'sticker' => ['IntMore0', 'DefaultNull',],
            'rating' => ['Int', 'Default0'],
        ];
    }

}
