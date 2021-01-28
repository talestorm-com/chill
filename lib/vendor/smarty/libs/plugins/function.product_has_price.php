<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_product_has_price($params, $template) {
    $ap = is_array($params) ? $params : [];
    $pm = DataMap\CommonDataMap::F()->rebind($ap);
    $product = $pm->get('product');
    $render_old = $pm->get_filtered('old', ['Boolean', 'DefaultFalse']);
    if ($product && is_object($product) && (($product instanceof \DataModel\Product\Model\ProductModel)|| ($product instanceof \DataModel\Product\Model\ProductCross) )) {
        if (\Auth\Auth::F()->is_authentificated()) {
            if (\Auth\Auth::F()->get_user_info()->is_dealer && \Auth\Auth::F()->is(Auth\Roles\RoleDealer::class)) {
                if ($render_old) {
                    return $product->gross_old && $product->gross_old > 0 ? true : false;
                } else {
                    return $product->gross && $product->gross > 0 ? true : false;
                }
            }
        }
        if ($render_old) {
            return $product->retail_old && $product->retail_old > 0 ? true : false;
        } else {
            return $product->retail && $product->retail > 0 ? true : false;
        }
    }
    return false;
}
