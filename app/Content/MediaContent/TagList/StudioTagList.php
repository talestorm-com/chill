<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\TagList;

/**
 * Description of StudioTagList
 *
 * @author eve
 */
class StudioTagList extends TagList {

    protected function get_key_column(): string {
        return 'media_id';
    }

    protected function get_language_column(): string {
        return '';
    }

    protected function get_language_link_mode(): int {
        return static::LANGUAGE_LINK_MODE_MULTABLE;
    }

    protected function get_linked_table(): string {
        return 'media__studio';
    }

    protected function get_linked_table_key_column(): string {
        return 'id';
    }

    protected function get_list_column(): string {
        return 'studio_id';
    }

    protected function get_list_table(): string {
        return 'media__content__studio__list';
    }

    protected function get_name_column(): string {
        return 'name';
    }

    protected function get_strings_table_name(): string {
        return '';
    }

    protected function get_strings_table_tpl(): string {
        return 'media__studio__strings__lang_%s';
    }

    protected function get_strings_table_key(): string {
        return 'id';
    }

}
