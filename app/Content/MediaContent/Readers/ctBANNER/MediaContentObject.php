<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctBANNER;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property string $name
 * @property string $background
 * @property string $text_color 
 * @property string $default_poster
 * @property bool $enabled
 * @property array $strings
 */
class MediaContentObject implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    const MEDIA_CONTEXT = "media_content_poster";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $background;

    /** @var string */
    protected $text_color;

    /** @var string */
    protected $default_poster;

    /** @var bool */
    protected $enabled;

    /** @var array */
    protected $strings;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__background() {
        return $this->background;
    }

    /** @return string */
    protected function __get__text_color() {
        return $this->text_color;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return array */
    protected function __get__strings() {
        return $this->strings;
    }

    //</editor-fold>

    protected function __construct(int $id) {
        $this->load($id);
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    protected function load(int $id) {
        $query = "SELECT A.enabled, B.* FROM media__content A JOIN  media__content__banner B ON (A.id=B.id) WHERE A.id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'background' => ['HTMLColor', 'DefaultNull'], //string
            'text_color' => ['HTMLColor', 'DefaultNull'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'enabled' => ['Boolean', 'DefaultTrue'], //bool
        ];
    }

    protected function t_common_import_after_import() {
        $this->load_strings();
    }

    protected function load_strings() {
        $this->strings = [];
        $rows = \DB\DB::F()->queryAll("SELECT language_id,url,bannertext  FROM media__content__banner__strings WHERE id=:P", [":P" => $this->id]);
        foreach ($rows as $row) {
            try {
                $crow = \Filters\FilterManager::F()->apply_filter_array($row, $this->get_lang_filters());
                \Filters\FilterManager::F()->raise_array_error($crow);
                if ($crow['bannertext'] || $crow['url']) {
                    $this->strings[$crow['language_id']] = $crow;
                }
            } catch (\Throwable $e) {
                
            }
        }
    }

    protected function get_lang_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'bannertext' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
