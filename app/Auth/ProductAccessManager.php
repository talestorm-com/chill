<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * Description of ProductAccessibleManager
 *
 * @author eve
 * @property string $class_version
 * @property int $user_id
 * @property string[] $items
 */
class ProductAccessManager {

    use \common_accessors\TCommonAccess;

    protected static $_cv = null;

    /** @var string */
    protected $class_version;

    /** @var int */
    protected $user_id;

    /** @var string[] */
    protected $items;

    /** @return string */
    protected function __get__class_version() {
        return $this->class_version;
    }

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return string[] */
    protected function __get__items() {
        return $this->items;
    }

    protected function __construct(int $user_id) {
        $this->user_id = $user_id;
        $this->class_version = static::get_class_ver();
        $this->items = [];
        $this->reload_and_store();
    }

    protected function reload_and_store() {
        if ($this->user_id && $this->user_id > 0) {
            $this->load();
            $this->store();
        }
    }

    /**
     * 
     * @return $this
     */
    protected function load() {
        $query = "SELECT CONCAT(t,i) `key` FROM user__access WHERE user_id=:P";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $this->user_id]);
        $this->items = [];
        foreach ($rows as $row) {
            $this->items[$row['key']] = $row['key'];
        }
        return $this;
    }

    /**
     * 
     * @return $this
     */
    protected function store() {
        \DataMap\MCDataMap::F()->set(sprintf("%s_%s", __CLASS__, $this->user_id), serialize($this));
        return $this;
    }

    public static function get_class_ver(): string {
        if (!static::$_cv) {
            static::$_cv = md5(implode(".", [__FILE__, filemtime(__FILE__)]));
        }
        return static::$_cv;
    }

    public static function C(int $user_id): ProductAccessManager {
        if ($user_id && $user_id > 0) {
            $item = unserialize(\DataMap\MCDataMap::F()->get(sprintf("%s_%s", __CLASS__, $user_id)));
            $cs = static::class; /* @var $item static */
            if ($item && is_object($item) && ($item instanceof $cs) && $item->class_version === static::get_class_ver()) {
                return $item;
            }
        }
        return new static($user_id);
    }

    public function regiser_access(string $content_type, string $content_id) {
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO user__access (user_id,t,i) VALUES(:P1,:P2,:P3) ON DUPLICATE KEY UPDATE user_id=VALUES(user_id),t=VALUES(t),i=VALUES(i);")
                ->push_params([":P1" => $this->user_id, ":P2" => $content_type, ":P3" => $content_id])->execute();
        $this->reload_and_store();
    }

    public function has_access(string $content_type, string $content_id): bool {
        return array_key_exists("{$content_type}{$content_id}", $this->items) ? true : false;
    }

}
