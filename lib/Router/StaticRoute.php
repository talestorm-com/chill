<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Router;

class StaticRoute extends AbstractRoute {

    public function __construct(array $route_params, array $regex_match = null) {
        $this->params = \DataMap\CommonDataMap::F();
        if (array_key_exists(0, $route_params)) {
            $this->namespace = \Helpers\Helpers::NEString($route_params[0], null);
        }
        if (array_key_exists(1, $route_params)) {
            $this->controller = \Helpers\Helpers::NEString($route_params[1], null);
        }
        if (array_key_exists(2, $route_params)) {
            $this->action = \Helpers\Helpers::NEString($route_params[2], null);
        }
        if (array_key_exists(3, $route_params)) {
            $p = \Helpers\Helpers::safe_array($route_params[3]);
            $this->params = \DataMap\CommonDataMap::F()->rebind($p);
        }
        if (!$this->namespace) {
            $this->namespace = \Config\Config::F()->DEFAULT_CONTROLLER_NAMESPACE;
        }
        if ($this->namespace) {
            $this->controller_namespace = "\\controllers\\{$this->namespace}";
        }
        if ($this->controller && $this->controller_namespace) {
            $this->controller_class = "{$this->controller_namespace}\\{$this->controller}Controller";
        }

        if (!$this->action && $this->controller_class) {
            $cc = $this->controller_class;
            if (class_exists($cc) && \Helpers\Helpers::class_inherits($cc, \controllers\abstract_controller::class)) {
                $this->action = $cc::get_default_action();
            }
        }
        if (is_array($regex_match)) {
            foreach ($this->params->get_all_cloned() as $key => $value) {
                $m = [];
                if (preg_match("/^\\$\\$(?P<key>.{1,})$/i", $value, $m)) {
                    if (array_key_exists($m['key'], $regex_match)) {
                        $this->params->set($key, $regex_match[$m['key']]);
                    } else {
                        $this->params->set([$key], null);
                    }
                }
            }
        }
    }

    /**
     * 
     * 
     * @param array $route_params
     * @param array $regex_match
     * @return \Router\StaticRoute
     */
    public static function F(array $route_params, array $regex_match = null): StaticRoute {
        return new static($route_params, $regex_match);
    }

}
