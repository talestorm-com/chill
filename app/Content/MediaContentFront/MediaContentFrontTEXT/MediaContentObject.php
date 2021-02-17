<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\MediaContentFrontTEXT;

/**
 * Description of MediaContentObject
 *
 * @author eve
 * @property \Content\IImageCollection $images
 * @property string $intro
 * @property string $info
 * @property string $common_name
 * @property string $default_poster
 * @property string $name
 * @property \DateTime $post
 * @property string $post_string
 * @property string $postdate_string
 * @property string $posttime_string
 * @property int $ratestars
 */
class MediaContentObject extends \Content\MediaContentFront\MediaContentObject {

    const TARGET_TYPE_TAG = 'ctTEXT';

    private static $_class_version;

    public static function get_class_version() {
        if (!static::$_class_version) {
            static::$_class_version = md5(implode(",", [__FILE__, filemtime(__FILE__), parent::get_class_version()]));
        }
        return static::$_class_version;
    }

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var \Content\IImageCollection */
    protected $images;

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

    /** @var \DateTime */
    protected $post;
    
    protected $ratestars;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
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

    /** @return \DateTime */
    protected function __get__post() {
        return $this->post;
    }

    /** @return string */
    protected function __get__post_string() {
        return $this->post ? $this->post->format('d.m.Y H:i') : null;
    }

    /** @return string */
    protected function __get__postdate_string() {
        return $this->post ? $this->post->format('d.m.Y') : null;
    }

    /** @return string */
    protected function __get__posttime_string() {
        return $this->post ? $this->post->format('H:i') : null;
    }
    
    protected function __get__ratestars(){
        return $this->ratestars;
    }

    //</editor-fold>




    public function load(int $id) {
        $sq = "
            SELECT 
            A.id,A.ctype content_type,B.common_name,B.default_poster,A.enabled,B.post,
            COALESCE(S1.name,S2.name) name,
            COALESCE(S1.intro,S2.intro) intro,
            COALESCE(S1.info,S2.info) info,
            A.emoji emoji_id,
            EMJ.tag emoji_tag,
            COALESCE(EMS1.name,EMS2.name) emoji_name,
            A.age_restriction,
            AR.international_name age_restriction_tag,
            COALESCE(ARS1.name,ARS2.name) age_restriction_name,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars
            FROM media__content A
            LEFT JOIN media__content__text B ON(B.id=A.id)
            LEFT JOIN media__content__text__strings__lang_%s S1 ON(S1.id=A.id)
            LEFT JOIN media__content__text__strings__lang_%s S2 ON(S2.id=A.id)            
            LEFT JOIN media__emoji EMJ ON(EMJ.id=A.emoji)
            LEFT JOIN media__emoji__strings EMS1 ON(EMS1.id=EMJ.id AND EMS1.language_id='%s')
            LEFT JOIN media__emoji__strings EMS2 ON(EMS2.id=EMJ.id AND EMS2.language_id='%s')
            LEFT JOIN media__age__restriction AR ON(AR.id=A.age_restriction)
            LEFT JOIN media__age__restriction__strings ARS1 ON(ARS1.id=AR.id AND ARS1.language_id='%s')
            LEFT JOIN media__age__restriction__strings ARS2 ON(ARS2.id=AR.id AND ARS2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
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
            'post' => ['DateMatch', 'DefaultNull'], //Datetime
            'ratestars'=>['IntMore0','Default0'],
        ]);
    }

    protected function t_common_import_after_import() {
        parent::t_common_import_after_import();
        if ($this->id) {
            $this->images = \Content\DefaultImageCollection::F(\Content\MediaContent\Readers\ctTEXT\MediaContentObject::MEDIA_CONTEXT, $this->id);
        }
    }

    public function render(\Smarty $smarty = null, string $template = 'ctTEXT', bool $return = false) {
        return parent::render($smarty, $template, $return);
    }

    protected function t_default_marshaller_export_property_post() {
        return $this->post ? $this->post->format('d.m.Y H:i') : null;
    }

}
