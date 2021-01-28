<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers;

/**
 * Description of AWriter
 *
 * @author eve
 * @property \DataMap\IDataMap $input
 * @property int $result_id
 * @property \DB\SQLTools\SQLBuilder $builder
 * @property string $temp_var
 * @property \DataMap\IDataMap $environment
 */
class AWriter {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var int */
    protected $result_id = null;

    /** @var \DB\SQLTools\SQLBuilder */
    protected $builder;

    /** @var string */
    protected $temp_var;

    /** @var \DataMap\IDataMap */
    protected $environment;

    protected function __get__input() {
        return $this->input;
    }

    protected function __get__result_id() {
        return $this->result_id;
    }

    protected function __get__builder() {
        return $this->builder;
    }

    protected function __get__temp_var() {
        return $this->temp_var;
    }

    /** @return \DataMap\IDataMap */
    protected function __get__environment() {
        return $this->environment;
    }

    protected function __construct(\DataMap\IDataMap $input) {
        $this->input = $input;
        $this->environment = \DataMap\CommonDataMap::F();
        $a = [];
        $this->environment->rebind($a);
        $this->builder = \DB\SQLTools\SQLBuilder::F();
        $this->temp_var = "@a" . md5(implode("_", [__METHOD__, microtime(true)]));
    }

    /**
     * 
     * @param \DataMap\IDataMap $map
     * @return \static
     */
    public static function F(\DataMap\IDataMap $map) {
        return new static($map);
    }

}
