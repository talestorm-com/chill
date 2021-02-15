<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of AvatarGen
 *
 * @author eve
 */
class AvatarGen {

    private static $instance = null;

    protected function fmt($ix) {
        $x = dechex($ix);
        while (mb_strlen($x) < 2) {
            $x = "0" . $x;
        }
        return $x;
    }

    protected function __construct() {
        static::$instance = $this;
    }

    public static function F(): AvatarGen {
        return static::$instance ? static::$instance : new static();
    }

    public function mk_avatar(\Auth\UserInfo $user_info, string $output_path) {
        if ($user_info && $user_info->valid) {
            $monogramm = sprintf("%s%s", mb_strtoupper(mb_substr($user_info->name, 0, 1, "UTF-8"), "UTF-8"), mb_strtoupper(mb_substr($user_info->family, 0, 1, "UTF-8"), "UTF-8"));
            $name_crc = crc32(trim("{$user_info->name} {$user_info->family} {$user_info->eldername}"));
            $inverted_crc = ~$name_crc;
            $f_color = "#" . $this->fmt(($name_crc & 0xFF0000) >> 16) . $this->fmt(($name_crc & 0xFF00) >> 8) . $this->fmt($name_crc & 0xFF);
            $b_color = "#" . $this->fmt(($inverted_crc & 0xFF0000) >> 16) . $this->fmt(($inverted_crc & 0xFF00) >> 8) . $this->fmt($inverted_crc & 0xFF);
            $image = new \Imagick();
            $image->newimage(300, 300, $b_color);
            $draw = new \ImagickDraw();
            $draw->setFillColor($f_color);
            $draw->setstrokecolor($f_color);
            $draw->setFont(__DIR__ . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . "avgen.ttf");
            $draw->setFontSize(220 * 72 / 96);
            // $metric = $image->queryfontmetrics($draw, $monogramm, false);
            //$baseline = $metric['boundingBox']['y2'];
            //$textwidth = $metric['textWidth']; // + 2 * $metric['boundingBox']['x1'];
            //$textheight = $metric['textHeight'] + $metric['descender'] + $metric['ascender'];
            //var_dump($metric);die();            
            //$t = ((250 - $textheight) / 2);
            // $l = (250 - $textwidth) / 2;            
            $draw->setgravity(\Imagick::GRAVITY_CENTER);
            $image->annotateimage($draw, 0, 0, 0, $monogramm);
            $image->setImageFormat('jpg');
            $image->setCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->setImageCompression(\Imagick::COMPRESSION_LOSSLESSJPEG);
            $image->writeImage($output_path);
        }
    }

}
