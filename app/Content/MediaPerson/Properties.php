<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaPerson;

/**
 * Description of MediaPersonProperties
 *
 * @author eve
 */
class Properties extends \Content\PropertyCollection\AbstractPropertyCollection {

    //put your code here
    public static function static_get_table_name(): string {
        return "media__content__actor__properties";
    }

}
