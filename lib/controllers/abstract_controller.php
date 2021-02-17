<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers;

/**
 * @property string $controller_id
 * @property string $controller_class
 * @property string $layout
 * @property string $MC
 * @property \DataMap\GPDataMap $GP
 * @property \Auth\IAuth  $auth
 * @property bool $is_device
 * @property bool $is_tablet
 * @property bool $is_phone
 */
abstract class abstract_controller implements IViewable {

    use \common_accessors\TCommonAccess;

    const DEBUG_MODE = false;

    /** @var \Out\IOut */
    protected $out;

    /** @var \DataMap\GPDataMap */
    protected $GP;

    /** @var \Filters\FilterManager */
    protected $FM;

    /** @var \Auth\Auth */
    protected $auth;
    protected $_MC;

    /** @return bool */
    protected function __get__is_device() {
        $detetctor = new \Router\MobileDetect();
        return $detetctor->isMobile() || $detetctor->isTablet();
    }

    /** @return bool */
    protected function __get__is_tablet() {
        $detetctor = new \Router\MobileDetect();
        return $detetctor->isTablet();
    }

    /** @return bool */
    protected function __get__is_phone() {
        $detetctor = new \Router\MobileDetect();
        return $detetctor->isMobile() && !$detetctor->isTablet();
    }

    protected function get_requested_layout(string $default) {
        /** @var $this->GP \DataMap\AbstractDataMap */
        return $this->GP->filtered_with_default("sys_render_layout", ['Strip', 'Trim', 'NEString'], $default);
    }

    protected function get_requested_template(string $default) {
        /** @var $this->GP \DataMap\AbstractDataMap */
        return $this->GP->filtered_with_default("sys_render_template", ['Strip', 'Trim', 'NEString'], $default);
    }

    protected function __get__MC() {
        if (!$this->_MC) {
            $CPath = array_slice(explode("\\", get_called_class()), 1);
            for ($i = 0; $i < count($CPath); $i++) {
                $CPath[$i] = ucfirst(mb_strtolower($CPath[$i], 'UTF-8'));
            }
            $this->_MC = implode("", $CPath);
        }
        return $this->_MC;
    }

    protected function __get__auth() {
        return $this->auth;
    }

    protected function __get__GP() {
        return $this->GP;
    }

    /*
     * 
     * экшен контроллера - кладет все в out
     * контролер после передает рендереру, если нужно
     *  
     */

    protected function __construct() {
        $this->set_defaults();
        $this->do_init();
        $this->on_after_init();
    }

    protected function set_defaults() {
        $this->out = \Out\Out::F();
        $this->GP = \DataMap\GPDataMap::F();
        $this->FM = \Filters\FilterManager::F();
        $this->auth = \Auth\Auth::F();
        if (!$this->check_access()) {

            if (!\Router\Request::F()->ajax) {//ajax запросы  - внутренняя проверка в API
                \Router\Router::F()->redirect_to_login();
            }
        }
    }

    protected function check_access() {
        return true;
    }

    protected function do_init() {
        
    }

    protected function on_after_init() {
        
    }

    public static function CONTROLLER_HAS_METHOD(string $method_name): bool {
        return method_exists(get_called_class(), "action{$method_name}");
    }

    public function run_method($method_name) {
        $call_method_name = "action{$method_name}";
        $this->on_before_method($method_name, $call_method_name);
        $this->$call_method_name();
        $this->on_after_method();
    }

    /**
     * override to filter and replace result method name
     * @param type $requested_action
     * @param type $call_method_name
     */
    protected function on_before_method($requested_action, &$call_method_name) {
        
    }

