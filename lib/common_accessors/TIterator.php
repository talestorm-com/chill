<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

/**
 * @property integer $length
 * @property integer $count
 */
trait TIterator {

    protected function t_iterator_get_internal_iterable_name(): string {
        return 'items';
    }

    public function count(): int {
        $pn = $this->t_iterator_get_internal_iterable_name();
        return count($this->$pn);
    }

    protected function __get__count(): int {
        $pn = $this->t_iterator_get_internal_iterable_name();
        return count($this->$pn);
    }

    protected function __get__length(): int {
        $pn = $this->t_iterator_get_internal_iterable_name();
        return count($this->$pn);
    }

    public function current() {
        $pn = $this->t_iterator_get_internal_iterable_name();
        return current($this->$pn);
    }

    public function key() {
        $pn = $this->t_iterator_get_internal_iterable_name();
        return key($this->$pn);
    }

    public function next() {
        $pn = $this->t_iterator_get_internal_iterable_name();
        next($this->$pn);
    }

    public function rewind() {
        $pn = $this->t_iterator_get_internal_iterable_name();
        reset($this->$pn);
    }

    public function valid(): bool {
        $pn = $this->t_iterator_get_internal_iterable_name();
        return array_key_exists(key($this->$pn), $this->$pn);
    }

}
