<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\GalleryRenderer;

/**
 * @property \Content\IImageCollection $images
 * @property string $template
 * @property int $count
 */
class GalleryRenderer extends \Content\Content {

    /** @var \Content\IImageCollection */
    protected $images;
    protected $template;

    protected function __get__images() {
        return $this->images;
    }

    protected function __get__template() {
        return$this->template;
    }

    protected function __get__count() {
        return count($this->images);
    }

    protected function __construct(\Content\IImageSupport $holder) {
        $this->images = $holder->get_object_images();
    }

    public function render(\Smarty $smarty = null, string $template = 'default', bool $return = false) {
        if (count($this->images)) {
            $this->template = $template;
            return parent::render($smarty, $template, $return);
        }
        return null;
    }

    /**
     * 
     * @param \Content\IImageSupport $holder
     * @return \static
     */
    public static function F(\Content\IImageSupport $holder): GalleryRenderer {
        return new static($holder);
    }

    public function marshall() {
        return $this->images->marshall();
    }

}
