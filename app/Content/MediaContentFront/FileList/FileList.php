<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\FileList;

/**
 * @property int $id
 * @property FileListItem[] $items
 */
class FileList implements \common_accessors\IMarshall, \Iterator, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var int */
    protected $id;

    /** @var FileListItem[] */
    protected $items;

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return FileListItem[] */
    protected function __get__items() {
        return $this->items;
    }

    public function __construct() {
        $this->items = [];
    }

    public function load(int $id) {
        $query = "SELECT id,cdn_id,content_type,size,info,sort,selector FROM `media__content__cdn__file` WHERE id=:P AND enabled=1 ORDER BY sort;";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $id]);
        foreach ($rows as $raw_row) {
            try {
                $this->items[] = FileListItem::F($raw_row);
            } catch (\Throwable $x) {
                
            }
        }
        return $this;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }
    
    /**
     * 
     * @return \static
     */
    public static function F(){
        return new static();
    }

}
