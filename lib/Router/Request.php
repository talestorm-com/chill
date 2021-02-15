<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Router;

/**
 * main request properties
 * 
 * @property string $request_path
 * @property string $request_mode
 * @property bool $https
 * @property string $request_protocol
 * @property string $host
 * @property bool $ignore_cache
 * @property bool $ajax
 * @property bool $is_mobile
 * @property bool $is_tablet
 * @property string $user_agent
 * 
 */
class Request {

    use \common_accessors\TCommonAccess;

    const REQUEST_MODE_HTML = 'HTML';
    const REQUEST_MODE_JSON = 'JSON';
    const REQUEST_MODE_XML = 'XML';
    const REQUEST_MODE_RAW = 'RAW';
    const REQUEST_MODE_DEFAULT = Request::REQUEST_MODE_HTML;

    //<editor-fold defaultstate="collapsed" desc="props && getters">
    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $request_path;

    /** @var string */
    protected $request_mode;

    /** @var bool */
    protected $https;

    /** @var string */
    protected $host;

    /** @var bool */
    protected $ignore_cache;

    /** @var bool */
    protected $ajax;

    /** @var bool */
    protected $is_mobile;

    /** @var bool */
    protected $is_tablet;

    /** @var string */
    protected $user_agent;

    /** @var string */
    protected $request_protocol;
    protected $full_query_string;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__request_path() {
        return $this->request_path;
    }

    /** @return string */
    protected function __get__request_mode() {
        return $this->request_mode;
    }

    /** @return bool */
    protected function __get__https() {
        return $this->https;
    }

    /** @return string */
    protected function __get__host() {
        return $this->host;
    }

    /** @return bool */
    protected function __get__ignore_cache() {
        return $this->ignore_cache;
    }

    /** @return bool */
    protected function __get__ajax() {
        return $this->ajax;
    }

    /** @return bool */
    protected function __get__is_mobile() {
        return $this->is_mobile;
    }

    /** @return bool */
    protected function __get__is_tablet() {
        return $this->is_tablet;
    }

    /** @return string */
    protected function __get__user_agent() {
        return $this->user_agent;
    }

    protected function __get__request_protocol() {
        return $this->request_protocol;
    }

    protected function __get__full_query_string() {
        return $this->full_query_string;
    }

    //</editor-fold>
    //</editor-fold>

    /** @var Request */
    protected static $instance = null;

    protected function __construct() {
        static::$instance = $this;
        $this->user_agent = \Helpers\Helpers::NEString(\DataMap\HeaderDataMap::F()->get("user-agent"), "common_user_agent");
        $this->request_path = $this->get_request_route();        
        $this->https = $this->check_sequre_protocol();
        $this->request_protocol = $this->https ? "https" : "http";
        $this->ignore_cache = (\Filters\FilterManager::F()->apply_chain(\DataMap\GPDataMap::F()->get('_debug_cache_mode'), ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']) === 'ignore');
        $m = new MobileDetect();
        $this->is_mobile = $m->isMobile();
        $this->is_tablet = $m->isTablet();
        unset($m);
        $this->ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH'], 'UTF-8') == 'xmlhttprequest');
        $this->host = \Helpers\Helpers::NEString(trim(\Helpers\Helpers::NEString(\DataMap\HeaderDataMap::F()->get('host'), ''), '\\/'), null);
        $this->get_request_mode();
    }

    protected function get_request_mode() {
        // как нибудь поизящнее нао бы
        $rq = \Helpers\Helpers::NEString(\DataMap\GPDataMap::F()->get_filtered("_request_mode", ['Strip', 'Trim', 'NEString', 'Uppercase', 'DefaultNull']), static::REQUEST_MODE_DEFAULT);
        if (static::REQUEST_MODE_HTML === $rq) {
            $this->request_mode = $rq;
        } else if (static::REQUEST_MODE_JSON === $rq) {
            $this->request_mode = $rq;
        } else if (static::REQUEST_MODE_RAW === $rq) {
            $this->request_mode = $rq;
        } else if (static::REQUEST_MODE_XML === $rq) {
            $this->request_mode = $rq;
        } else {
            $this->request_mode = static::REQUEST_MODE_DEFAULT;
        }
    }

    protected function check_sequre_protocol() {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            return true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        }
        return false;
    }

    protected function get_request_route() {
        $x = explode("?", trim($_SERVER['REQUEST_URI'], "\\/"));
        $x = count($x) ? $x[0] : "";
        return "/{$x}";
    }

    /**
     * 
     * @return \Router\Request
     */
    public static function F(): Request {
        return static::$instance ? static::$instance : new static();
    }

}
