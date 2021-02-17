<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content;

/**
 * @property string $template_search_path
 */
class Content implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    protected $_stored_vars;

    protected function get_template_text(string $template_name = null): string {
        $template_name ? 0 : $template_name = 'default';
        $path = $this->get_template_file_name($template_name);
        if ($path) {
            return file_get_contents($path);
        }
        return "<!-- no template `{$template_name}` -->";
    }

    protected function get_template_file_name($template = null) {
        return ContentViewResolver::F()->resolve_path_for_object($this, $template);
    }

    protected function assign_var(\Smarty $smarty, string $name, $value) {
        $this->_stored_vars[$name] = $smarty->getTemplateVars($name);
        $smarty->assign($name, $value);
    }

    protected function assign_module_vars(\Smarty $smarty) {
        $this->_stored_vars = [];
        $this->assign_var($smarty, 'this', $this);
    }

    public function render(\Smarty $smarty = null, string $template = 'default', bool $return = false) {
        $smarty = $smarty ? $smarty : \smarty\SMW::F()->smarty;
        $template_file_name = $this->get_template_file_name($template);
        $this->assign_module_vars($smarty);
        $result = "";
        if ($template_file_name) {
            if ($return) {
                $result = $smarty->fetch($template_file_name);
            } else {
                $smarty->display("{$template_file_name}");
            }
        } else {
            if ($return) {
                $result = $smarty->fetch("string:{$this->get_template_text($template)}");
            } else {
                $smarty->display("string:{$this->get_template_text($template)}");
            }
        }
        $this->restore_module_vars($smarty);
        return $result;
    }

    protected function restore_module_vars(\Smarty $smarty) {
        if (is_array($this->_stored_vars)) {
            foreach ($this->_stored_vars as $key => $value) {
                $smarty->assign($key, $value);
            }
        }
    }

    protected function t_common_import_after_import() {
        
    }
    
    
    
    
    
    public function create_front_templates($relative_path){
        $result=[".d"=>''];
        $base_path = rtrim(ContentViewResolver::F()->get_templates_dir_for_object($this, $relative_path),DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        if(file_exists($base_path) && is_dir($base_path) && is_readable($base_path)){
            $list = scandir($base_path);
            foreach ($list as $name){
                if(mb_substr($name, 0,1,'UTF-8')!=='.'){
                    $lpath = $base_path.$name;
                    if(file_exists($lpath) && is_file($lpath) && is_readable($lpath)){
                        $m = [];
                        if(preg_match("/^(?P<n>.*)\.html$/i", $name,$m)){
                           $result[$m["n"]]= file_get_contents($lpath); 
                        }
                    }
                }
            }
        }
        return json_encode($result);
    }

}
