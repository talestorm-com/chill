<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctCOLLECTION;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property int $id
 * @property string $common_name
 * @property string $default_poster
 * @property bool $enabled
 * @property string $name
 * @property string $intro
 * @property int $html_mode 
 * @property int $ratestars
 * @property MediaContentElement[] $items
 * @property int $mcsort
 * @property string $preplay_name
 * @property int $preplay
 * @property string $video_cdn_id
 * @property string $video_cdn_url
 * @property string $lent_mode
 * @property string $lent_image_name
 * @property string $gif_cdn_id
 * @property string $gif_cdn_url
 * @property string $lent_message
 * @property string $video_cdn_id2
 * @property string $video_cdn_url2
 * @property string $lent_mode2
 * @property string $lent_image_name2
 * @property string $gif_cdn_id2
 * @property string $gif_cdn_url2
 * @property string $lent_message2
 * @property string $translit_name
 */
class MediaContentObject implements \common_accessors\IMarshall, \Countable, \Iterator {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport,
        \common_accessors\TIterator;

    const MEDIA_CONTEXT = "media_content_poster";

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $common_name;
    /** @var string */
    protected $translit_name;
    /** @var string */
    protected $default_poster;

    /** @var bool */
    protected $enabled;

    /** @var string */
    protected $name;

    /** @var string */
    protected $intro;

    /** @var MediaContentElement[] */
    protected $items;

    /** @var int */
    protected $html_mode;

    /** @var int */
    protected $ratestars;
    protected $mcsort;

    /** @var string */
    protected $preplay_name;

    /** @var int */
    protected $preplay;

    /** @var string */
    protected $video_cdn_id;

    /** @var string */
    protected $video_cdn_url;

    /** @var string */
    protected $lent_mode;

    /** @var string */
    protected $lent_image_name;

    /** @var string */
    protected $gif_cdn_id;

    /** @var string */
    protected $gif_cdn_url;

    /** @var string */
    protected $lent_message;

    /** @var string */
    protected $video_cdn_id2;

    /** @var string */
    protected $video_cdn_url2;

    /** @var string */
    protected $lent_mode2;

    /** @var string */
    protected $lent_image_name2;

    /** @var string */
    protected $gif_cdn_id2;

    /** @var string */
    protected $gif_cdn_url2;

    /** @var string */
    protected $lent_message2;

    /** @var string */
    protected $meta_title;

    /** @var string */
    protected $meta_description;