    protected function on_after_method() {
        
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

    protected function check_api_access(): bool {
        return true;
    }

    protected function on_before_api_action(string $action_method, string $action) {
        
    }

    protected function actionAPI() {
        $this->out->add('status', 'ok');
        try {
            if (!$this->check_access()) {
                \Auth\AuthError::R(\Auth\AuthError::ACCESS_DENIED);
            }
            if (!$this->check_api_access()) {
                \Auth\AuthError::R(\Auth\AuthError::ACCESS_DENIED);
            }
            $this->out->add('controller', get_called_class());
            $action = \DataMap\InputDataMap::F()->get_filtered('action', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $call_action = $action ? "API_{$action}" : "API_default";
            if (!method_exists($this, $call_action)) {
                \Errors\common_error::RF("no API action `%s` found in `%s`", $call_action, get_called_class());
            }
            $this->on_before_api_action($call_action, $action);
            $this->$call_action();
        } catch (\Throwable $e) {
            $this->out->add('status', 'error');
            $this->out->replace_section('error_info', [
                'message' => $e->getMessage(),
                'place' => (static::DEBUG_MODE)?sprintf("%s at line %s", $e->getFile(), $e->getLine()):null,
                'trace' => (static::DEBUG_MODE)?$e->getTraceAsString():null,
            ]);
        }
        $this->out_json($this->out->marshall());
    }

    protected function out_json(array $out) {
        if (\DataMap\InputDataMap::F()->get_filtered('require-jsonp-responce', ["Boolean", "DefaultFalse"])) {
            $this->out_jsonp($out);
            die();
        }
        if (!headers_sent()) {
            header("Content-Type: application/json");
        }
        $this->clear_content_buffer();
        die(json_encode($out));
    }

    protected function out_jsonp(array $out) {
        if (!headers_sent()) {
            header("Content-Type: text/html");
        }
        $layout_path = \Config\Config::F()->HTML_LAYOUT_DIR . "jsonp_html.tpl";
        (file_exists($layout_path) && is_file($layout_path) && is_readable($layout_path)) ? false : \Errors\common_error::RF("no layout found `%s`", $layout_path);
        $this->clear_content_buffer();
        \smarty\SMW::F()->smarty->assign("output_json", json_encode($out));
        \smarty\SMW::F()->smarty->assign("jsonp_callback", \Helpers\Helpers::NEString(\DataMap\InputDataMap::F()->get_filtered("jsonp-callback", ["NEString", "DefaultNull"]), 'jsonp_callback'));
        \smarty\SMW::F()->smarty->display($layout_path, "layout" . md5(get_called_class()));
        die();
    }

    protected function clear_content_buffer() {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    public function get_view_path(): string {
        $controller_path = trim(str_ireplace(["\\", "/"], DIRECTORY_SEPARATOR, static::class), "\\/");
        $common_view_path = \Config\Config::F()->VIEW_PATH;
        //var_dump($common_view_path);
        return $common_view_path . $controller_path . DIRECTORY_SEPARATOR;
    }

    public function get_frontend_templates($folder = 'front_templates') {
        $view_path = $this->get_view_path() . $folder . DIRECTORY_SEPARATOR;
        $result = ['_d' => ''];
        if (file_exists($view_path) && is_dir($view_path) && is_readable($view_path)) {
            $list = scandir($view_path);
            foreach ($list as $file) {
                if (mb_substr($file, 0, 1, 'UTF-8') !== '.') {
                    if (file_exists($view_path . $file) && is_file($view_path . $file)) {
                        $m = [];
                        if (preg_match("/^(?P<n>.*)\.html$/i", $file, $m)) {
                            $result[$m['n']] = file_get_contents($view_path . $file);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function render_partial(string $view_name, bool $return = false) {
        \smarty\SMW::F()->smarty->assign("OUT", $this->out);
        \smarty\SMW::F()->smarty->assign("controller", $this);
        $view_path = $this->get_view_path() . $view_name . ".tpl";
        (file_exists($view_path) && is_file($view_path) && is_readable($view_path)) ? false : \Errors\common_error::RF("no view found `%s`", $view_path);
        if ($return) {
            return \smarty\SMW::F()->smarty->fetch($view_path, md5(get_called_class()));
        }
        \smarty\SMW::F()->smarty->display($view_path, md5(get_called_class()));
    }

    public function render_view(string $layout_name, string $view_name) {
        $this->out->add('page_content', $this->render_partial($view_name, true));
        $layout_path = \Config\Config::F()->HTML_LAYOUT_DIR . "{$layout_name}.tpl";
        (file_exists($layout_path) && is_file($layout_path) && is_readable($layout_path)) ? false : \Errors\common_error::RF("no layout found `%s`", $layout_path);
        $this->clear_content_buffer();
        \smarty\SMW::F()->smarty->display($layout_path, "layout" . md5(get_called_class()));
        die();
    }

    public function get_current_year() {
        $d = new \DateTime();
        return $d->format('Y');
    }

    public function common_templtes($template_name) {
        return \Config\Config::F()->VIEW_PATH . "_common_views" . DIRECTORY_SEPARATOR . "{$template_name}.tpl";
    }

    public static function get_default_action() {
        return null;
    }

    public function get_preference(string $pref_name, $default = null) {
        $result = \PresetManager\PresetManager::F()->get_filtered($pref_name, ['Trim', 'NEString']);
        return \Filters\Value::is($result) ? $default : $result;
    }

    public function current_url() {
        return implode("", [\Router\Request::F()->https ? "https://" : "http://", \Router\Request::F()->host, \Router\Request::F()->request_path]);
    }

    public function get_http_code_string(int $code) {
        return HttpCodeVoc::get($code);
    }

}
