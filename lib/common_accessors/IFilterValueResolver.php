<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common_accessors;

/**
 * Интерфейс для разрешения ошибок фильтрации
 * Используется трейтом TCommonImport
 */
interface IFilterValueResolver {

    /**
     * 
     * вернет значение заменяющее некоректное или исключение.
     * если вернет исключение - оно будет выкинуто
     * @param string $prop
     * @param \Filters\Value $value
     * @return mixed|\Exception
     */
    public function resolve_value(string $prop, \Filters\Value $value);
}