    /** @var string */
    protected $additional_content;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }
    /** @return string */
    protected function __get__translit_name() {
        return $this->translit_name;
    }
    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return MediaContentElement[] */
    protected function __get__items() {
        return $this->items;
    }

    /** @return int */
    protected function __get__html_mode() {
        return $this->html_mode;
    }

    /** @return int */
    protected function __get__ratestars() {
        return $this->ratestars;
    }

    protected function __get__mcsort() {
        return $this->mcsort;
    }

    /** @return string */
    protected function __get__video_cdn_id2() {
        return $this->video_cdn_id2;
    }

    /** @return string */
    protected function __get__video_cdn_url2() {
        return $this->video_cdn_url2;
    }

    /** @return string */
    protected function __get__lent_mode2() {
        return $this->lent_mode2;
    }

    /** @return string */
    protected function __get__lent_image_name2() {
        return $this->lent_image_name2;
    }

    /** @return string */
    protected function __get__gif_cdn_id2() {
        return $this->gif_cdn_id2;
    }

    /** @return string */
    protected function __get__gif_cdn_url2() {
        return $this->gif_cdn_url2;
    }

    /** @return string */
    protected function __get__lent_message2() {
        return $this->lent_message2;
    }

    //</editor-fold>

    protected function __construct(int $id) {
        $this->load($id);
    }

    /** @return string */
    protected function __get__preplay_name() {
        return $this->preplay_name;
    }

    /** @return int */
    protected function __get__preplay() {
        return $this->preplay;
    }

    /** @return string */
    protected function __get__video_cdn_id() {
        return $this->video_cdn_id;
    }

    /** @return string */
    protected function __get__video_cdn_url() {
        return $this->video_cdn_url;
    }

    /** @return string */
    protected function __get__lent_mode() {
        return $this->lent_mode;
    }

    /** @return string */
    protected function __get__lent_image_name() {
        return $this->lent_image_name;
    }

    /** @return string */
    protected function __get__gif_cdn_id() {
        return $this->gif_cdn_id;
    }

    /** @return string */
    protected function __get__gif_cdn_url() {
        return $this->gif_cdn_url;
    }

    /** @return string */
    protected function __get__lent_message() {
        return $this->lent_message;
    }

    /** @return string */
    protected function __get__meta_title() {
        return $this->meta_title;
    }
    /** @return string */
    protected function __get__meta_description() {
        return $this->meta_description;
    }
    /** @return string */
    protected function __get__additional_content() {
        return $this->additional_content;
    }

    /** @return string */
    public function get__meta_title() {
        return $this->meta_title;
    }
    /** @return string */
    public function get__meta_description() {
        return $this->meta_description;
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
        $query = sprintf("SELECT A.enabled,A.mcsort, B.*, COALESCE(S1.name,S2.name) name,
            COALESCE(S1.html_mode,S2.html_mode) html_mode,
            COALESCE(S1.intro,S2.intro) intro,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            MPV.id preplay, MPV.name preplay_name,
            MLV.cdn_id video_cdn_id,MLV.cdn_url video_cdn_url,MLM.mode lent_mode,
            MLM.message lent_message,MLM.lent_image_name,
            MLG.cdn_id gif_cdn_id,MLG.cdn_url gif_cdn_url,
            LMV2.lent_mode2,LMV2.lent_message2,
            LMV2.gif_cdn_id2,LMV2.gif_cdn_url2,
            LMV2.video_cdn_id2,LMV2.video_cdn_url2,
            LMV2.lent_image_name2,B.meta_title, B.meta_description, B.additional_content,
            
            NULL sys_dummy_val
            FROM media__content A 
            JOIN  media__content__collection B ON (A.id=B.id) 
            LEFT JOIN media__content__collection_strings_lang_%s S1 ON(S1.id=A.id)
            LEFT JOIN media__content__collection_strings_lang_%s S2 ON(S2.id=A.id)
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            LEFT JOIN media__content__preplay MCPP ON(MCPP.content_id=A.id)
            LEFT JOIN media__preplay__video MPV ON(MPV.id=MCPP.preplay_id)
            LEFT JOIN media__lent__mode MLM ON(MLM.id=A.id)
            LEFT JOIN media__lent__gif MLG ON(MLG.id=A.id)
            LEFT JOIN media__lent__video MLV ON(MLV.id=A.id)
            LEFT JOIN media__lent__mode_page LMV2 ON(LMV2.id=A.id)
            WHERE A.id=:P", \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language());
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->import_props($row);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //bool
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'html_mode' => ['IntMore0', 'Default0'],
            'ratestars' => ['IntMore0', 'Default0'],
            'mcsort' => ['Int', 'Default0'],
            'preplay_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'preplay' => ['IntMore0', 'DefaultNull'],
            'video_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'video_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_mode' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_image_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'gif_cdn_id' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'gif_cdn_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_message' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'video_cdn_id2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'video_cdn_url2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_mode2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_image_name2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'gif_cdn_id2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'gif_cdn_url2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'lent_message2' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'additional_content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'translit_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string

        ];
    }

    protected function t_common_import_after_import() {
        $this->items = MediaContentElement::load_by_collection_id($this->id);
    }

    /**
     * 
     * @return $this
     */
    public function remove_disabled() {
        $ni = [];
        foreach ($this->items as $item) {
            if ($item->enabled) {
                $ni[] = $item;
            }
        }
        $this->items = $ni;
        return $this;
    }

}
