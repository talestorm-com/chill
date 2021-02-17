<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\PropertyCollection;

interface IPropertyCollection extends \common_accessors\IMarshall, \DataMap\IDataMap {

    public function get_table_name(): string;

    public function get_id_field(): string;

    public static function F(): IPropertyCollection;

    public function load_from_datamap(\DataMap\IDataMap $source): IPropertyCollection;

    public function load_from_array(array $source): IPropertyCollection;

    public function load_from_object_array(array $source): IPropertyCollection;

    public function load_from_json_object_array(string $json): IPropertyCollection;

    public function load_from_database($key_value, \DB\IDBAdapter $adapter = null): IPropertyCollection;

    public function import_raw(array $values_all, $id_to_load): IPropertyCollection;

    public function save(\DB\SQLTools\SQLBuilder $builder, string $tmp_var): IPropertyCollection;

    public static function load_join(string $link_table, string $link_field = 'id'): array;

    public static function static_get_table_name(): string;

    public static function static_get_id_field(): string;

    public static function static_get_load_join_key_prefix(): string;
}
