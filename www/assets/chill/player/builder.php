<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AA4A4679C13A412D88F9640CB58907F7;

class Builder {

    public $input_dir;
    public $tp_dir;
    public $input_file;
    public $output_file;

    protected function __construct() {
        $this->input_dir = __DIR__ . DIRECTORY_SEPARATOR;
        $this->tp_dir = "{$this->input_dir}TPL" . DIRECTORY_SEPARATOR;
        $this->input_file = "{$this->input_dir}player_dev.js";
        $this->output_file = "{$this->input_dir}player.js";
    }
    
    
    public function include_file($path_to_file){
        ob_start();
        include $path_to_file;
        return ob_get_clean();
    }

    public function include_templates() {
        $r = [];
        $files = scandir($this->tp_dir);
        foreach ($files as $file) {
            if (is_file($this->tp_dir . $file)) {
                $m = [];
                if (preg_match('/^(?P<n>.{1,})\.html$/i', $file, $m)) {
                    $r[$m['n']] = $this->include_file($this->tp_dir . $file);
                }
            }
        }
        return "*/ templates = " . json_encode($r) . ";/* ";
    }

    public function run() {
        ob_start();
        include $this->input_file;
        $r = ob_get_clean();
        file_put_contents($this->output_file, $r);
    }

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static();
    }

}

Builder::F()->run();
die('done');
