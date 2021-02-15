<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Readers\ctCOLLECTION;

/**
 * Description of MediaContentElement
 *
 * @author eve
 * @property int $collection_id
 * @property int $content_id
 * @property int $id
 * @property string $content_type
 * @property string $name
 * @property string $common_name
 * @property string $default_poster
 * @property int $sort
 * @property bool $enabled (content is enabled)
 * @property bool $valid
 * @property int $ratestars
 * @property int $seasons_count
 * @property int $series_count
 * @property int $track_language
 * @property string $track_language_name
 * @property int $genre_id
 * @property string $genre_name
 * @property int $origin_country_id
 * @property string $origin_country_name
 * @property bool $free
 * @property int $age_restriction
 * @property string $age_restriction_name
 * @property string $age_restriction_tag
 * @property string $age_restriction_image
 */
class MediaContentElement implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $collection_id;

    /** @var int */
    protected $content_id;

    /** @var string */
    protected $content_type;

    /** @var string */
    protected $name;

    /** @var string */
    protected $default_poster;

    /** @var int */
    protected $sort;

    /** @var bool */
    protected $enabled;

    /** @var int */
    protected $ratestars;

    /** @var string */
    protected $common_name;

    /** @var int */
    protected $seasons_count;

    /** @var int */
    protected $series_count;

    /** @var int */
    protected $track_language;

    /** @var string */
    protected $track_language_name;

    /** @var int */
    protected $genre_id;

    /** @var string */
    protected $genre_name;

    /** @var int */
    protected $origin_country_id;

    /** @var string */
    protected $origin_country_name;

    /** @var bool */
    protected $free;

    /** @var int */
    protected $age_restriction;

    /** @var string */
    protected $age_restriction_name;

    /** @var string */
    protected $age_restriction_tag;

    /** @var string */
    protected $age_restriction_image;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__collection_id() {
        return $this->collection_id;
    }

    /** @return int */
    protected function __get__content_id() {
        return $this->content_id;
    }

    /** @return int */
    protected function __get__id() {
        return $this->content_id;
    }

    /** @return string */
    protected function __get__content_type() {
        return $this->content_type;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return int */
    protected function __get__sort() {
        return $this->sort;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->content_id && ($this->content_type === 'ctVIDEO' || $this->content_type === 'ctSEASON');
    }

    /** @return int */
    protected function __get__ratestars() {
        return $this->ratestars;
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

    /** @return int */
    protected function __get__genre_id() {
        return $this->genre_id;
    }

    /** @return string */
    protected function __get__genre_name() {
        return $this->genre_name;
    }

    /** @return int */
    protected function __get__origin_country_id() {
        return $this->origin_country_id;
    }

    /** @return string */
    protected function __get__origin_country_name() {
        return $this->origin_country_name;
    }

    /** @return bool */
    protected function __get__free() {
        return $this->free;
    }

    /** @return int */
    protected function __get__age_restriction() {
        return $this->age_restriction;
    }

    /** @return string */
    protected function __get__age_restriction_name() {
        return $this->age_restriction_name;
    }

    /** @return string */
    protected function __get__age_restriction_tag() {
        return $this->age_restriction_tag;
    }

    /** @return string */
    protected function __get__age_restriction_image() {
        return $this->age_restriction_image;
    }

    //</editor-fold>

    public function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'collection_id' => ['IntMore0'],
            'content_id' => ['IntMore0'],
            'enabled' => ['Boolean', 'DefaultFalse'],
            'name' => ['Strip', 'Trim', 'NEString'],
            'common_name' => ['Strip', 'Trim', 'NEString'],
            'content_type' => ['Strip', 'Trim', 'NEString'],
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
            'ratestars' => ['IntMore0', 'Default0'],
            'seasons_count' => ['IntMore0', 'DefaultNull'], //int
            'series_count' => ['IntMore0', 'DefaultNull'], //int
            'track_language' => ['IntMore0', 'DefaultNull'], //int
            'track_language_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'genre_id' => ['IntMore0', 'DefaultNull'], //int
            'genre_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'origin_country_id' => ['IntMore0', 'DefaultNull'], //int
            'origin_country_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'free' => ['Boolean', 'DefaultFalse'], //bool
            'age_restriction' => ['IntMore0', 'DefaultNull'], //int
            'age_restriction_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'age_restriction_tag' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'age_restriction_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
        ];
    }

    /**
     * 
     * @param int $collection_id
     * @return MediaContentElement[] 
     */
    public static function load_by_collection_id(int $collection_id) {
        $ra = [];
        $query = "
            SELECT
            A0.collection_id,A0.content_id,A0.sort,A.enabled,
            A.ctype content_type,
            COALESCE( CVS1.name,CVS2.name,CSS1.name,CSS2.name ) name,
            COALESCE( CV.default_poster,CS.default_poster) default_poster,
            A0.sort,
            CASE A.ctype
              WHEN 'ctVIDEO' THEN CV.common_name
              WHEN 'ctSEASON' THEN CS.common_name
              ELSE ''
            END common_name  ,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            A.track_language, COALESCE(TL1.name,TL2.name)track_language_name,
            MCGL.genre_id genre_id, COALESCE(MCGS1.name,MCGS2.name) genre_name,
            MCO.country_id origin_country_id, COALESCE(MCOS1.name,MCOS2.name) origin_country_name,
            A.series_count,A.seasons_count,
            A.free,
            AR.id age_restriction,AR.international_name age_restriction_tag,
            COALESCE(ARS1.name,ARS2.name) age_restriction_name,
            AR.default_image age_restriction_image,
            NULL as dmy
            FROM media__content__collection__items A0
            JOIN media__content A ON(A.id=A0.content_id)
            LEFT JOIN media__content__video CV ON(CV.id=A.id)
            LEFT JOIN media__content__season CS ON(CS.id=A.id)
            LEFT JOIN media__content__video__strings__lang_%s CVS1 ON(CVS1.id=CV.id)
            LEFT JOIN media__content__video__strings__lang_%s CVS2 ON(CVS2.id=CV.id)
            LEFT JOIN media__content__season__strings__lang_%s CSS1 ON(CSS1.id=CS.id)
            LEFT JOIN media__content__season__strings__lang_%s CSS2 ON(CSS2.id=CS.id)
            -- track language
            LEFT JOIN media__content__tracklang__strings TL1 ON(TL1.id=A.track_language AND TL1.language_id='%s')
            LEFT JOIN media__content__tracklang__strings TL2 ON(TL2.id=A.track_language AND TL2.language_id='%s')
            -- genre
            LEFT JOIN media__content__genre_list MCGL ON (MCGL.media_id=A.id AND MCGL.sort = 0)
            LEFT JOIN media__content__genre__strings MCGS1 ON(MCGS1.id=MCGL.genre_id AND MCGS1.language_id='%s')
            LEFT JOIN media__content__genre__strings MCGS2 ON(MCGS2.id=MCGL.genre_id AND MCGS2.language_id='%s')
            -- country
            LEFT JOIN media__content__origin MCO ON(MCO.id=A.id AND MCO.sort = 0)
            LEFT JOIN media__content__origin__country__strings MCOS1 ON(MCOS1.id=MCO.country_id AND MCOS1.language_id='%s')
            LEFT JOIN media__content__origin__country__strings MCOS2 ON(MCOS2.id=MCO.country_id AND MCOS2.language_id='%s')
            -- stars
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            -- age restriction
            LEFT JOIN media__age__restriction AR ON(AR.id=A.age_restriction)
            LEFT JOIN media__age__restriction__strings ARS1 ON(ARS1.id=AR.id AND ARS1.language_id='%s')
            LEFT JOIN media__age__restriction__strings ARS2 ON(ARS2.id=AR.id AND ARS2.language_id='%s')
            WHERE A0.collection_id=:P ORDER BY A0.sort
            ";
        $language = \Language\LanguageList::F()->get_current_language();
        $def_lang = \Language\LanguageList::F()->get_default_language();
        $rq = sprintf($query, $language, $def_lang, $language, $def_lang, $language, $def_lang, $language, $def_lang, $language, $def_lang,$language, $def_lang);
        $rows = \DB\DB::F()->queryAll($rq, [":P" => $collection_id]);

        foreach ($rows as $row) {
            try {
                $item = static::F($row);
                if ($item && $item->valid) {
                    $ra[] = $item;
                }
            } catch (\Throwable $e) {
                
            }
        }
        return $ra;
    }

}
