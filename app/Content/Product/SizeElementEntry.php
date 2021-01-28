<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Product;

/**
 * @property int $size_id
 * @property int $system_id
 * @property string $value
 * @property bool $valid
 * @property string $key
 */
class SizeElementEntry implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    protected $id;
    protected $sid;
    protected $alter_size;
    protected $key;

    /** @return int */
    protected function __get__size_id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__system_id() {
        return $this->sid;
    }

    /** @return string */
    protected function __get__value() {
        return $this->alter_size;
    }

    protected function __get__valid() {
        return ($this->alter_size && $this->id && $this->sid) ? true : false;
    }

    protected function __get__key() {
        return $this->key;
    }

    public function __construct(array $data) {
        $this->import_props($data);
    }

    protected function t_common_import_get_filters() {
        return[
            'id' => ['IntMore0', 'DefaultNull'],
            'sid' => ['IntMore0', 'DefaultNull'],
            'alter_size' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    protected function t_common_import_after_import() {
        $this->key = "P{$this->sid}";
    }

    /**
     * 
     * @param array $data
     * @return \Content\Product\SizeElementEntry
     */
    public static function F(array $data): SizeElementEntry {
        return new static($data);
    }

}
