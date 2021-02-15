<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CommonMap;

/**
 * Description of CommonMap
 *
 * @author eve
 * @property \DataMap\IDataMap $params
 * @property string $api_key
 * @property string $template Description
 */
class CommonMap extends \Content\Content {

    /** @var \DataMap\IDataMap */
    protected $params;
    protected $api_key;
    protected $template;

    public function get_params() {
        return $this->params;
    }
    
    protected function __get__api_key(){
        return $this->api_key;
    }
    protected function __get__template(){
        return $this->template;
    }
    
    protected function __get__params(){
        return $this->params;
    }

    public function __construct(\DataMap\IDataMap $params = null) {
        $this->params = $params ? $params : \DataMap\CommonDataMap::F();
        $this->api_key = \PresetManager\PresetManager::F()->get_filtered("MAP_BOX_KEY", ["Trim","NEString","DefaultEmptyString"]);
        $this->template = $this->params->get_filtered("template",["Strip","Trim","NEString","DefaultNull"]);
        $this->template?0:$this->template="default";
    }

    /**
     * 
     * @param \DataMap\IDataMap $params
     * @return \static
     */
    public static function F(\DataMap\IDataMap $params = null) {
        return new static($params);
    }
    
        

}
