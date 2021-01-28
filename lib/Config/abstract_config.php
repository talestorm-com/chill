<?php

namespace Config;

/**
 * 
 * ===PROPS_START===
 * ===PROPS_END===
 * 
 */
abstract class abstract_config {

    const DEBUG_UPGRADE = true;
    const PROPS_START = "* ===PROPS_START==";
    const PROPS_END = " * ===PROPS_END==";
    const PROP = " * @property %s $%s";

    protected $config_values = [];

    public function __get($name) {
        return array_key_exists($name, $this->config_values) ? $this->config_values[$name] : null;
    }

    protected function load(array $values) {
        $replaces = ["* fake abstract_config to use props with phpdoc "," ".static::PROPS_START."="];
        foreach ($values as $key => $value) {
            $this->config_values[$key] = $value;
            if (static::DEBUG_UPGRADE) {
                $type = "mixed";
                if (is_object($value)) {
                    $type = "\\". ltrim(get_class($value),"\\/");
                } else if (is_array($value)) {
                    $type = "array";
                } else if (is_bool($value)) {
                    $type = "bool";
                } else if (is_int($value)) {
                    $type = "integer";
                } else if (is_string($value)) {
                    $type = "string";
                } else if (is_float($value)) {
                    $type = "float";
                } else if (is_double($value)) {
                    $type = "double";
                }
                $replaces[] = sprintf(static::PROP, $type, $key);
            }
        }
        if (static::DEBUG_UPGRADE) {
            $replaces[] = static::PROPS_END."=";
            $text = file_get_contents(__FILE__);
            //\* ===PROPS_START===(?:\n|\r|.){0,}\* ===PROPS_END===
            $regex = "/" . preg_quote(static::PROPS_START, "/") . "\=(?:\n|\r|.){0,}" . preg_quote(static::PROPS_END,"/") . "\=/i";            
            $new_text = preg_replace($regex, implode("\n", $replaces), $text);
            file_put_contents(__DIR__.DIRECTORY_SEPARATOR . "config_props.php", $new_text, LOCK_EX);
        }
    }

}
