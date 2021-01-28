<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_product_price($params, $template) {
    $ap = is_array($params) ? $params : [];
    $pm = DataMap\CommonDataMap::F()->rebind($ap);
    $product = $pm->get('product');
    $render_old = $pm->get_filtered('old', ['Boolean', 'DefaultFalse']);
    $default_text = $pm->get_filtered('default', ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
    if ($product && is_object($product) && (($product instanceof \DataModel\Product\Model\ProductModel) || (($product instanceof \DataModel\Product\Model\ProductCross)) ) ) {
        if (\Auth\Auth::F()->is_authentificated()) {
            if(DataMap\GPDataMap::F()->exists("debug_users")){
                var_dump(\Auth\Auth::F()->get_user_info()->is_dealer);
                var_dump(\Auth\Auth::F()->is(Auth\Roles\RoleDealer::class));
            }
            if (\Auth\Auth::F()->get_user_info()->is_dealer && \Auth\Auth::F()->is(Auth\Roles\RoleDealer::class)) {
                if ($render_old) {
                    return $product->gross_old ? number_format($product->gross_old, 0, ".", ' ') : '';
                } else {
                    return $product->gross ? number_format($product->gross, 0, '.', ' ') : $default_text;
                }
            }
        }
        if ($render_old) {
            return $product->retail_old ? number_format($product->retail_old, 0, ".", ' ') : '';
        } else {
            return $product->retail ? number_format($product->retail, 0, '.', ' ') : $default_text;
        }
    }
    return '';
}
