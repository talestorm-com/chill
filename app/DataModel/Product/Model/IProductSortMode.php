<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataModel\Product\Model;

interface IProductSortMode {

    CONST SM_DEFAULT = "SM_DEFAULT";
    CONST SM_REVERSE = "SM_REVERSE";
    CONST SM_DISCOUNT_RETAIL = "SM_DISCOUNT_RETAIL";
    CONST SM_DISCOUNT_RETAIL_REV = "SM_DISCOUNT_RETAIL_REV";
    CONST SM_DISCOUNT_GROSS = "SM_DISCOUNT_GROSS";
    CONST SM_DISCOUNT_GROSS_REV = "SM_DISCOUNT_GROSS_REV";
    CONST SM_PRICE_RETAIL = "SM_PRICE_RETAIL";
    CONST SM_PRICE_RETAIL_REV = "SM_PRICE_RETAIL_REV";
    CONST SM_PRICE_GROSS = "SM_PRICE_GROSS";
    CONST SM_PRICE_GROSS_REV = "SM_PRICE_GROSS_REV";
    CONST MODES = [
        self::SM_DEFAULT => "A.sort,A.id DESC",
        self::SM_REVERSE => "A.sort DESC,A.id",
        self::SM_DISCOUNT_RETAIL => "P.discount_retail,A.sort,A.id DESC",
        self::SM_DISCOUNT_RETAIL_REV => "P.discount_retail DESC,A.sort DESC,A.id",
        self::SM_DISCOUNT_GROSS => "P.discount_gross,A.sort,A.id DESC",
        self::SM_DISCOUNT_GROSS_REV => "P.discount_gross DESC,A.sort DESC,A.id",
        self::SM_PRICE_RETAIL => "P.retail,A.sort,A.id DESC",
        self::SM_PRICE_RETAIL_REV => "P.retail DESC,A.sort DESC,A.id",
        self::SM_PRICE_GROSS => "P.gross,A.sort,A.id DESC",
        self::SM_PRICE_GROSS_REV => "P.gross DESC,A.sort DESC,A.id",
    ];

}
