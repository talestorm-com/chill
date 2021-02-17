<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Filters\classes;

/**
 * Заменяет те сиволы, которые опасны для sql с прредположением что фильтруемое - строка
 * и будет сипользоваться для прямого матча или вставки (не ля лайка)
 */
class SQLSafeStringFilter extends \Filters\AbstractFilter {

    protected function do_apply($input_value, \Filters\IFilterParams $params = null) {
        return is_string($input_value) ? str_ireplace(['\'', '"', '`'], '', $input_value) : '';
    }

}
