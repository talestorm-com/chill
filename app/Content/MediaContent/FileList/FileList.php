<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\FileList;

/**
 * Description of FileList
 *
 * @author eve
 * @property FileListItem[] $items
 */
abstract class FileList implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    /** @var FileListItem[] */
    protected $items;

    protected function __get__items() {
        return $this->items;
    }

    protected function __construct(int $id = null) {
        $this->items = [];
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $p
     * @return $this
     */
    public function load(int $p) {
        $query = "SELECT id content_id,cdn_id,enabled,content_type,size,info,selector,sort FROM `{$this->get_file_list_table()}` WHERE id=:P ";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $p]);
        foreach ($rows as $raw_row) {
            try {
                $this->items[] = FileListItem::F($raw_row);
            } catch (\Throwable $x) {
                
            }
        }
        return $this;
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    protected abstract function get_file_list_table(): string;
}
