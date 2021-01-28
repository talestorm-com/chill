<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Router;

/**
 * @property string $namespace
 * @property string $controller
 * @property string $action
 * @property string $controller_class
 * @property string $controller_namespace
 * @property IRoute $route
 */
class Router {

    use \common_accessors\TCommonAccess;
    /*
     * нужны статические маршруты, маршруты модулей (по регуляркам) и натуральные маршруты
     * 
     */

    /** @var Router */
    private static $instance;

    /** @var RouteManager */
    private $route_manager;

    /** @var IRoute */
    private $route;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__namespace() {
        return $this->route ? $this->route->get_namespace() : null;
    }

    /** @return string */
    protected function __get__controller() {
        return $this->route ? $this->route->get_controller() : null;
    }

    /** @return string */
    protected function __get__action() {
        return $this->route ? $this->route->get_action() : null;
    }

    /** @return string */
    protected function __get__controller_class() {
        return $this->route ? $this->route->get_controller_class() : null;
    }

    /** @return string */
    protected function __get__controller_namespace() {
        return $this->route ? $this->route->get_controller_namespace() : null;
    }

    protected function __get__route() {
        return $this->route;
    }

    //</editor-fold>


    private function __construct() {
        static::$instance = $this;
        $this->route_manager = RouteManager::F();
    }

    public function run() {
        $debug = \DataMap\GPDataMap::F()->get_filtered('debug_route', ['Boolean', "DefaultFalse"]);
        $route = null;
        try {
            // для начала статический роутинг
            $url = Request::F()->request_path;
            $route = $this->route_manager->get_static_route($url);
            if ($debug) {
                var_dump($route);
            }
            if (!($route && $route->get_route_is_valid())) {
                $route = $this->route_manager->get_natural_route($url);
                if ($debug) {
                    var_dump($route);
                }
            }
            if (!($route && $route->get_route_is_valid())) {
                $this->try_redirect();
                $route = $this->route_manager->get_special_route_for('404');
                if ($debug) {
                    var_dump($route);
                }
            }
            if ($debug) {
                var_dump(Request::F()->request_path);
            }
            if (!($route && $route->get_route_is_valid())) {
                RouterError::RF('no route for `%s`', $url);
            }
        } catch (\Throwable $err) {
            $route = $this->route_manager->get_special_route_for('500');
            if ($debug) {
                var_dump($route);
                die();
            }

            if ($route && $route->get_route_is_valid()) {
                $this->route = $route;
                $this->route->get_params()->set('error', $err);
                $this->run_controller_method_by_route($route);
            } else {
                throw $err;
            }
        }
        $this->route = $route;

        try {
            if ($debug) {
                var_dump($this->route);
                try {
                    $this->run_controller_method_by_route();
                } catch (\Throwable $e) {
                    var_dump($e);
                    die();
                }
            }

            $this->run_controller_method_by_route();
        } catch (NotFoundError $err) {
            $route = $this->route_manager->get_special_route_for('404');
            if ($route && $route->get_route_is_valid()) {
                $route->get_params()->set('error', $err);
                $this->route = $route;
                $this->run_controller_method_by_route($route);
            } else {
                throw $err;
            }
        } catch (RenderableCodeError $error) {
            $route = $this->route_manager->get_special_route_for("{$error->get_http_code()}");            
            if ($route && $route->get_route_is_valid()) {
                $this->route = $route;
                $this->route->get_params()->set('error', $error);
                $this->run_controller_method_by_route($route);
            } else {
                throw $err;
            }
        } catch (\Throwable $err) {
            $route = $this->route_manager->get_special_route_for('500');
            if ($route && $route->get_route_is_valid()) {
                $this->route = $route;
                $this->route->get_params()->set('error', $err);
                $this->run_controller_method_by_route($route);
            } else {
                throw $err;
            }
        }
        return;

// search with static routes
        //natural routes
        // full namespace
        //deep namespaces?
        $m = [];
        if (preg_match("/^\/(?P<ns>[^\/\\\]{1,})\/(?P<controller>[^\/\\\]{1,})\/(?P<method>[^\/\\\]{1,})\/{0,1}$/i", $route, $m)) {
            if ($this->run_controller_method($m['ns'], $m['controller'], $m['method'])) {
                return;
            }
        } else if (preg_match("/^\/(?P<controller>[^\/\\\]{1,})\/(?P<method>[^\/\\\]{1,})\/{0,1}$/i", $route, $m)) {//default namespace
            if ($this->run_controller_method(\Config\Config::F()->DEFAULT_CONTROLLER_NAMESPACE, $m['controller'], $m['method'])) {
                return;
            }
        } else if (preg_match("/^\/(?P<controller>[^\/\\\]{1,})\/{0,1}$/i", $route, $m)) {//default namespace and default method
            if ($this->run_controller_method(\Config\Config::F()->DEFAULT_CONTROLLER_NAMESPACE, $m['controller'], null)) {
                return;
            }
        } else if (preg_match("/^\/{0,1}$/i", $route, $m)) {//default namespace and default controller and default method
            if ($this->run_controller_method(\Config\Config::F()->DEFAULT_CONTROLLER_NAMESPACE, null, null)) {
                return;
            }
        }
        // run 404 controller
        if (!headers_sent()) {
            header("HTTP/1.0 404 Not Found");
        }
        die("e404:{$route}");
    }

