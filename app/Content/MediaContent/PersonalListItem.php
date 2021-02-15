<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent;

/**
 * Description of PersonalListItem
 *
 * @author eve
 * @property int $person_id
 * @property string $value
 * @property string $name
 * @property string $common_name
 * @property int $sort
 */
class PersonalListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $person_id;

    /** @var string */
    protected $value;

    /** @var string */
    protected $name;

    /** @var string */
    protected $common_name;

    /** @var int */
    protected $sort;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__person_id() {
        return $this->person_id;
    }

    /** @return string */
    protected function __get__value() {
        return $this->value;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    //</editor-fold>


    public function __construct(array $data) {
        $this->import_props($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'person_id' => ['IntMore0'], //int
            'value' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'sort' => ['Int', 'Default0'], //int
        ];
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

}
