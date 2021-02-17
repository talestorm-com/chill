<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\TrainingHall\writer;

/**
 * Description of CommonWriter
 *
 * @author eve
 */
class CommonWriter {

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\TrainingHall\writer\CommonWriter
     */
    public static function F(): CommonWriter {
        return new static();
    }

    public function run(writer $w) {

        $writer_data = \Filters\FilterManager::F()->apply_filter_datamap($w->data_input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($writer_data);
        $this->create_sql($writer_data, $w);
    }

    protected function get_filters() {
        return [
            'id' => ['IntMore0', "DefaultNull"], //integer            
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'phone' => ['Strip', 'Trim', 'NEString', 'PhoneMatch', 'DefaultNull'], //string
            'address' => ['Strip', 'Trim', 'NEString'], //bool
            'lat' => ['Float',], //bool
            'lon' => ['Float',], //\DateTime            
            'default_image' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'features' => ['NEArray', 'DefaultEmptyArray'], //string                                 
        ];
    }

    protected function create_sql(array $data, writer $w) {
        $w->builder->push_params([
            ":P{$w->builder->c}name" => $data["name"],
            ":P{$w->builder->c}address" => $data["address"],
            ":P{$w->builder->c}phone" => $data["phone"],
            ":P{$w->builder->c}lat" => $data["lat"],
            ":P{$w->builder->c}lon" => $data["lon"],
            ":P{$w->builder->c}default_image" => $data["default_image"],
            ":P{$w->builder->c}features" => json_encode($data["features"]),
        ]);
        if ($data['id']) {
            $w->builder->push("SET {$w->temp_var} = :P{$w->builder->c}id;");
            $w->builder->push_param(":P{$w->builder->c}id", $data["id"]);
            $w->builder->push("UPDATE fitness__places SET name=:P{$w->builder->c}name,
                address=:P{$w->builder->c}address,
                phone=:P{$w->builder->c}phone,
                    lat=:P{$w->builder->c}lat,
                        lon=:P{$w->builder->c}lon,
                default_image=:P{$w->builder->c}default_image,
                features=:P{$w->builder->c}features
                WHERE id={$w->temp_var};    
            ");
        } else {
            $w->builder->push("INSERT INTO fitness__places( name,address,lat,lon,phone,default_image,features )
                VALUES(:P{$w->builder->c}name,:P{$w->builder->c}address,:P{$w->builder->c}lat,:P{$w->builder->c}lon,
                :P{$w->builder->c}phone,:P{$w->builder->c}default_image,:P{$w->builder->c}features);
                ");
            $w->builder->push("SET {$w->temp_var} = LAST_INSERT_ID();");
        }
        $w->builder->inc_counter();
    }

}
