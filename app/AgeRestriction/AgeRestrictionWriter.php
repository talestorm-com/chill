<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AgeRestriction;

/**
 * Description of AgeRestrictionWriter
 *
 * @author eve
 * @property integer $result_id
 * @property \DataMap\IDataMap $input
 */
class AgeRestrictionWriter {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;
    protected $result_id;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return int */
    protected function __get__result_id() {
        return $this->result_id;
    }

    //</editor-fold>

    /**
     * 
     * @return $this
     */
    public function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = '@a' . md5(__FILE__ . __LINE__);
        if ($data['id']) {
            $b->push("SET {$t} = :P{$b->c}id;");
            $b->push("UPDATE media__age__restriction SET international_name=:P{$b->c}international_name,default_image=:P{$b->c}default_image WHERE id={$t};");
            $b->push_param(":P{$b->c}id", $data['id']);
        } else {
            $b->push("INSERT INTO media__age__restriction (international_name,default_image) VALUES(:P{$b->c}international_name,:P{$b->c}default_image);");
            $b->push("SET {$t} = LAST_INSERT_ID();");
        }

        $b->push_params([
            ":P{$b->c}international_name" => $data['international_name'],
            ":P{$b->c}default_image" => $data['default_image']
        ]);
        $b->inc_counter();
        $b->push("DELETE FROM media__age__restriction__strings WHERE id={$t}; ");
        $b->inc_counter();
        $params = [];
        $c = 0;
        $inserts = [];
        foreach ($data['name'] as $item) {
            if (is_array($item)) {
                try {
                    $citem = \Filters\FilterManager::F()->apply_filter_array($item, $this->get_item_filters());
                    \Filters\FilterManager::F()->raise_array_error($citem);
                    $c++;
                    $inserts[] = "({$t},:P{$b->c}_i{$c}_lang,:P{$b->c}_i{$c}_name)";
                    $params = array_merge($params, [
                        ":P{$b->c}_i{$c}_lang" => $citem["language_id"],
                        ":P{$b->c}_i{$c}_name" => $citem["name"],
                    ]);
                    $c++;
                } catch (\Throwable $e) {
                    
                }
            }
        }
        if (count($inserts)) {
            $b->push(sprintf("INSERT INTO media__age__restriction__strings(id,language_id,name) VALUES %s ON DUPLICATE KEY UPDATE name=VALUES(name);", implode(",", $inserts)))
                    ->push_params($params)->inc_counter();
        }

        $this->result_id = $b->execute_transact($t);
        AgeRestriction::reset_cached();
        return $this;
    }

    private function __construct(\DataMap\IDataMap $input) {
        $this->input = $input;
    }

    /**
     * 
     * @param \DataMap\IDataMap $input
     * @return \static
     */
    public static function F(\DataMap\IDataMap $input) {
        return new static($input);
    }

    protected function get_filters() {
        return[
            'id' => ['IntMore0', 'DefaultNull'],
            'international_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'name' => ['NEArray', 'DefaultEmptyArray'],
        ];
    }

    protected function get_item_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
        ];
    }

}
