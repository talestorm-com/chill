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
 * @property \DataMap\IDataMap $params
 * @property bool $valid
 */
abstract class AbstractRoute implements IRoute {

    use \common_accessors\TCommonAccess;


    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $namespace;

    /** @var string */
    protected $controller;

    /** @var string */
    protected $action;

    /** @var string */
    protected $controller_class;

    /** @var string */
    protected $controller_namespace;

    /** @var \DataMap\IDataMap */
    protected $params;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__namespace() {
        return $this->get_namespace();
    }

    /** @return string */
    protected function __get__controller() {
        return $this->get_controller();
    }

    /** @return string */
    protected function __get__action() {
        return $this->get_action();
    }

    /** @return string */
    protected function __get__controller_class() {
        return $this->get_controller_class();
    }

    /** @return string */
    protected function __get__controller_namespace() {
        return $this->get_controller_namespace();
    }

    /** @return \DataMap\IDataMap */
    protected function __get__params() {
        return $this->get_params();
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->get_route_is_valid();
    }

    //</editor-fold>

    public function get_action(): string {
        return $this->action;
    }

    public function get_controller(): string {
        return $this->controller;
    }

    public function get_controller_class(): string {
        return $this->controller_class;
    }

    public function get_controller_namespace(): string {
        return $this->controller_namespace;
    }

    public function get_namespace(): string {
        return $this->namespace;
    }

    public function get_params(): \DataMap\IDataMap {
        return $this->params;
    }

    public function get_route_is_valid(): bool {
        if (false) {
            echo "\ndumping route:\n\n";
            var_dump($this);
            echo "\nchecking route:\n\n";
            echo "cnt && ns:\n";
            var_dump($this->controller && $this->namespace);
            echo "\nclass_exists:\n";
            var_dump($this->controller_class ? class_exists($this->controller_class) : null);
            echo "\nclass_implements:\n";
            var_dump($this->controller_class ? \Helpers\Helpers::class_inherits($this->controller_class, \controllers\abstract_controller::class) : null);
        }
        if ($this->controller && $this->namespace) {
            if ($this->controller_class && class_exists($this->controller_class) && \Helpers\Helpers::class_inherits($this->controller_class, \controllers\abstract_controller::class)) {
                $cc = $this->controller_class;
                //       echo "\nclass_has_method:\n";
                //     var_dump($cc::CONTROLLER_HAS_METHOD($this->action));
                if ($cc::CONTROLLER_HAS_METHOD($this->action)) {
                    return true;
                }
            }
        }
        return false;
    }

}
