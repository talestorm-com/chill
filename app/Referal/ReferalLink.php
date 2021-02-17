<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Referal;

/**
 * Description of ReferalLink
 *
 * @author eve
 * @property int $referal_id
 * @property bool $valid
 * 
 */
class ReferalLink {

    use \common_accessors\TCommonAccess;

    const FAKE_KEYSET = "014f9d0317b1473498a679c5c4d99c62U9bceac90849b45e19ff119dc8991cc5eb7dbb66138fd4368893c551f2df72af44a1873968ca441d2a366207e9fd1ddc6bca251b1f4aa4d2ead5487140a2c54dafb456c79e14a44e7a21e950d3ec797dabce41f86fcaf4fa7bb04a17e06b71c711bbcb145a0dd4522ae01385808e8ec9b18c8e715f60b43489983b7bd197e74ea";
    const REF_PARAM = "chillvisionrefid";
    const Q_NAME = "chillvision_referal_code";

    protected $referal_id;
    protected static $instance;

    protected function __get__referal_id() {
        return $this->referal_id;
    }

    protected function __get__valid() {
        return $this->referal_id ? true : false;
    }

    protected function __construct() {
        static::$instance = $this;
        //$debug = \DataMap\InputDataMap::F()->get_filtered("debug_referals", ['Boolean', 'DefaultFalse']);
        if (\DataMap\InputDataMap::F()->exists(static::REF_PARAM)) {
            $ref = \DataMap\InputDataMap::F()->get_filtered(static::REF_PARAM, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $m = [];
            if ($ref && preg_match("/^(?P<u>\d{1,})\.(?P<hash>[0-9a-f]{1,})$/i", $ref, $m)) {
                $x = intval($m[u]);
                $hash = trim($m['hash']);
                if (strcasecmp(md5(implode("---", [static::FAKE_KEYSET, $x, static::FAKE_KEYSET])), $hash) === 0) {
                    $this->referal_id = $x;
                    setcookie(static::Q_NAME, $ref, time() + 60 * 60 * 24 * 3000, "/", "", true, true);
                } 
            }
        }
        if (!$this->referal_id) {
            $ref = \DataMap\CookieDataMap::F()->get_filtered(static::Q_NAME, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $m = [];
            if ($ref && preg_match("/^(?P<u>\d{1,})\.(?P<hash>[0-9a-f]{1,})$/i", $ref, $m)) {
                $x = intval($m[u]);
                $hash = trim($m['hash']);
                if (strcasecmp(md5(implode("---", [static::FAKE_KEYSET, $x, static::FAKE_KEYSET])), $hash) === 0) {
                    $this->referal_id = $x;
                    setcookie(static::Q_NAME, $ref, time() + 60 * 60 * 24 * 3000, "/", "", true, true);
                }
            }
        }
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

    /**
     * 
     * @param int $referal_id
     * @return string|null
     */
    public static function mk_referal_link(int $referal_id = null) {
        if (!$referal_id) {
            if (\Auth\Auth::F()->is_authentificated()) {
                $referal_id = \Auth\Auth::F()->get_id();
            }
        }
        if ($referal_id) {
            $key = implode(".", [$referal_id, md5(implode("---", [static::FAKE_KEYSET, $referal_id, static::FAKE_KEYSET]))]);
            return (\Router\Request::F()->https ? "https://" : "http://") . \Router\Request::F()->host . "?" . static::REF_PARAM . "={$key}";
        }
        return null;
    }

}
