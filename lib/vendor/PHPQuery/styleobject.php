<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPQuery;

class styleobject {

    protected $selector;
    protected $rules;

    protected function __construct($sele) {
        $this->selector = $sele;
        $this->rules = [];
    }

    public function get_selector() {
        return $this->selector;
    }

    public function append_rules($rules_text) {       
        $this->parse_rules_into($rules_text, $this->rules);       
    }

    protected function parse_rules_into($rules_text, array &$target) {
        $arules = explode(";", $rules_text);        
        foreach ($arules as $rule_str) {
            if (mb_strlen(trim($rule_str),'UTF-8')) {
                $m = [];
                if (preg_match("/^\s{0,}(?P<prop>[^:]{1,})\s{0,}:\s{0,}(?P<val>.{1,})$/i", $rule_str, $m)) {
                    $prop = trim($m['prop']);
                    $value = trim(trim(trim($m['val']), ';'));
                    if (mb_strlen($prop, 'UTF-8') && mb_strlen($value, 'UTF-8')) {
                        $target[$prop] = $value;
                    }                   
                } 
            }
        }       
    }

    public function merge_rules() {
        $r = [];
        foreach ($this->rules as $p => $v) {
            $r[] = "{$p}:{$v};";
        }
        return implode("", $r);
    }

    public function merge_attributed_style($existed_style) {
        $rules = [];
        $this->parse_rules_into($existed_style, $rules);
        foreach ($this->rules as $prop => $value) {
            $rules[$prop] = $value;
        }
        $r = [];
        foreach ($rules as $p => $v) {
            $r[] = "{$p}:{$v};";
        }
        return implode("", $r);
    }

    public static function F($text) {
        return new static($text);
    }

}
