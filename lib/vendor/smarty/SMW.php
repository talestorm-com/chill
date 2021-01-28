<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eeboh9zu3eiquei7ohz3AhGhophah7iek5oango3laibooradu {

    class Translator {

        protected function __construct() {
            
        }

        public static function F() {
            return new static();
        }

        public function T(string $term, string $language = null): string {
            $language_id = $language ? $language : \Language\LanguageList::F()->get_current_language();
            return \Language\LanguageTokenList::F($language_id)->t($term);
        }

    }

}

namespace smarty {

    /**
     * @property \starty $smarty
     */
    class SMW {

        use \common_accessors\TCommonAccess;

        /** @var SMW */
        private static $instance;

        /** @var \smarty */
        private $smarty;

        protected function __construct() {
            static::$instance = $this;
            require_once __DIR__ . DIRECTORY_SEPARATOR . "libs" . DIRECTORY_SEPARATOR . "Smarty.class.php";
        }

        protected function __get__smarty() {
            if (!$this->smarty) {
                $this->smarty = new \Smarty();
                $this->smarty->setTemplateDir(\Config\Config::F()->SMARTY_BASE_DIR . 'templates');
                $this->smarty->setCompileDir(\Config\Config::F()->SMARTY_BASE_DIR . 'templates_c');
                $this->smarty->setCacheDir(\Config\Config::F()->SMARTY_BASE_DIR . 'cache');
                $this->smarty->setConfigDir(\Config\Config::F()->SMARTY_BASE_DIR . 'configs');
                $this->smarty->assign('T', \eeboh9zu3eiquei7ohz3AhGhophah7iek5oango3laibooradu\Translator::F());
            }
            return $this->smarty;
        }

        /**
         * 
         * @return \static
         */
        public static function F() {
            return static::$instance ? static::$instance : new static();
        }

    }

}