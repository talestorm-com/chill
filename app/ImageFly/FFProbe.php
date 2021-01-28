<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of FFProbe
 *
 * @author eve
 */
class FFProbe {

    public $metadata;
    public $pares_time;

    public function __construct($filename, $prettify = false) {
        if (!file_exists($filename)) {
            throw new Exception(sprintf('File not exists: %s', $filename));
        }

        $this->metadata = $this->probe($filename, $prettify);
    }

    public function probe($filename, $prettify) {
        // Start time
        $init = microtime(true);

        // Default options
        $options = '-loglevel quiet -show_format -show_streams -print_format json';

        if ($prettify) {
            $options .= ' -pretty';
        }

        // Avoid escapeshellarg() issues with UTF-8 filenames
        setlocale(LC_CTYPE, 'en_US.UTF-8');

        // Run the ffprobe, save the JSON output then decode
        $json = json_decode(shell_exec(sprintf('ffprobe %s %s', $options,
                                escapeshellarg($filename))), true);

        if (!array_key_exists("format", $json)) {
            throw new Exception('Unsupported file type');
        }

        // Save parse time (milliseconds)
        $this->parse_time = round((microtime(true) - $init) * 1000);

        return $json;
    }

    public function __get($key) {
        if (array_key_exists($key, $this->metadata)) {
            return $this->metadata[$key];
        }
        throw new Exception(sprintf('Undefined property: %s', $key));
    }

    public function get($key, $def = null) {
        return array_key_exists($key, $this->metadata) ? $this->metadata[$key] : $def;
    }

}
