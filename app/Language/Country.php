<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of Country
 *
 * @author eve
 * @property int $id
 * @property string $common_name
 * @property string[] $name
 */
class Country implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    /** @var int */
    protected $id;

    /** @var string */
    protected $common_name;

    /** @var string[] */
    protected $name;

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return string[] */
    protected function __get__name() {
        return $this->name;
    }

    /** @return boolean */
    protected function __get__valid() {
        return $this->common_name && $this->id;
    }

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load(int $id) {
        $row = \DB\DB::F()->queryRow("SELECT * FROM media__content__origin_country WHERE id=:P;", [":P" => $id]);
        $this->import_props(is_array($row) ? $row : []);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'common_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['NEArray', 'DefaultEmptyArray'],
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->__get__valid()) {
            $rows = \DB\DB::F()->queryAll("SELECT language_id,name FROM media__content__origin__country__strings WHERE id=:P", [":P" => $this->id]);
            foreach ($rows as $row) {
                $this->name[$row["language_id"]] = $row["name"];
            }
        }
    }

}
