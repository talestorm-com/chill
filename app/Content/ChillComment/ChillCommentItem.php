<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ChillComment;

/**
 * Description of ChillCommentItem
 *
 * @author eve
 * @property int $id
 * @property \DateTime $datum
 * @property string $author
 * @property bool $enabled
 * @property int $sticker
 * @property string $content
 * @property double $rating
 * @property string $sticker_url
 * @property string $sticker_name
 * @property string $r
 * @property bool $valid
 */
class ChillCommentItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    const CACHE_DEP = "chill_comment";

    /** @var int */
    protected $id;

    /** @var \DateTime */
    protected $datum;

    /** @var string */
    protected $author;

    /** @var bool */
    protected $enabled;

    /** @var int */
    protected $sticker;

    /** @var string */
    protected $content;

    /** @var double */
    protected $rating;

    /** @var string */
    protected $sticker_url;

    /** @var string */
    protected $sticker_name;
    
    /** @var string */
    protected $r;

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return \DateTime */
    protected function __get__datum() {
        return $this->datum;
    }

    /** @return string */
    protected function __get__author() {
        return $this->author;
    }

    /** @return bool */
    protected function __get__enabled() {
        return $this->enabled;
    }

    /** @return int */
    protected function __get__sticker() {
        return $this->sticker;
    }

    /** @return string */
    protected function __get__content() {
        return $this->content;
    }

    /** @return double */
    protected function __get__rating() {
        return $this->rating;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->author;
    }

    /** @return string */
    protected function __get__sticker_url() {
        return $this->sticker_url;
    }

    /** @return string */
    protected function __get__sticker_name() {
        return $this->sticker_name;
    }
    
    protected function __get__r(){
        return $this->r;
    }

    protected function __construct() {
        
    }

    public function load_db(int $id): ChillCommentItem {
        $row = \DB\DB::F()->queryRow('SELECT A.*, S.cdn_url sticker_url,B.rating,S.name sticker_name
            FROM chill__review A LEFT JOIN chill__review__rating B ON(A.id=B.id)
            LEFT JOIN chill__review__sticker S ON(S.id=A.sticker)
            WHERE A.id=:P', [":P" => $id]);
        return $this->load_array(is_array($row) ? $row : []);
    }

    public function load_array(array $data): ChillCommentItem {
        $this->import_props($data);
        return $this;
    }

    public static function F(int $id): ChillCommentItem {
        return (new static())->load_db($id);
    }

    public static function FA(array $data): ChillCommentItem {
        return (new static())->load_array($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'datum' => ['DateMatch', 'DefaultNull'], //\DateTime
            'author' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'enabled' => ['Boolean', 'DefaultFalse'], //bool
            'sticker' => ['IntMore0', 'DefaultNull'], //int
            'content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'], //string
            'r' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'rating' => ['Float', 'Default0'], //double
            'sticker_url' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sticker_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    public static function reset_cache() {
        \Cache\FileBeaconDependency::F([static::CACHE_DEP])->reset_dependency_beacons();
    }

    protected function t_default_marshaller_export_property_datum() {
        return $this->datum ? $this->datum->format('d.m.Y') : null;
    }

}
