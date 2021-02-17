<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Emoji;

/**
 * Description of GenreWriter
 *
 * @author eve
 */
class EmojiWriter {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;
    protected $operation_id;
    protected $created = false;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return int */
    protected function __get__operation_id() {
        return $this->operation_id;
    }

    /** @return int */
    protected function __get__id() {
        return $this->operation_id;
    }

    /** @return bool */
    protected function __get__created() {
        return $this->created;
    }

    //</editor-fold>



    protected function check_image($svg_text) {
        
    }

    /**
     * 
     * @return $this
     */
    public function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $this->check_image($data['image']);
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = '@a' . md5(__FILE__ . __LINE__);
        if ($data['id']) {
            $b->push("SET {$t} = :P{$b->c}id;");
            $b->push("UPDATE media__emoji SET sort=:P{$b->c}sort,tag=:P{$b->c}tag,image=:P{$b->c}image, meta_title=:P{$b->c}meta_title, meta_description=:P{$b->c}meta_description, additional_content=:P{$b->c}additional_content WHERE id={$t};");
            $b->push_param(":P{$b->c}id", $data['id']);
        } else {
            $b->push("INSERT INTO media__emoji (tag,sort,image, meta_title, meta_description, additional_content) VALUES(:P{$b->c}tag, :P{$b->c}sort,:P{$b->c}image, :P{$b->c}meta_title, :P{$b->c}meta_description), :P{$b->c}additional_content);");
            $b->push("SET {$t} = LAST_INSERT_ID();");
        }

        $b->push_params([
            ":P{$b->c}sort" => $data["sort"],
            ":P{$b->c}image" => $data["image"],
            ":P{$b->c}tag" => $data["tag"],
        ]);
        $b->push_params([
            ":P{$b->c}meta_title" => $data["meta_title"],
            ":P{$b->c}meta_description" => $data["meta_description"],
            ":P{$b->c}additional_content" => $data["additional_content"],
        ]);

        $b->inc_counter();
        $b->push("DELETE FROM media__emoji__strings WHERE id={$t}; ");
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
            $b->push(sprintf("INSERT INTO media__emoji__strings(id,language_id,name) VALUES %s ON DUPLICATE KEY UPDATE name=VALUES(name);", implode(",", $inserts)))
                    ->push_params($params)->inc_counter();
        }

        $this->operation_id = $b->execute_transact($t);
        $this->process_image();
        EmojiList::reset_cache();
        return $this;
    }

    protected function process_image() {
        if (!\ImageFly\MediaContextInfo::F()->context_exists('SMILE')) {
            \ImageFly\MediaContextInfo::register_media_context('SMILE', 1200, 1200, 32, 32);
        }
        $files = \DataMap\FileMap::F()->get_by_field_name('image');
        if (count($files)) {
            \ImageFly\ImageFly::F()->process_upload_manual('SMILE', $this->operation_id, 'smile', $files[0]);
        }
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
            'sort' => ['Int', 'Default0'],
            'name' => ['NEArray', 'DefaultEmptyArray'],
            'image' => ['Trim', 'NEString', 'DefaultEmptyString'],
            'tag' => ['Strip', 'Trim', 'NEString'],
            'meta_title' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'meta_description' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString'],
            'additional_content' => ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']
        ];
    }

    protected function get_item_filters() {
        return [
            'language_id' => ['Strip', 'Trim', 'NEString'],
            'name' => ['Strip', 'Trim', 'NEString'],
        ];
    }

}
