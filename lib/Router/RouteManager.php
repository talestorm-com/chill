<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Router;

class RouteManager {

    protected $routes;
    protected $special_routes;
    protected static $instance;

    protected function __construct() {
        static::$instance = $this;
        $routeconf = \Helpers\Helpers::safe_array(\Config\Config::F()->ROUTES);
        if (array_key_exists("static", $routeconf)) {
            $this->special_routes = \Helpers\Helpers::safe_array($routeconf['static']);
        } else {
            $this->special_routes = [];
        }

        if (array_key_exists('routes', $routeconf)) {
            $this->routes = \Helpers\Helpers::safe_array($routeconf['routes']);
        } else {
            $this->special_routes = [];
        }
    }

    public function get_special_route_for($situation = '404') {
        if (array_key_exists($situation, $this->special_routes)) {
            return StaticRoute::F($this->special_routes[$situation]);
        }
        return null;
    }

    /**
     * 
     * @param string $url
     * @return IRoute
     */
    public function get_static_route(string $url) {
        foreach ($this->routes as $url_rx => $route_params) {
            $m = [];
            if (preg_match($url_rx, $url, $m)) {
                $route = StaticRoute::F($route_params, $m);
                if ($route && $route->valid) {
                    return $route;
                }
            }
        }
        return null;
    }

    /**
     * 
     * @param string $url
     * @return IRoute
     */
    public function get_natural_route(string $url) {
        $route = NaturalRoute::F($url);
        return $route && $route->valid ? $route : null;
    }

    /**
     * 
     * @return \Router\RouteManager
     */
    public static function F(): RouteManager {
        return static::$instance ? static::$instance : new static();
    }

}
