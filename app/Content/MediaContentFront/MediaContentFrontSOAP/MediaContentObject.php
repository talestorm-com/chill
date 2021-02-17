<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\MediaContentFrontSOAP;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property SeasonList $seasons
 * @property TrailerList $trailers
 * @property \Content\IImageCollection $images
 * @property \Content\IImageCollection $frames
 * @property string $intro
 * @property string $info
 * @property string $common_name
 * @property string $default_poster
 * @property string $name
 * @property int $ratestars
 * @property string $origin_language
 * @property \DateTime $released
 * @property string $released_ymd
 * @property int $seasons_count
 * @property int $series_count
 * @property int $track_language
 * @property string $track_language_name
 * @property string $preplay_video_url
 */
class MediaContentObject extends \Content\MediaContentFront\MediaContentObject {

    const TARGET_TYPE_TAG = 'ctSEASON';

    private static $_class_version;

    public static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode(",", [__FILE__, filemtime(__FILE__), parent::get_class_version()]));
        }
        return static::$_class_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var SeasonList */
    protected $seasons;

    /** @var TrailerList */
    protected $trailers;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var \Content\IImageCollection */
    protected $frames;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $default_poster;

    /** @var string */
    protected $name;
    protected $ratestars;

    /** @var string */
    protected $origin_language;

    /** @var \DateTime */
    protected $released;

    /** @var int */
    protected $seasons_count;

    /** @var int */
    protected $series_count;

    /** @var int */
    protected $track_language;

    /** @var string */
    protected $track_language_name;

    /** @var string */
    protected $preplay_video_url;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return SeasonList */
    protected function __get__seasons() {
        return $this->seasons;
    }

    /** @return TrailerList */
    protected function __get__trailers() {
        return $this->trailers;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    /** @return \Content\IImageCollection */
    protected function __get__frames() {
        return $this->frames;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    protected function __get__ratestars() {
        return $this->ratestars;
    }

    /** @return string */
    protected function __get__origin_language() {
        return $this->origin_language;
    }

    /** @return \DateTime */
    protected function __get__released() {
        return $this->released;
    }

    /** @return string */
    protected function __get__released_dmy() {
        return $this->released ? $this->released->format('d.m.Y') : null;
    }

    /** @return int */
    protected function __get__seasons_count() {
        return $this->seasons_count;
    }

    /** @return int */
    protected function __get__series_count() {
        return $this->series_count;
    }

    /** @return int */
    protected function __get__track_language() {
        return $this->track_language;
    }

    /** @return string */
    protected function __get__track_language_name() {
        return $this->track_language_name;
    }

    /** @return string */
    protected function __get__preplay_video_url() {
        return $this->preplay_video_url;
    }

    //</editor-fold>




    public function load(int $id) {
        $sq = "
            SELECT 
            A.id,A.ctype content_type,B.common_name,B.default_poster,B.released,B.origin_language,
            COALESCE(S1.name,S2.name) name,
            COALESCE(S1.intro,S2.intro) intro,
            COALESCE(S1.info,S2.info) info,
            A.emoji emoji_id,
            EMJ.tag emoji_tag,
            COALESCE(EMS1.name,EMS2.name) emoji_name,
            A.age_restriction,
            AR.international_name age_restriction_tag,
            COALESCE(ARS1.name,ARS2.name) age_restriction_name,
            AR.default_image age_restriction_image,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            A.track_language, COALESCE(TL1.name,TL2.name)track_language_name,            
            A.series_count,A.seasons_count,
            MPV.cdn_url preplay_video_url,
            NULL as dmy
            FROM media__content A
             JOIN media__content__season B ON(B.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s S1 ON(S1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s S2 ON(S2.id=A.id)            
            LEFT JOIN media__emoji EMJ ON(EMJ.id=A.emoji)
            LEFT JOIN media__emoji__strings EMS1 ON(EMS1.id=EMJ.id AND EMS1.language_id='%s')
            LEFT JOIN media__emoji__strings EMS2 ON(EMS2.id=EMJ.id AND EMS2.language_id='%s')
            LEFT JOIN media__age__restriction AR ON(AR.id=A.age_restriction)
            LEFT JOIN media__age__restriction__strings ARS1 ON(ARS1.id=AR.id AND ARS1.language_id='%s')
            LEFT JOIN media__age__restriction__strings ARS2 ON(ARS2.id=AR.id AND ARS2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            -- track language
            LEFT JOIN media__content__tracklang__strings TL1 ON(TL1.id=A.track_language AND TL1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings TL2 ON(TL2.id=A.track_language AND TL2.language_id='%s')
            LEFT JOIN media__content__preplay MCP ON(MCP.content_id=A.id)
            LEFT JOIN media__preplay__video MPV ON (MPV.id=MCP.preplay_id)

            WHERE A.id=:P;            
            ";
        $row = \DB\DB::F()->queryRow($this->sprintf_query($sq), [':P' => $id]);
        $row ? 0 : \Errors\common_error::R("not found");
        $this->load_array($row);
    }

    protected function t_common_import_get_filters(): array {
        return array_merge(parent::t_common_import_get_filters(), [
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'common_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'ratestars' => ['IntMore0', 'Default0'],
            'released' => ['DateMatch', 'DefaultNull'],
            'origin_language' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'seasons_count' => ['IntMore0', 'DefaultNull'], //int
            'series_count' => ['IntMore0', 'DefaultNull'], //int
            'track_language' => ['IntMore0', 'DefaultNull'], //int
            'track_language_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'preplay_video_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ]);
    }

    protected function t_common_import_after_import() {
        parent::t_common_import_after_import();
        if ($this->id) {
            $this->images = \Content\DefaultImageCollection::F(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_POSTERS, $this->id);
            $this->trailers = \Content\MediaContentFront\TrailerList\TrailerList::F()->load($this->id, $this->language, $this->default_language);
            $this->seasons = SeasonList::F()->load($this->id, $this->language, $this->default_language);
            $this->frames = \Content\DefaultImageCollection::F(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_FRAMES, $this->id);
        }
    }

    public function render(\Smarty $smarty = null, string $template = 'ctSOAP', bool $return = false) {
        return parent::render($smarty, $template, $return);
    }

    protected function t_default_marshaller_export_property_released() {
        return $this->released ? $this->released->format('d.m.Y H:i:s') : null;
    }

}
