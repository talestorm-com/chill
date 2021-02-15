<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia;

/**
 * Description of PublicMediaGallery
 * ридер публичной галереи
 * @author eve
 * @property int $id
 * @property string $uid
 * @property int $owner_id
 * @property string $name
 * @property string $info
 * @property boolean $visible
 * @property \DateTime $updated
 * @property string[] $tags
 * @property string $version
 * @property int $qty
 * @property PublicMediaItemShort[] $items
 * @property bool $valid 
 * @property float $cover_aspect
 * 
 */
class PublicMediaGalleryShort extends \PublicMedia\PublicMediaGallery {

   

   

   

    protected function t_common_import_after_import() {
       // $this->load_tags();
   
    }

   


   

}
