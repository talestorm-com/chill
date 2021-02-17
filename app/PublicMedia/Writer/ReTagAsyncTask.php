<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer;

/**
 * Description of ReTagAsyncTask
 * объединяет теги галереи к элементу в отдельное табло для поиска
 * @author eve
 */
class ReTagAsyncTask extends \AsyncTask\AsyncTaskAbstract {

    const MODE_ITEM_ONLY = "item";
    const MODE_GALLERY = "gallery";

    //put your code here
    protected function exec() {
        $target = $this->params->get_filtered("mode", ["Strip", 'Trim', 'NEString', 'DefaultNull']);
        if (static::MODE_ITEM_ONLY === $target) {
            $this->run_rebuild_item();
        } elseif (static::MODE_GALLERY === $target) {
            $this->run_rebuild_gallery();
        }
    }

    protected function run_rebuild_item() {
        //gallery_id needed to find gallery tags
        $gallery_id = $this->params->get_filtered("gallery_id", ['IntMore0', 'DefaultNull']);
        $item_id = $this->params->get_filtered('item_id', ['IntMore0', 'DefaultNull']);
        if ($item_id && $gallery_id) {
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("DELETE FROM public__gallery__item__tag__result WHERE item_id=:P;")
                    ->push_params([
                        ":PP" => $gallery_id,
                        ":P" => $item_id
                    ])
                    ->push("INSERT INTO public__gallery__item__tag__result(item_id,tag_id)
                        SELECT :P,tag_id FROM public__gallery__tag 
                        WHERE gallery_id=:PP
                        ON DUPLICATE KEY UPDATE tag_id=VALUES(tag_id);                        
                        ")
                    ->push("
                        INSERT INTO public__gallery__item__tag__result(item_id,tag_id)
                        SELECT item_id,tag_id FROM public__gallery__item__tag
                        WHERE item_id=:P
                        ON DUPLICATE KEY UPDATE tag_id=VALUES(tag_id);                        
                        ")->execute();
        }
    }

    protected function run_rebuild_gallery() {
        $gallery_id = $this->params->get_filtered("gallery_id", ['IntMore0', 'DefaultNull']);
        if ($gallery_id) {
            \DB\SQLTools\SQLBuilder::F()
                    ->push("
                        DELETE A.* FROM public__gallery__item__tag__result
                        JOIN public__gallery__item B ON(A.item_id=B.id)
                        WHERE B.gallery_id=:PP;
                        ")
                    ->push_param(":PP", $gallery_id)
                    ->push("
                        INSERT INTO public__gallery__item__tag__result (item_id,tag_id)
                        SELECT B.id,T.tag_id
                        FROM public__gallery A JOIN public__gallery__item B ON(A.id=B.gallery_id)
                        JOIN public__gallery__tag T ON(T.gallery_id=A.id)
                        WHERE A.id=:PP
                        ON DUPLICATE KEY UPDATE tag_id=VALUES(tag_id);                        
                        ")
                    ->push("INSERT INTO public__gallery__item__tag__result (item_id,tag_id)
                        SELECT item_id,tag_id 
                        FROM public__gallery__item__tag 
                        WHERE gallery_id=:PP
                        ON DUPLICATE KEY UPDATE tag_id=VALUES(tag_id);                        
                        ")->execute();
        }
    }

}
