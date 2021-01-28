<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage;

/**
 * Description of ClientPackage
 *
 * @author eve
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $default_image
 * @property string $usages_remain
 * @property \DateTime $expires
 * @property bool $valid
 * @property string $format_expiration
 */
class ClientPackage implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $client_id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $default_image;

    /** @var string */
    protected $usages_remain;

    /** @var string */
    protected $expires;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__client_id() {
        return $this->client_id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__default_image() {
        return $this->default_image;
    }

    /** @return string */
    protected function __get__usages_remain() {
        return $this->usages_remain;
    }

    /** @return \DateTime */
    protected function __get__expires() {
        return $this->expires;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->usages_remain > 0 && $this->expires && $this->expires->getTimestamp() > time();
    }

    /** @return string */
    protected function __get__format_expiration() {
        return $this->expires ? $this->expires->format('d.m.Y') : null;
    }

    //</editor-fold>

    public function __construct(int $id) {
        $this->load($id);
    }

    public function load(int $id) {
        $query = "
            SELECT X.package_id id,X.user_id client_id,X.package_name name,
            P.default_image,U.expires,U.usage_count usages_remain
            FROM
            (SELECT user_id,package_id,package_name FROM fitness__user__order WHERE user_id=:P ORDER BY id DESC LIMIT 1)X
            LEFT JOIN fitness__package P ON(P.id=X.package_id)
            LEFT JOIN user__usages U ON(U.user_id=X.user_id)            
            ";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $this->import_props(is_array($row) ? $row : []);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'],
            'client_id' => ['IntMore0'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'expires' => ['DateMatch', 'DefaultNull'],
            'usages_remain' => ['IntMore0', 'Default0'],
        ];
    }

    /**
     * 
     * @param int $id
     * @return \static|null
     */
    public static function F(int $id) {
        try {
            $r = new static($id);
            $r->valid ? 0 : \Errors\common_error::R("invpkg");
            return $r;
        } catch (\Throwable $e) {
            
        }
        return null;
    }

}
