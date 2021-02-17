<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\CatalogTile\Writer;

class CatalogsWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\CatalogTile\Writer\CatalogsWriter
     */
    public static function F(): CatalogsWriter {
        return new static();
    }

    public function run(\DataMap\IDataMap $input, \DB\SQLTools\SQLBuilder $b, string $var) {
        $catalogs = $input->get_filtered("catalogs", ['NEArray', 'DefaultEmptyArray']);
        $clean_catalogs = [];
        foreach ($catalogs as $catalog) {
            if (is_array($catalog)) {
                $raw_catalog = \Filters\FilterManager::F()->apply_filter_array($catalog, $this->get_filters());
                if (\Filters\FilterManager::F()->is_values_ok($raw_catalog)) {
                    $clean_catalogs[] = $raw_catalog;
                }
            }
        }
        $b->inc_counter();
        $b->push("DELETE FROM catalog__tile__catalog WHERE t_id={$var};");
        $inserts = [];
        $ic = 0;
        $params = [];
        foreach ($clean_catalogs as $catalog) {
            $inserts[] = "({$var},:P{$b->c}_{$ic}_group,:P{$b->c}_{$ic}_sort,:P{$b->c}_{$ic}_override,:P{$b->c}_{$ic}_image_id)";
            $params[":P{$b->c}_{$ic}_group"] = $catalog['id'];
            $params[":P{$b->c}_{$ic}_sort"] = $catalog['sort'];
            $params[":P{$b->c}_{$ic}_override"] = $catalog['override'];
            $params[":P{$b->c}_{$ic}_image_id"] = $catalog['image_id'];
            
            $ic++;
        }
        if (count($inserts)) {
            $b->push(sprintf("INSERT INTO catalog__tile__catalog(t_id,c_id,sort,override,image_id) VALUES %s ON DUPLICATE KEY UPDATE sort=VALUES(sort),override=VALUES(override),image_id=VALUES(image_id);", implode(",", $inserts)));
            $b->push_params($params);
        }
        $b->inc_counter();
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0'],
            'sort' => ['Int', 'Default0'],
            'override'=>['Strip','Trim','NEString','DefaultNull'],
            'image_id'=>['Strip','Trim','NEString','DefaultNull'],
        ];
    }

}
