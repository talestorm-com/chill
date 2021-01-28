<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of Remover
 *
 * @author eve
 */
class Remover {

    /** @var \PublicMedia\PublicMediaItemShort */
    protected $item = null;

    public function __construct(\PublicMedia\PublicMediaItemShort $item) {
        $this->item = $item;
    }

    public function run() {
        $path = \PublicMedia\PublicMediaGallery::gallery_files_path($this->item->gallery_id);
        \Helpers\Helpers::rm_files_by_regex($path, ["/^{$this->item->id}\./", "/^preview\.{$this->item->id}\./"]);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM public__gallery__item WHERE id=:PP;")
                ->push("INSERT INTO public__gallery__counter (id,qty) 
                    VALUES( :P,COALESCE( (SELECT COUNT(*) FROM public__gallery__item WHERE gallery_id=:P ) ,0) ) 
                    ON DUPLICATE KEY UPDATE public__gallery__counter.qty=VALUES(qty);")
                ->push_params([
                    ":P" => $this->item->gallery_id,
                    ":PP" => $this->item->id,
                ])->execute();
        \PublicMedia\PublicMediaGallery::reset_cache_for($this->item->gallery_id);
    }

    /**
     * 
     * @param \PublicMedia\PublicMediaItemShort $item
     * @return \static
     */
    public static function F(\PublicMedia\PublicMediaItemShort $item) {
        return new static($item);
    }

}
