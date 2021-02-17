<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Router;

class NaturalRoute extends AbstractRoute {

    protected $debug = false;
    
    protected function is_valid_variant(string $namespace = null, string $controller = null, string $action = null): bool {
        $namespace = \Filters\FilterManager::F()->apply_chain($namespace, ['Trim','NEString','DefaultNull']);
        $controller = \Filters\FilterManager::F()->apply_chain($controller, ['Trim','NEString','DefaultNull']);
        $action = \Filters\FilterManager::F()->apply_chain($action, ['Trim','NEString','DefaultNull']);
        if (!$namespace) {
            $namespace = \Config\Config::F()->DEFAULT_CONTROLLER_NAMESPACE;
        }
        if ($namespace) {
            $real_namespace = "\\controllers\\{$namespace}\\";
            $nsparams_class_name = "{$real_namespace}NSProps";
            $nsparams = null; /* @var $nsparams \controllers\NSProps */
            if (class_exists($nsparams_class_name) && \Helpers\Helpers::class_inherits($nsparams_class_name, \controllers\NSProps::class)) {
                $nsparams = $nsparams_class_name::F();
            }
            if (!$controller && $nsparams) {
                $controller = $nsparams->default_controller;
            }
            if ($controller) {
                $controller_class = "{$real_namespace}{$controller}Controller";
                if (class_exists($controller_class) && \Helpers\Helpers::class_inherits($controller_class, \controllers\abstract_controller::class)) {
                    if (!$action) {
                        $action = $controller_class::get_default_action();
                    }
                    if (!$action && $nsparams) {
                        $action = $nsparams->default_action;
                    }
                    if ($action) {
                        if ($controller_class::CONTROLLER_HAS_METHOD($action)) {
                            $this->controller = $controller;
                            $this->action = $action;
                            $this->namespace = $namespace;
                            $this->controller_class = $controller_class;
                            $this->controller_namespace = $real_namespace;
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function __construct(string $url) {
        $this->debug = \DataMap\GPDataMap::F()->get_filtered('debug_route',['Boolean',"DefaultFalse"]);
        $this->params = \DataMap\CommonDataMap::F();
        $url = preg_replace("/\/\//i", "/", $url);
        $parts = explode("/", $url);
        $vparts = [];
        for ($i = 0; $i < count($parts); $i++) {
            $x = \Helpers\Helpers::NEString($parts[$i], null);
            $x ? $vparts[] = $x : 0;
        }
        
        // variant - action-controller-namespace
        $rparts = array_reverse($vparts);
        
        if (count($rparts) >= 3) {
            $action = $rparts[0];
            $controller = $rparts[1];
            $namespace = implode("\\", array_reverse(array_slice($rparts, 2)));            
            if ($this->is_valid_variant($namespace, $controller, $action)) {
                return;
            }
        }
        //variant default action - controller - namespace
        if (count($rparts) >= 2) {
            $controller = $rparts[0];
            $namespace = implode("\\", array_reverse(array_slice($rparts, 1)));            
            if ($this->is_valid_variant($namespace, $controller, null)) {
                return;
            }
        }
        //variant - action-controller-default_namespace
        if (count($rparts) >= 2) {
            $action = $rparts[0];
            $controller = $rparts[1];
            $namespace = implode("\\", array_reverse(array_slice($rparts, 2)));
            
            if ($this->is_valid_variant($namespace, $controller, $action)) {
                return;
            }
        }
        //variant - default action - default_controller - namespace
        if (count($rparts) >= 1) {
            $namespace = implode("\\", $parts);
            if ($this->is_valid_variant($namespace, null, null)) {
                return;
            }
        }
        
        //variant default_action,controller,default_namespace
        if(count($rparts)===1){
            $controller = $rparts[0];
            if($this->is_valid_variant(null,$controller,null)){
                return;
            }
        }

        //variant - default action default controller default namespace
        //this is a last variant
        if (count($rparts) === 0) {
            if ($this->is_valid_variant(null, null, null)) {
                return;
            }
        }
        $this->namespace = null;
        $this->controller = null;
        $this->controller_class = null;
        $this->controller_namespace = null;
        $this->action = null;
    }
    
    
    public static function F(string $url):NaturalRoute{
        return new static($url);
    }

}
