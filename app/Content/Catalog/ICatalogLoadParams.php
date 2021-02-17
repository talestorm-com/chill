<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Catalog;

interface ICatalogLoadParams {

    const SORT_DEFAULT = "SORT_NATURAL";
    const SORT_NATURAL = "SORT_NATURAL";
    const SORT_NATURAL_REV = "SORT_NATURAL_REV";
    const SORT_PRICE = "SORT_PRICE";
    const SORT_PRICE_REV = "SORT_PRICE_REV";
    const SORT_OPTIONS = [
        self::SORT_NATURAL => ["info" => "by_default", "order" => "P.sort,P.id"],
        self::SORT_NATURAL_REV => ["info" => "by_default_rev", "order" => "P.sort DESC,P.id DESC"],
        self::SORT_PRICE => ["info" => "by_price", "order" => "PP.retail,P.sort,P.id"],
        self::SORT_PRICE_REV => ["info" => "by_default_rev", "order" => "PP.retail DESC,P.sort DESC,P.id DESC"],
    ];

}
