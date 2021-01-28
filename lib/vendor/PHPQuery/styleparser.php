<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPQuery;

class styleparser {

    protected $styles;

    public function __construct() {
        $this->styles = [];
    }

    public function append($cssText/* inner text! */) {
        //esm or regular?
        $cssText = strip_tags($cssText);
        $matches = [];        
        if (preg_match_all("/\s{0,}(?P<selectors>[^{}]{1,})\s{0,}\{(?P<rules>[^}]{0,})\}/", $cssText, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $selectors = trim($match['selectors']);
                $rules = trim($match['rules']);
                $aselectors = explode(",", $selectors);
                foreach ($aselectors as $selector) {
                    $selector = trim($selector);
                    if (mb_strlen($selector, 'UTF-8')) {
                        if (!array_key_exists($selector, $this->styles)) {
                            $this->styles[$selector] = styleobject::F($selector);
                        }
                        $this->styles[$selector]->append_rules($rules);
                    }
                }
            }
        }
    }

    public function sort_rules() {

        uksort($this->styles, function($k1, $k2) {
            return mb_strlen($k1, 'UTF-8') - mb_strlen($k2, 'UTF-8');
        });
    }
    
    /**
     * 
     * @return styleobject[]
     */
    public function get_rules(){
        return $this->styles;
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

}
