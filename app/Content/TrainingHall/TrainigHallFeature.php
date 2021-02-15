<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\TrainingHall;

/**
 * Description of TrainigHallFeature
 *
 * @author eve
 * @property string $image
 * @property string $name
 * @property string $value
 * @property bool $valid
 */
class TrainigHallFeature implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $image;

    /** @var string */
    protected $name;

    /** @var string */
    protected $value;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__image() {
        return $this->image;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__value() {
        return $this->value;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->image && $this->name ? true : false;
    }

    //</editor-fold>


    public function __construct(array $data = null) {
        if ($data) {
            $this->import_props($data);
        }
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data = null) {
        return new static($data);
    }

    /**
     * @param string $in
     * @return TrainingHallFeature[]
     */
    public static function from_json_array(string $in): array {
        $result = [];
        try {            
            $ina = \Filters\FilterManager::F()->apply_chain($in, ["NEString", "JSONString", "NEArray", "DefaultNUll"]);
            if ($ina) {
                foreach ($ina as $inr) {
                    try {
                        if (is_array($inr)) {
                            $item = static::F($inr);
                            $item && $item->valid ? $result[] = $item : false;                
                        }
                    } catch (\Throwable $er) {                        
                    }
                }
                
            }
        } catch (\Throwable $e) {
            throw $e;
        }
        return $result;
    }

    protected function t_common_import_get_filters(): array {
        return[
            'image' => ['Trim', 'NEString', 'DefaultNull'],
            'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'value' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

}
