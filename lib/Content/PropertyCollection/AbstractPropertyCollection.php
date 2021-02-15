<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\PropertyCollection;

/**
 * @property array $kvs
 */
abstract class AbstractPropertyCollection implements IPropertyCollection {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    /** @var DefaultPropertyItem[] */
    protected $items = null;

    /** @var DefaultPropertyItem[] */
    protected $index = null;

    public final function get_table_name(): string {
        return static::static_get_table_name();
    }

    public final function get_id_field(): string {
        return static::static_get_id_field();
    }

    protected final function __construct() {
        $this->items = [];
        $this->index = [];
    }

    /**
     * 
     * @return \Content\PropertyCollection\AbstractPropertyCollection
     */
    public static function F(): IPropertyCollection {
        return new static();
    }

    public function load_from_datamap(\DataMap\IDataMap $source): IPropertyCollection {
        $all = $source->get_all_cloned();
        $ns = [];
        foreach ($source as $key => $value) {
            if ($key && $value) {
                $prop = DefaultPropertyItem::F();
                $prop->set_up($key, $value, 0);
                $prop->valid ? $ns[$prop->name] = $prop : 0;
            }
        }
        $this->index = $ns;
        $this->items = array_values($this->index);
        return $this;
    }

    public function load_from_array(array $source): IPropertyCollection {
        $ns = [];
        foreach ($source as $key => $value) {
            if ($key && $value) {
                $prop = DefaultPropertyItem::F();
                $prop->set_up($key, $value);
                $prop->valid ? $ns[$prop->property_name] = $prop : 0;
            }
        }
        $this->index = $ns;
        $this->items = array_values($this->index);
        return $this;
    }

    public function load_from_object_array(array $source): IPropertyCollection {
        $ns = [];
        foreach ($source as $object_value) {
            if (is_array($object_value)) {
                $property = DefaultPropertyItem::F($object_value);
                $property->valid ? $ns[$property->name] = $property : 0;
            }
        }
        $this->index = $ns;
        $this->items = array_values($this->index);
        return $this;
    }

