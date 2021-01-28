<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

/**
 * Description of ProductAccessMonitor
 *
 * @author eve
 */
class ProductAccessMonitor {

    private static $instance;

    /** @var ProductAccessManager */
    private $product_access;

    private function __construct() {
        static::$instance = $this;
    }

    public static function F(): ProductAccessMonitor {
        return static::$instance ? static::$instance : new static();
    }

    public function has_access(string $content_type, string $content_id): bool {
        $auth = Auth::F()->is_authentificated() ? Auth::F() : null;
        if ($auth) {/* @var $auth Auth */
            if ($auth->is(Roles\RoleAdmin::class)) {
                return true;
            }
            if (!$this->product_access || $this->product_access->user_id !== $auth->id) {
                $this->product_access = ProductAccessManager::C($auth->id);
            }
            return $this->product_access->has_access($content_type, $content_id);
        }
        return false;
    }

    public function has_access_to_preset(string $id) {
        if (Auth::F()->is_authentificated()) {
            $ui = Auth::F()->get_user_info();
            if ($ui->subscribed) {
                return true;
            }
        }
        return $this->has_access("P", $id);
    }
    
    public function has_access_to_tutorial(string $id){        
        return $this->has_access("T", $id);
    }

}
