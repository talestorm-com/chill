<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_product_discount($params, $template) {
    $ap = is_array($params) ? $params : [];
    $pm = DataMap\CommonDataMap::F()->rebind($ap);
    $product = $pm->get('product');
    if ($product && is_object($product) && ($product instanceof \DataModel\Product\Model\ProductModel)) {
        if (\Auth\Auth::F()->is_authentificated()) {
            if (\Auth\Auth::F()->get_user_info()->is_dealer && \Auth\Auth::F()->is(Auth\Roles\RoleDealer::class)) {
                return intval($product->discount_gross);
            }
        }
        return intval($product->discount_retail);
    }
    return '';
}
