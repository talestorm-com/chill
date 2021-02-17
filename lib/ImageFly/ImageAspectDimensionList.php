<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of ImageAspectDimensionList
 *
 * @author eve
 * @property ImageAspectDimension[] $items
 * @property string $context
 * @property string $owner_id
 * @property string $image
 */
class ImageAspectDimensionList implements \common_accessors\IMarshall, \Iterator, \Countable {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TIterator;

    private $prefix = "imagefly__";

    /** var ImageAspectDimension[] */
    protected $items;

    /** @var string */
    protected $context;

    /** @var string */
    protected $owner_id;

    /** @var string */
    protected $image;

    /** @return string */
    protected function __get__context() {
        return $this->context;
    }

    /** @return string */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    protected function __get__items() {
        return $this->items;
    }

    public function __construct(string $context, string $owner_id, string $image) {
        $this->context = $context;
        $this->items = [];
        $this->image = $image;
        $this->owner_id = $owner_id;
        $this->load();
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param string $image
     * @return \static
     */
    public static function F(string $context, string $owner_id, string $image) {
        return new static($context, $owner_id, $image);
    }

    protected function load() {
        $query = "SELECT * FROM {$this->prefix}aspect_preset WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage";
        $rows = \DB\DB::F()->queryAll($query, [":Pcontext" => $this->context, ":Powner_id" => $this->owner_id, ":Pimage" => $this->image]);
        foreach ($rows as $row) {
            $item = ImageAspectDimension::F($row);
            if ($item && $item->valid) {
                $this->items[] = $item;
            }
        }
    }
    
    

}