    public function load_from_json_object_array(string $json): IPropertyCollection {
        $jsa = \Filters\FilterManager::F()->apply_chain($json, ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultEmptyArray']);
        return $this->load_from_object_array($jsa);
    }

    protected function get_input_filters() {
        return [
            'property_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'property_value' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'sort' => ['Int', 'Default0'],
        ];
    }

    public function load_from_database($key_value, \DB\IDBAdapter $adapter = null): IPropertyCollection {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $query = "SELECT property_name,property_value,sort FROM `{$this->get_table_name()}` WHERE `{$this->get_id_field()}`=:P;";
        $raw_rows = $adapter->queryAll($query, [":P" => $key_value]);
        return $this->load_from_object_array($raw_rows);
    }

    public function save(\DB\SQLTools\SQLBuilder $builder, string $tmp_var): IPropertyCollection {
        $builder->inc_counter();
        $builder->push("DELETE FROM `{$this->get_table_name()}` WHERE `{$this->get_id_field()}` = {$tmp_var};");
        $b = $builder;
        $ic = 0;
        $inserts = [];
        $params = [];
        foreach ($this->items as $item) {
            if ($item->valid) {
                $inserts[] = "({$tmp_var},:P{$b->c}_i_{$ic}n,:P{$b->c}_i_{$ic}v,:P{$b->c}_i_{$ic}s)";
                $params[":P{$b->c}_i_{$ic}n"] = $item->key;
                $params[":P{$b->c}_i_{$ic}v"] = $item->value;
                $params[":P{$b->c}_i_{$ic}s"] = $item->sort;
                $ic++;
            }
        }
        if (count($inserts)) {
            $builder->push("INSERT INTO `{$this->get_table_name()}` (`{$this->get_id_field()}`,property_name,property_value,sort) VALUES "
                    . implode(",", $inserts) . " ON DUPLICATE KEY UPDATE property_value=VALUES(property_value),sort=VALUES(sort);");
            $builder->push_params($params);
        }
        $builder->inc_counter();
        return $this;
    }

    public function exists(string $key): bool {
        return array_key_exists($key, $this->index) ? true : false;
    }

    public function get(string $key, $default = null) {
        return array_key_exists($key, $this->index) ? $this->index[$key]->value : $default;
    }

    public function get_all_cloned(): array {
        return array_merge([], $this->index);
    }

    public function get_filtered_def(string $key, array $filters = [], \Filters\IParamPool $parampool = null) {
        return $this->get_filtered($key, $filters, $parampool && $parampool->has_params_for_property() ? $parampool->get_param_set_for_property() : null);
    }

    public function get_filtered(string $key, array $filters = [], \Filters\IFilterParamSet $params = null) {
        return \Filters\FilterManager::F()->apply_chain($this->get($key), $filters, $params);
    }

    public function rebind(array &$source): \DataMap\IDataMap {
        \DataMap\DataMapError::RF("`%s` is not rebindable", get_called_class());
    }

    public function remove(string $key): \DataMap\IDataMap {
        if (array_key_exists($key, $this->index)) {
            unset($this->index[$key]);
            $this->items = array_values($this->index);
        }
        return $this;
    }

    public function set(string $key, $value): \DataMap\IDataMap {
        if (!array_key_exists($key, $this->index)) {
            $new_prop = DefaultPropertyItem::F();
            $new_prop->set_up($key, $value);
            if ($new_prop->valid) {
                $this->index[$new_prop->property_name] = $new_prop;
                $this->items = array_values($this->index);
            }
        } else {
            $this->index[$key]->set_value($value);
        }
        return $this;
    }

    public function marshall() {
        return $this->t_default_marshaller_marshall_array($this->items);
    }

    public function __sleep() {
        return ['index'];
    }

    public function __wakeup() {
        $this->items = array_values($this->index);
    }

    protected function reindex() {
        $this->index = [];
        foreach ($this->items as $item) {
            $this->index[$item->property_name] = $item;
        }
    }

    protected function __get__kvs() {
        $r = [];
        foreach ($this->items as $item) {
            $r[$item->key] = $item->value;
        }
        return $r;
    }

    public static function load_join(string $link_table, string $link_field = 'id'): array {
        $mtable = static::static_get_table_name();
        $mid = static::static_get_id_field();
        $query = "SELECT B.* FROM `{$link_table}` A JOIN `{$mtable}` B ON(A.`{$link_field}`=B.`{$mid}`) ORDER BY B.sort,B.property_name;";
        $rows = \DB\DB::F()->queryAll($query);
        $result = [];
        $mprefix = static::static_get_load_join_key_prefix();
        foreach ($rows as $row) {
            $key = "{$mprefix}{$row[$mid]}";
            array_key_exists($key, $result) ? 0 : $result[$key] = [];
            $result[$key][] = $row;
        }
        return $result;
    }

    public static function static_get_id_field(): string {
        return 'id';
    }

    abstract public static function static_get_table_name(): string;

    public static function static_get_load_join_key_prefix(): string {
        return "P";
    }

    /**
     * читает данные из входного массива вида ["Pxxx"=>[["property_name"=>'',"property_value"=>.....]]]
     * для массовой загрузки с разбиением по ключам владельца
     * @param array $values_all
     * @param mixed $id_to_load
     * @return \Content\PropertyCollection\IPropertyCollection
     */
    public function import_raw(array $values_all, $id_to_load): IPropertyCollection {
        $mprefix = static::static_get_load_join_key_prefix();
        $key = "{$mprefix}{$id_to_load}";
        if (array_key_exists($key, $values_all) && is_array($values_all[$key]) && count($values_all[$key])) {
            $this->load_from_object_array($values_all[$key]);
        }
        return $this;
    }

}
