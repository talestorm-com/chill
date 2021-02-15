<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Order;

/**
 * @property int $id
 * @property int $user_id
 * @property \DateTime $created
 * @property int $reserve
 * @property int $shop_id
 * @property string $shop_name
 * @property string $user_name
 * @property string $user_phone
 * @property string $user_email
 * @property bool $dealer
 * @property string $delivery
 * @property OrderItemCollection $items
 * @property int $status
 * @property double $amount
 * @property boolean $valid
 * @property string $comment
 */
class Order implements \Countable, \Iterator, \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var int */
    protected $id;

    /** @var int */
    protected $user_id;

    /** @var \DateTime */
    protected $created;

    /** @var int */
    protected $reserve;

    /** @var int */
    protected $shop_id;

    /** @var string */
    protected $shop_name;

    /** @var string */
    protected $user_name;

    /** @var string */
    protected $user_phone;

    /** @var string */
    protected $user_email;

    /** @var bool */
    protected $dealer;

    /** @var string */
    protected $delivery;

    /** @var OrderItemCollection */
    protected $items;

    /** @var int */
    protected $status;

    /** @var double */
    protected $amount;
    
    protected $comment;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__user_id() {
        return $this->user_id;
    }

    /** @return \DateTime */
    protected function __get__created() {
        return $this->created;
    }

    /** @return int */
    protected function __get__reserve() {
        return $this->reserve;
    }

    /** @return int */
    protected function __get__shop_id() {
        return $this->shop_id;
    }

    /** @return string */
    protected function __get__shop_name() {
        return $this->shop_name;
    }

    /** @return string */
    protected function __get__user_name() {
        return $this->user_name;
    }

    /** @return string */
    protected function __get__user_phone() {
        return $this->user_phone;
    }

    /** @return string */
    protected function __get__user_email() {
        return $this->user_email;
    }

    /** @return bool */
    protected function __get__dealer() {
        return $this->dealer;
    }

    /** @return string */
    protected function __get__delivery() {
        return $this->delivery;
    }

    /** @return OrderItemCollection */
    protected function __get__items() {
        return $this->items;
    }

    /** @return int */
    protected function __get__status() {
        return $this->status;
    }

    /** @return double */
    protected function __get__amount() {
        return $this->amount;
    }

    protected function __get__valid() {
        return ($this->id && $this->created && $this->user_name) ? true : false;
    }
    
    protected function __get__comment(){
        return $this->comment;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="interfaces">
    public function count() {
        return count($this->items);
    }

    public function current() {
        return $this->items->current();
    }

    public function key() {
        return $this->items->key();
    }

    public function next() {
        return $this->items->next();
    }

    public function rewind() {
        return $this->items->rewind();
    }

    public function valid() {
        return $this->items->valid();
    }

    //</editor-fold>



    public function __construct(int $id = null) {
        $this->items = new OrderItemCollection();
        if ($id) {
            $this->load($id);
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

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load(int $id, \DB\IDBAdapter $adapter = null) {
        $adapter = $adapter ? $adapter : \DB\DB::F();
        $query = "SELECT A.*,B.status,C.amount,M.comment
            FROM clientorder A LEFT JOIN clientorder__status B ON(B.id=A.id)
            LEFT JOIN clientorder__total C ON(C.id=A.id)
            LEFT JOIN clientorder__comment M ON(M.id=A.id)
            WHERE A.id=:P
            ";
        $row = $adapter->queryRow($query, [":P" => $id]);
        $row = is_array($row) ? $row : [];
        $this->import_props($row);
        if ($this->__get__valid()) {
            $this->items->load($id, $adapter);
        }
        return $this;
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ["IntMore0", "DefaultNull"], //int
            'user_id' => ["IntMore0", "DefaultNull"], //int
            'created' => ["DateMatch", "DefaultNull"], //\DateTime
            'reserve' => ["IntMore0", "Default0"], //int
            'shop_id' => ["IntMore0", "DefaultNull"], //int
            'shop_name' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'user_name' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'user_phone' => ["Strip", "Trim", "NEString", "PhoneMatch", "DefaultNull"], //string
            'user_email' => ["Strip", "Trim", "NEString", "EmailMatch", "DefaultNull"], //string
            'dealer' => ["Boolean", "DefaultFalse"], //bool
            'delivery' => ["Strip", "Trim", "NEString", "DefaultNull"], //string
            'status' => ["IntMore0", "Default0"], //int
            'amount' => ["Float", "Default0"], //double
            'comment'=>["Strip","Trim","NEString","DefaultEmptyString"]
        ];
    }
    
    
    protected function t_default_marshaller_export_property_created() {
        return $this->created?$this->created->format('d.m.Y H:i'):null;
    }

}
