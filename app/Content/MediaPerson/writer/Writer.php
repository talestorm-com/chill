<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaPerson\writer;

/**
 * Description of Writer
 *
 * @author eve
 * @property \DataMap\IDataMap $input
 * @property int $result_id
 */
class Writer {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;
    protected $result_id;

    protected function __get__input() {
        return $this->input;
    }

    protected function __get__result_id() {
        return $this->result_id;
    }

    protected function __construct(\DataMap\IDataMap $datamap) {
        $this->input = $datamap;
    }

    public static function F(\DataMap\IDataMap $map): Writer {
        return new static($map);
    }

    /**
     * 
     * @return $this
     */
    public function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = "@a" . md5(__METHOD__);
        if ($data['id']) {
            $b->push("SET {$t} = :P{$b->c}id;")
                    ->push_param(":P{$b->c}id", $data["id"])
                    ->push("UPDATE media__content__actor SET 
                        common_name=:P{$b->c}name_en,                        
                        image=:P{$b->c}image                            
                        WHERE id={$t};    
                        ");
        } else {
            $b->push("INSERT INTO media__content__actor(common_name,image)
                VALUES(:P{$b->c}name_en,:P{$b->c}image);
                SET {$t} = LAST_INSERT_ID();    
                ");
        }
        $b->push(sprintf("INSERT INTO media__content__actor__strings_lang_%s (id,name,html_mode,intro,info)
            VALUES({$t},:P{$b->c}name,:P{$b->c}html_mode,:P{$b->c}intro,:P{$b->c}info)
                ON DUPLICATE KEY UPDATE name=VALUES(name),html_mode=VALUES(html_mode),intro=VALUES(intro),info=VALUES(info);
            ", \Language\LanguageList::F()->get_current_language()));
        $b->push_params([
            ":P{$b->c}name" => $data["name"],
            ":P{$b->c}name_en" => $data["name_en"],
            ":P{$b->c}html_mode" => $data["html_mode"],
            ":P{$b->c}image" => $data['image'],
            ":P{$b->c}intro" => $data["intro"],
            ":P{$b->c}info" => $data["info"],
        ]);
        $b->inc_counter();
        $props = \Content\MediaPerson\Properties::F()->load_from_object_array($this->input->get_filtered("properties", ["NEArray", "DefaultEmptyArray"]));
        $props->save($b, $t);
        $this->result_id = $b->execute_transact($t);
        \Content\MediaPerson\MediaPerson::reset_cached();
        return $this;
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', "DefaultNull"], //int
            'name_en' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'html_mode' => ['IntMore0', 'Default0'], //boolean            
            'image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string            
        ];
    }

}
