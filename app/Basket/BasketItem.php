<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Basket;

/**
 * // если оставить только одну корзнину, то нужны артикулы, наименования, наименования цветов,ценники и ключ зависимости кеша
 * // и все равно нужна будет процедура перзагрузки на случай сбоя кеша, подумать до завтра. корзину завтра надо доделать.
 * @property int $id            идентификатор товара 
 * @property int $qty           количество 
 * @property string $color_id   идентификатор цвета, товары разных цветов и размеров занимают разные строчки
 * @property string $sizes      <b>значения</b> размеров, выстроенные в алфавитном порядке
 * @property int[] $size_list   <b>идентификаторы</b> размеров (мвссов)
 * @property string $hash
 * @property string $product_name
 * @property string $product_article
 * @property string $color_name
 * @property string $color_html
 * @property double $price_gross
 * @property double $price_retail
 * @property bool $data_valid
 * @property string $default_image
 * @property string $size_hash   часть хеша, построенная по идентам размеров
 */
class BasketItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="properties">
    /** @var int */
    protected $id;

    /** @var int */
    protected $qty;

    /** @var string */
    protected $color_id;

    /** @var string */
    protected $sizes;

    /** @var string */
    protected $hash;

    // свойства товара, ревалидируются при поломке зависимости кеша

    /** @var string */
    protected $product_name;

    /** @var string */
    protected $product_article;

    /** @var string */
    protected $color_name;

    /** @var double */
    protected $price_gross;

    /** @var double */
    protected $price_retail;

    /** @var string */
    protected $color_html;

    /** @var string */
    protected $default_image;

    /** @var int[] */
    protected $size_list;

    /** @var string */
    protected $size_hash;
    protected $size_id_translations; // транслятор размера

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    protected function __get__size_hash() {
        return $this->size_hash;
    }

    protected function __get__size_list() {
        return $this->size_list;
    }

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__qty() {
        return $this->qty;
    }

    /** @return string */
    protected function __get__color_id() {
        return $this->color_id;
    }

    /** @return string */
    protected function __get__sizes() {
        return $this->sizes;
    }

    protected function __get__hash() {
        if (!$this->hash) {
            $this->hash = md5(implode("|", [
                $this->id,
                $this->color_id ? $this->color_id : 'N',
                $this->size_hash,
            ]));
        }
        return $this->hash;
    }

    protected function create_sizes_hash() {
        $as = explode(",", $this->sizes);
        $cas = [];
        foreach ($as as $asi) {
            $asic = \Helpers\Helpers::NEString(trim($asi), null);
            $asic ? $cas[] = $asic : 0;
        }
        usort($cas, function($a, $b) {
            return strcmp($a, $b);
        });
        return md5(implode("|||", $cas));
    }

    /** @return string */
    protected function __get__product_name() {
        return $this->product_name;
    }

    /** @return string */
    protected function __get__product_article() {
        return $this->product_article;
    }

    /** @return string */
    protected function __get__color_name() {
        return $this->color_name;
    }

    /** @return double */
    protected function __get__price_gross() {
        return $this->price_gross;
    }

    /** @return double */
    protected function __get__price_retail() {
        return $this->price_retail;
    }

    /** @return string */
    protected function __get__color_html() {
        return $this->color_html;
    }

    protected function __get__data_valid() {
        return ($this->product_article && $this->product_name && ($this->color_id === null || ($this->color_html && $this->color_name))) ? true : false;
    }

    protected function __get__default_image() {
        return $this->default_image;
    }

    //</editor-fold>

    protected function reset_hash(): BasketItem {
        $this->hash = null;
        return $this;
    }

    public function set_color(string $color = null): BasketItem {
        $this->color_id = $color;
        $this->reset_hash();
        return $this;
    }

    public function set_sizes(string $size): BasketItem {
        $this->size_id = $size;
        $this->reset_hash();
        return $this;
    }

    public function set_qty(int $qty): BasketItem {
        $this->qty = max([1, $qty]);
        return $this;
    }

    public function increment(int $step = 1): BasketItem {
        return $this->set_qty($this->qty + $step);
    }

    public function decrement(int $step = 1): BasketItem {
        return $this->set_qty(max([$this->qty - $step, 1]));
    }

    //<editor-fold defaultstate="collapsed" desc="nat setters">
    protected function __set__color_id(string $color = null) {
        $this->set_color($color);
    }

    protected function __set__sizes(string $sizes) {
        \Errors\common_error::R("deprecated");
        $this->set_sizes($sizes);
    }

    protected function __set__qty(int $qty) {
        $this->set_qty($qty);
    }

    //</editor-fold>




    public function update_product_info(\DataModel\Product\Model\ProductModel $product = null) {
        if ($product) {
            $this->product_article = $product->safe_article;
            $this->product_name = $product->name;
            $this->default_image = $product->default_image;
            if ($this->color_id) {
                $color = $product->colors->get_by_uid($this->color_id, null);
                if ($color) {
                    $this->color_html = $color->html_color;
                    $this->color_name = $color->name;
                } else {
                    $this->color_name = null;
                    $this->color_html = null;
                }
            } else {
                $this->color_name = null;
                $this->color_html = null;
            }
            $this->price_gross = $product->safe_gross;
            $this->price_retail = $product->safe_retail;
        } else {
            $this->product_article = null;
            $this->product_name = null;
            $this->color_name = null;
            $this->color_html = null;
            $this->price_gross = null;
            $this->price_retail = null;
        }
    }

    /**
     * 
     * @param int $id
     * @param string $color_id
     * @param \DataModel\Product\Model\ProductSize[] $sizes
     * @param int $qty
     */
    public function __construct(int $id, string $color_id = null, array $sizes = null, int $qty = 1) {
        $this->id = $id;
        $this->color_id = $color_id;
        //$this->sizes = $sizes;
        $this->qty = $qty;
        $this->size_list = [];
        $nat_sizes = [];
        $this->size_id_translations = [];
        if ($sizes) {
            foreach ($sizes as $size) {
                $this->size_list[] = $size->id;
                $nat_sizes[] = $size->value;
                $this->size_id_translations["P{$size->id}"] = $size->value;
            }
        }
        usort($this->size_list, function($a, $b) {
            return $a - $b;
        });

        $this->size_hash = count($this->size_list) ? implode("##*##", $this->size_list) : "N";
        $cas = [];
        foreach ($nat_sizes as $asi) {
            $asic = \Helpers\Helpers::NEString(trim($asi), null);
            $asic ? $cas[] = $asic : 0;
        }
        usort($cas, function($a, $b) {
            return strcmp($a, $b);
        });
        $this->sizes = implode(", ", $cas);
    }

    /**
     * 
     * @param int $id
     * @param string $color_id
     * @param \DataModel\Product\Model\ProductSize[] $sizes
     * @param int $qty
     * @return \Basket\BasketItem
     */
    public static function F(int $id, string $color_id = null, array $sizes = null, int $qty = 1): BasketItem {
        return new static($id, $color_id, $sizes, $qty);
    }

    public function translate_size(int $size_id) {
        if (is_array($this->size_id_translations)) {
            $key = "P{$size_id}";
            if (array_key_exists($key, $this->size_id_translations)) {
                return $this->size_id_translations[$key];
            }
        }
        return null;
    }

}
