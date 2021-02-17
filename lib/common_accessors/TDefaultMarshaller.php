<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

trait TDefaultMarshaller {

    public function marshall() {
        $result = [];
        $props_to_marshall = $this->t_default_marshaller_get_marshallable_props();
        $this->t_default_marshaller_on_props_to_marshall($props_to_marshall);
        foreach ($props_to_marshall as $prop_name) {
            $result[$prop_name] = $this->t_default_marshaller_marshall_property($prop_name);
        }
        $this->t_default_marshaller_on_marshall_done($result);
        return $result;
    }

    /**
     * returns names of props to marshall
     * @return string[]
     */
    protected function t_default_marshaller_get_marshallable_props(): array {
        $props = get_object_vars($this);
        $props_keys = array_keys($props);
        return array_combine($props_keys, $props_keys);
    }

    /**
     * override to filter props to marshall or add some virtual props
     * @param string[] $props
     */
    protected function t_default_marshaller_on_props_to_marshall(array &$props) {
        
    }

    /**
     * override to prepare result before it returns from marshall
     * @param array $result
     */
    protected function t_default_marshaller_on_marshall_done(array &$result) {
        
    }

    /**
     * example custom export method
     * @return type
     */
    protected function t_default_marshaller_export_property_example_property() {
        return null;
    }

    /**
     * export one property scalar or vector type (or object if it is IMarshall)
     * if property is object but not IMarshall implementor - export as cast to array
     * @param string $prop
     * @return type
     */
    protected function t_default_marshaller_marshall_property(string $prop) {
        $test_fn = "t_default_marshaller_export_property_{$prop}";
        if (method_exists($this, $test_fn)) {
            return $this->$test_fn();
        }
        if (isset($this->$prop)) {
            if (is_object($this->$prop) && ($this->$prop instanceof IMarshall)) {
                return $this->$prop->marshall();
            } else if (is_array($this->$prop)) {
                return $this->t_default_marshaller_marshall_array($this->$prop);
            } else if (is_scalar($this->$prop)) {
                return $this->$prop;
            } else if (is_object($this->$prop)) {
                $t = (array) $this->$prop;
                return $this->t_default_marshaller_marshall_array($t);
            }
        }
        return null;
    }

    /**
     * marshall array
     * @param array $d
     * @return Array
     */
    protected function t_default_marshaller_marshall_array(array $d): array {
        $r = [];
        foreach ($d as $key => $value) {
            if (is_object($value) && ($value instanceof IMarshall)) {
                $r[$key] = $value->marshall();
            } else if (is_object($value)) {
                $r[$key] = $this->t_default_marshaller_marshall_array((array) $value);
            } else if (is_array($value)) {
                $r[$key] = $this->t_default_marshaller_marshall_array($value);
            } else if (is_scalar($value)) {
                $r[$key] = $value;
            } else {
                $r[$key] = null;
            }
        }
        return $r;
    }

}
