<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\TagList;

/**
 * Description of GenreTagList
 *
 * @author eve
 */
class TagTagList extends TagList{
    
    protected function get_key_column(): string {
        return 'media_id';
    }

    protected function get_language_column(): string {
        return 'language_id';
    }

    protected function get_language_link_mode(): int {
        return static::LANGUAGE_LINK_MODE_SIMTABLE;
    }

    protected function get_linked_table(): string {
        return 'media__content__tag';
    }

    protected function get_linked_table_key_column(): string {
        return 'id';
    }

    protected function get_list_column(): string {
        return 'tag_id';
    }

    protected function get_list_table(): string {
        return 'media__content__tag__list';
    }

    protected function get_name_column(): string {
        return 'name';
    }

    protected function get_strings_table_name(): string {
        return 'media__content__tag__strings';
    }

    protected function get_strings_table_tpl(): string {
        return '';
    }

    protected function get_strings_table_key(): string {
        return 'id';
    }

}
