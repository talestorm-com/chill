<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property double $lat
 * @property double $lon
 * @property bool $visible
 * @property int $storage_id
 * @property bool $valid
 * @property string $email
 * @property string $phone
 * @property string $phone_alter
 * @property string $works
 */
class OfflineShop implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props && getters">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $address;

    /** @var double */
    protected $lat;

    /** @var double */
    protected $lon;

    /** @var bool */
    protected $visible;

    /** @var int */
    protected $storage_id;

    /** @var string */
    protected $email;

    /** @var string */
    protected $phone;
    protected $works;

    /** @var string */
    protected $phone_alter;

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
    protected function __get__address() {
        return $this->address;
    }

    /** @return double */
    protected function __get__lat() {
        return $this->lat;
    }

    /** @return double */
    protected function __get__lon() {
        return $this->lon;
    }

    /** @return bool */
    protected function __get__visible() {
        return $this->visible;
    }

    /** @return int */
    protected function __get__storage_id() {
        return $this->storage_id;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->name && $this->storage_id;
    }

    /** @return string */
    protected function __get__email() {
        return $this->email;
    }

    /** @return string */
    protected function __get__phone() {
        return $this->phone;
    }

    protected function __get__works() {
        return $this->works;
    }

    /** @return string */
    protected function __get__phone_alter() {
        return $this->phone_alter;
    }

//</editor-fold>
    //</editor-fold>


    public function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \Basket\OfflineShop
     */
    public static function F(array $data): OfflineShop {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'address' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'visible' => ['Boolean', 'DefaultNull'],
            'storage_id' => ['IntMore0', 'DefaultNull'],
            'lat' => ["Float", "DefaultNull"],
            'lon' => ["Float", "DefaultNull"],
            'email' => ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull'],
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            'phone_alter' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            'works' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
        ];
    }

}
