<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\ClientPackage;

/**
 * Description of Properies
 *
 * @author eve
 */
class Properies extends \Content\PropertyCollection\AbstractPropertyCollection {

    public static function static_get_table_name(): string {
        return "fitness__package__properties";
    }

}
