<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Training;

/**
 * Description of Training
 *
 * @property int $id
 * @property int $trainer_id
 * @property int $user_id
 * @property int $place_id
 * @property string $place_name
 * @property string $trainer_name
 * @property string $client_name
 * @property \DateTime $datum
 * @property int $start
 * @property int $end
 * @property int $duration
 * @property int $state
 * @property \DateTime $start_moment
 * @property \DateTime $end_moment
 * @property bool $valid
 * @author eve
 */
class Training extends \Content\Content {

    use \common_accessors\TCommonImport;
    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $trainer_id;

    /** @var int */
    protected $user_id;

    /** @var int */
    protected $place_id;

    /** @var string */
    protected $place_name;

    /** @var string */
    protected $trainer_name;

    /** @var string */
    protected $client_name;

    /** @var \DateTime */
    protected $datum;

    /** @var int */
    protected $start;

    /** @var int */
    protected $end;

    /** @var int */
    protected $duration;

    /** @var int */
    protected $state;

    /** @var \DateTime */
    protected $start_moment;

    /** @var \DateTime */
    protected $end_moment;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__trainer_id() {
        return $this->trainer_id;
    }

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return int */
    protected function __get__place_id() {
        return $this->place_id;
    }

    /** @return string */
    protected function __get__place_name() {
        return $this->place_name;
    }

    /** @return string */
    protected function __get__trainer_name() {
        return $this->trainer_name;
    }

    /** @return string */
    protected function __get__client_name() {
        return $this->client_name;
    }

    /** @return \DateTime */
    protected function __get__datum() {
        return $this->datum;
    }

    /** @return int */
    protected function __get__start() {
        return $this->start;
    }

    /** @return int */
    protected function __get__end() {
        return $this->end;
    }

    /** @return int */
    protected function __get__duration() {
        return $this->duration;
    }

    /** @return int */
    protected function __get__state() {
        return $this->state;
    }

    /** @return \DateTime */
    protected function __get__start_moment() {
        return $this->start_moment;
    }

    /** @return \DateTime */
    protected function __get__end_moment() {
        return $this->end_moment;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->id && $this->trainer_id && $this->user_id && $this->place_id && $this->place_name && $this->trainer_name && $this->datum && ($this->start !== null);
    }
    
    
    public $var_raw = null;

    //</editor-fold>


    public function __construct(int $id = null) {
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load(int $id) {        
        $query = "SELECT * FROM fitness__trainer__buisy WHERE id=:P";
        $row = \DB\DB::F()->queryRow($query, [":P" => $id]);
        $this->var_raw=$row;
        $this->import_props(is_array($row) ? $row : []);
        
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'], //int
            'trainer_id' => ['IntMore0', 'DefaultNull'], //int
            'user_id' => ['IntMore0', 'DefaultNull'], //int
            'place_id' => ['IntMore0', 'DefaultNull'], //int
            'place_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'trainer_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'client_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string
            'datum' => ['DateMatch', 'DefaultNull'], //\DateTime
            'start' => ['AnyInt', 'DefaultNull'], //int
            'end' => ['IntMore0', 'DefaultNull'], //int
            'state' => ['AnyInt', 'Default0'], //int
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id) {
            if ($this->datum) {
                $this->datum->setTime(0, 0, 0, 0);
                $dow_offset = (intval($this->datum->format('N')) - 1) * 86400;
                $start_offset = $this->start - $dow_offset;
                $end_offset = $this->end - $dow_offset;
                $this->duration = $this->end - $this->start;
                $this->start_moment = new \DateTime();
                $this->start_moment->setTimestamp($this->datum->getTimestamp());
                $this->start_moment->add(new \DateInterval("PT{$start_offset}S"));
                $this->end_moment = new \DateTime();
                $this->end_moment->setTimestamp($this->datum->getTimestamp());
                $this->end_moment->add(new \DateInterval("PT{$end_offset}S"));
            }
        }
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id = null) {
        return new static($id);
    }
    
    
    public function create_confirm_hash(){
        $ssl = \OpenSSL\OpenSSL::F("train_confirm", 512);
        return $ssl->sign($this->get_data_for_sign());
    }
    
    protected function get_data_for_sign() {
        $data_array = [
            $this->id,
            $this->trainer_id,
            $this->user_id,$this->place_id,            
        ];
        return implode("*", $data_array);
    }

    public function check_confirm_hash(string $hash) {
        $ssl = \OpenSSL\OpenSSL::F("train_confirm", 512);
        if ($ssl->checkSign($this->get_data_for_sign(), $hash)) {
            return true;
        }
        return false;
    }

}
