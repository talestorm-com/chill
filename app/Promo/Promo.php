<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Promo;

/**
 * Description of Promo
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $code
 * @property float $value
 * @property string $valid
 */
class Promo implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;


    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $code;

    /** @var float */
    protected $value;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__code() {
        return $this->code;
    }

    /** @return float */
    protected function __get__value() {
        return $this->value;
    }

    protected function __get__valid() {
        return $this->id && $this->code;
    }

    //</editor-fold>


    protected function __construct(int $id = null, string $code = null) {
        if ($id) {
            $this->load_by_id($id);
        } else if ($code) {
            $this->load_by_code($code);
        }
    }

    protected function load_by_id(int $id) {
        return $this->load_by_attribute('id', $id);
    }

    protected function load_by_code(string $code) {
        return $this->load_by_attribute('code', $code);
    }

    protected function load_by_attribute(string $attribute, $value) {
        $query = "SELECT * FROM chill__promo WHERE `{$attribute}`=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $value]);
        $row ? 0 : \Errors\common_error::R('not found');
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'code' => ['Strip', 'Trim', 'NEString'], //string
            'value' => ['Float'], //float
        ];
    }

    protected function t_common_import_get_filter_params(): array {
        return[
            'value' => [
                'Float' => ['min' => 1.0],
            ],
        ];
    }

    /**
     * 
     * @param int $id
     * @param string $code
     * @return \static
     */
    public static function F(int $id = null, string $code = null) {
        return new static($id, $code);
    }

}
