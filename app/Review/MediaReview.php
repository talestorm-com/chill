<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Review;

/**
 * Description of MediaReview
 *
 * @author eve
 * @property int $media_id
 * @property int $user_id
 * @property string $name
 * @property int $rate
 * @property string $info
 * @property \DateTime $post
 * @property string $post_str
 * @property string $post_date_str
 * @property string $post_time_str
 */
class MediaReview implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $media_id;

    /** @var int */
    protected $user_id;

    /** @var string */
    protected $name;

    /** @var int */
    protected $rate;

    /** @var string */
    protected $info;

    /** @var \DateTime */
    protected $post;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__media_id() {
        return $this->media_id;
    }

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return int */
    protected function __get__rate() {
        return $this->rate;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return \DateTime */
    protected function __get__post() {
        return $this->post;
    }

    /** @return string */
    protected function __get__post_str() {
        return $this->post ? $this->post->format('d.m.Y H:i') : null;
    }

    /** @return string */
    protected function __get__post_date_str() {
        return $this->post ? $this->post->format('d.m.Y') : null;
    }

    /** @return string */
    protected function __get__post_time_str() {
        return $this->post ? $this->post->format('H:i') : null;
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

    protected function t_default_marshaller_export_property_post() {
        return $this->post ? $this->post->format('d.m.Y H:i') : null;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'media_id' => ['IntMore0'], //int
            'user_id' => ['IntMore0'], //int
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'rate' => ['IntMore0'], //int
            'info' => ['Strip', 'Trim', 'NEString'], //string
            'post' => ['DateMatch'], //\DateTime
        ];
    }

}
