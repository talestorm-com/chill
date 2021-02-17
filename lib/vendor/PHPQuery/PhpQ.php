<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPQuery;

require_once __DIR__ . DIRECTORY_SEPARATOR . "class.php";

class PhpQ {

    /**
     * 
     * @param type $html
     * @return \phpQueryObject|\QueryTemplatesSource|\QueryTemplatesParse|\QueryTemplatesSourceQuery
     */
    public static function D($html) {
        return @\phpQuery::newDocumentHTML($html, 'utf-8');
    }

}