    protected function try_redirect() {
        $query = "SELECT target from redirects WHERE source=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => trim(Request::F()->request_path, "\\/")]);
        if ($row) {
            $target = \Helpers\Helpers::NEString($row['target'], null);
            if ($target && !headers_sent()) {
                header("Location: {$target}", true, 301);
                die();
            }
        }
    }

    protected function run_controller_method_by_route(IRoute $route = null) {
        $route = $route ? $route : $this->route;
        $class = $this->route->get_controller_class();
        $class::F()->run_method($route->get_action());
    }

    protected function run_controller_method(string $ns, string $controller = null, string $method = null): bool {
        if ($controller === null || $method === null) {
            $nsprops_class = "\\controllers\\{$ns}\\NSProps";
            if (class_exists($nsprops_class) && \Helpers\Helpers::class_implements($nsprops_class, \controllers\INSProps::class)) {
                $nsprops = $nsprops_class::F(); /* @var $nsprops \controllers\INSProps */
                $controller = \Helpers\Helpers::NEString($controller, $nsprops->get_default_controller());
                $controller_class = "\\controllers\\{$ns}\\{$controller}Controller";
                if ($controller && \Helpers\Helpers::class_exists($controller_class) && \Helpers\Helpers::class_inherits($controller_class, \controllers\abstract_controller::class)) {
                    $method = \Helpers\Helpers::NEString($method, \Helpers\Helpers::NEString($controller_class::get_default_action(), $nsprops->get_default_action()));
                }
            }
        }
        $class = "\\controllers\\{$ns}\\{$controller}Controller";
        if (class_exists($class) && \Helpers\Helpers::class_inherits($class, \controllers\abstract_controller::class)) {
            if ($class::CONTROLLER_HAS_METHOD($method)) {
                $this->namespace = $ns;
                $this->action = $method;
                $this->controller = $controller;
                $this->controller_class = $class;
                $class::F()->run_method($method);
                return true;
            }
        }
        return false;
    }

    public function redirect($url, $status = 301) {
        if (!headers_sent()) {
            header("Location: {$url}", true, $status);
            die();
        }
        RouterError::R("cant redirect - headers alredy sent");
    }

    public function redirect_to_login() {
        $current_url = "/" . ltrim($_SERVER['REQUEST_URI'], "/");
        $this->redirect(\Helpers\Helpers::add_params_to_url(\Helpers\Helpers::NEString(\Config\Config::F()->LOGIN_URL, "/Auth/Login"), ['return_url' => $current_url]), 302);
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return static::$instance ? static::$instance : new static();
    }

}
