<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * Description of UserAdress
 *
 * @author studio2
 * @property string $uid
 * @property string $label
 * @property string $address
 * @property bool $valid
 */
class UserAdress implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    /** @var string */
    protected $uid;

    /** @var string */
    protected $label;

    /** @var string */
    protected $address;

    /** @return string */
    protected function __get__uid() {
        return $this->uid;
    }

    /** @return string */
    protected function __get__label() {
        return $this->label;
    }

    /** @return string */
    protected function __get__address() {
        return $this->address;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->uid && $this->label && $this->address;
    }

    protected function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'label' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'address' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
