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
 * @property bool $enabled
 * @property bool $valid
 * @property string $email
 * @property string $phone
 * @property string $works
 * @property string $town
 * @property string $town_key
 * @property string $phone_alter
 */
class PartnerShop implements \common_accessors\IMarshall {

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
    protected $enabled;

    /** @var string */
    protected $email;

    /** @var string */
    protected $phone;
    protected $works;
    protected $town;

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
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->name;
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

    protected function __get__town() {
        return $this->town;
    }

    protected function __get__town_key() {
        return trim(mb_strtolower($this->town, 'UTF-8'));
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
     * @return \Basket\PartnerShop
     */
    public static function F(array $data): PartnerShop {
        return new static($data);
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'address' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'enabled' => ['Boolean', 'DefaultNull'],
            'lat' => ["Float", "DefaultNull"],
            'lon' => ["Float", "DefaultNull"],
            'email' => ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull'],
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            'phone_alter' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'],
            'works' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'town' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    protected function t_default_marshaller_on_marshall_done(array &$result) {
        $result['town_key'] = $this->__get__town_key();
    }

}
