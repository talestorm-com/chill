<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

class BasketController extends AbstractFrontendController {

    protected function API_get_product_info() {
        $product_id = $this->GP->get_filtered("product_id", ["IntMore0", "DefaultNull"]);
        $product_alias = $this->GP->get_filtered("alias", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $product_alias || $product_id ? 0 : \Errors\common_error::R("invalid request");
        $product = \Content\Product\ProductInfoRequest::F($product_id, $product_alias);
        $product->valid ? 0 : \Errors\common_error::R("product not found");
        $this->out->add("product", $product);
    }

    protected function API_get_product_storage_info() {
        $product_id = $this->GP->get_filtered("product_id", ["IntMore0", "DefaultNull"]);
        if ($product_id) {
            $result = \Basket\StorageLoader::F()->get($product_id);
            $this->out->add("wh", [$result]);
        }
        $this->API_get_shop_list();
    }

    protected function API_get_products_storage_info() {
        $ids = $this->GP->get_filtered("ids", ["NEArray", 'ArrayOfInt', "DefaultNull"], \Filters\params\ArrayParamBuilder::B([
                    "ArrayOfInt" => [
                        "min" => 1
                    ]
                        ], TRUE)->get_param_set_for_property());
        if ($ids) {
            $result = \Basket\StorageLoader::F()->get_array($ids);
            $this->out->add("wh", array_values($result));
        }
        $this->API_get_shop_list();
    }

    protected function API_get_shop_list() {
        $this->out->add('offline', \Basket\OfflineShopList::C());
    }

    protected function API_pm_product_info() {
        $product_id = $this->GP->get_filtered("product_id", ["IntMore0", "DefaultNull"]);
        if ($product_id) {
            $product = \Content\Product\ProductInfoRequest::F($product_id, null);
            $product->valid ? 0 : \Errors\common_error::R("product not found");
            $this->out->add("product", $product);
            $this->out->add("warehouse", [\Basket\StorageLoader::F()->get($product_id)]);
        }
        $this->out->add('offline', \Basket\OfflineShopList::C());
    }

    protected function API_get_user_info() {
        if ($this->auth->authenticated) {
            $this->out->add('user_info', [
                'id' => $this->auth->user_info->id,
                'name' => trim("{$this->auth->user_info->name} {$this->auth->user_info->eldername} {$this->auth->user_info->family}"),
                'phone' => \Helpers\Helpers::NEString($this->auth->user_info->phone),
                'email' => \Helpers\Helpers::NEString($this->auth->user_info->login),
                'split_name' => $this->auth->user_info->name,
                'split_family' => $this->auth->user_info->family,
                'split_eldername' => $this->auth->user_info->eldername,
                'addresses' => \Basket\UserAddressList::C($this->auth->id),
            ]);
        }
    }

    protected function API_reserve() {
        // все проверить и отправить почту
        $raw_data = $this->GP->get_filtered("data", ["Strip", 'Trim', "NEString", "JSONString", 'NEArray', "DefaultNull"]);
        $raw_data ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($raw_data, [
            "product_id" => ["IntMore0"],
            "color_id" => ["Strip", "Trim", "NEString", "DefaultNull"],
            'sizes' => ["NEArray", "ArrayOfInt", "DefaultNull"],
            'phone' => ["Strip", "Trim", "NEString", "PhoneMatch"],
            'name' => ['Strip', 'Trim', 'NEString'],
            'email' => ["Strip", 'Trim', "NEString", "EmailMatch"],
            'shop_id' => ["IntMore0"]
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $product = \DataModel\Product\Model\ProductModel::F($data['product_id']);
        $selected_color = null;
        if ($product->has_colors && !$data['color_id']) {
            \Errors\common_error::R("Не указан цвет");
        } else if (!$product->has_colors && $data['color_id']) {
            \Errors\common_error::R("У этого товара не найдено такого цвета");
        } else if ($product->has_colors && $data['color_id']) {
            $selected_color = $product->colors->get_by_guid($data['color_id']);
            if (!$selected_color) {
                \Errors\common_error::R("У этого товара не найдено такого цвета");
            }
        }
        $selected_sizes = []; /* @var $selected_sizes \DataModel\Product\Model\ProductSize[] */

        if ($product->has_sizes && !$data['sizes']) {
            \Errors\common_error::R("не выбран размер");
        } else if (!$product->has_sizes && $data['sizes']) {
            \Errors\common_error::R("У этого товара отстутсвуют выбраные размеры");
        } else if ($product->has_sizes && $data['sizes']) {
            foreach ($data['sizes'] as $size_id) {
                $size = $product->sizes->get_by_id($size_id);
                $size ? 0 : \Errors\common_error::R("У этого товара отстутствует выбранный размер");
                $selected_sizes[] = $size;
            }
        }

        $shop = \Basket\OfflineShopList::C()->get_by_id($data['shop_id']);
        $shop ? 0 : \Errors\common_error::R("Указанного магазина не существует!");
        $av_list = \Basket\StorageLoader::F()->get($product->id);
        $use_sizes = count($selected_sizes);
        if ($use_sizes) {
            $av_by_size = [];
            foreach ($av_list as $sr) {/* @var $sr \Basket\StorageResultItem */
                if ($sr->storage_id === $shop->storage_id) {
                    if ($selected_color === null || $selected_color->guid === $sr->color) {
                        $key = implode("", ["P", $sr->size ? $sr->size : "N"]);
                        if (!array_key_exists($key, $av_by_size)) {
                            $av_by_size[$key] = 0;
                        }
                        $av_by_size[$key] += $sr->qty;
                    }
                }
            }
            foreach ($selected_sizes as $size) {
                $key = "P{$size->id}";
                if (!(array_key_exists($key, $av_by_size) && $av_by_size[$key])) {
                    \Errors\common_error::RF("Размер \"%s\" отстутствует в выбранном магазине", $size->value);
                }
            }
        } else {
            $q = 0;
            foreach ($av_list as $sr) {/* @var $sr \Basket\StorageResultItem */
                if ($sr->storage_id === $shop->storage_id) {
                    if ($selected_color === null || $selected_color->guid === $sr->color) {
                        $q += $sr->qty;
                    }
                }
            }
            if (!$q) {
                \Errors\common_error::R("Товара нет в наличии в выбранном магазине");
            }
        }
        // проверки завершены
        // создаем запись и майлим
        $b = \DB\SQLTools\SQLBuilder::F();
        $tn = "@a" . md5(__METHOD__);
        $b->push("INSERT INTO clientorder (user_id,created,reserve,shop_id,shop_name,user_name,user_phone,user_email,dealer,delivery)
            VALUES(:P{$b->c}user_id,NOW(),1,:P{$b->c}shop_id,:P{$b->c}shop_name,:P{$b->c}user_name,:P{$b->c}user_phone,:P{$b->c}user_email,:P{$b->c}dealer,'');
            SET {$tn}=LAST_INSERT_ID();                 
            ");
        $b->push_params([
            ":P{$b->c}user_id" => $this->auth->authenticated ? $this->auth->id : null,
            ":P{$b->c}shop_id" => $data['shop_id'],
            ":P{$b->c}shop_name" => $shop->name,
            ":P{$b->c}user_name" => $data['name'],
            ":P{$b->c}user_phone" => $data['phone'],
            ":P{$b->c}user_email" => $data['email'],
            ":P{$b->c}dealer" => ($this->auth->authenticated ? $this->auth->is_verified_dealer : false) ? 1 : 0
        ]);
        $b->inc_counter();
        //id	item_guid	item_product_id	product_name	color_name	product_article	sizes	price	qty
        $b->push("INSERT INTO clientorder__status(id,status) VALUES({$tn},0);
            INSERT INTO clientorder__items(id,item_guid,item_product_id,product_name,color_name,product_article,sizes,price,qty)
            VALUES({$tn},UUID(),:P{$b->c}product_id,:P{$b->c}name,:P{$b->c}color,:P{$b->c}article,:P{$b->c}sizes,:P{$b->c}price,:P{$b->c}qty);
        ");
        $sizeliststring = null;
        if ($use_sizes) {
            $m = [];
            foreach ($selected_sizes as $ss) {
                $m[] = $ss->value;
            }
            $sizeliststring = implode(", ", $m);
        }
        $b->push_params([
            ":P{$b->c}product_id" => $product->id,
            ":P{$b->c}name" => $product->name,
            ":P{$b->c}color" => $selected_color ? $selected_color->name : null,
            ":P{$b->c}article" => $product->safe_article,
            ":P{$b->c}sizes" => $sizeliststring,
            ":P{$b->c}price" => $this->auth->is_verified_dealer ? $product->safe_gross : $product->safe_retail,
            ":P{$b->c}qty" => 1,
        ]);
        $b->inc_counter();
        $b->push("
            INSERT INTO clientorder__total (id,position,amount)
            SELECT {$tn},COUNT(*),SUM(qty*COALESCE(price,0))
                FROM clientorder__items WHERE id={$tn}
                GROUP BY id
            ON DUPLICATE KEY UPDATE position=VALUES(position),amount=VALUES(amount);
            ");
        $order_id = $b->execute_transact($tn);
        $params = \Content\Order\Tasks\ReserveNotifyTask::mk_params();
        $params->set("order_id", $order_id);
        $params->set_debug_out(true);
        $params->run();
        $this->out->add("order_id", $order_id)->add("shop_name", $shop->name)->add('shop_address', $shop->address);
    }

    protected function API_preorder() {
        // все проверить и отправить почту
        $raw_data = $this->GP->get_filtered("data", ["Strip", 'Trim', "NEString", "JSONString", 'NEArray', "DefaultNull"]);
        $raw_data ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($raw_data, [
            "product_id" => ["IntMore0"],
            "color_id" => ["Strip", "Trim", "NEString", "DefaultNull"],
            'sizes' => ["NEArray", "ArrayOfInt", "DefaultNull"],
            'phone' => ["Strip", "Trim", "NEString", "PhoneMatch"],
            'name' => ['Strip', 'Trim', 'NEString'],
            'email' => ["Strip", 'Trim', "NEString", "EmailMatch"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $product = \DataModel\Product\Model\ProductModel::F($data['product_id']);
        $selected_color = null;
        if ($product->has_colors && !$data['color_id']) {
            \Errors\common_error::R("Не указан цвет");
        } else if (!$product->has_colors && $data['color_id']) {
            \Errors\common_error::R("У этого товара не найдено такого цвета");
        } else if ($product->has_colors && $data['color_id']) {
            $selected_color = $product->colors->get_by_guid($data['color_id']);
            if (!$selected_color) {
                \Errors\common_error::R("У этого товара не найдено такого цвета");
            }
        }
        $selected_sizes = []; /* @var $selected_sizes \DataModel\Product\Model\ProductSize[] */

        if ($product->has_sizes && !$data['sizes']) {
            \Errors\common_error::R("не выбран размер");
        } else if (!$product->has_sizes && $data['sizes']) {
            \Errors\common_error::R("У этого товара отстутсвуют выбраные размеры");
        } else if ($product->has_sizes && $data['sizes']) {
            foreach ($data['sizes'] as $size_id) {
                $size = $product->sizes->get_by_id($size_id);
                $size ? 0 : \Errors\common_error::R("У этого товара отстутствует выбранный размер");
                $selected_sizes[] = $size;
            }
        }
        // проверки завершены
        // создаем запись и майлим
        $b = \DB\SQLTools\SQLBuilder::F();
        $tn = "@a" . md5(__METHOD__);
        $b->push("INSERT INTO clientorder (user_id,created,reserve,shop_id,shop_name,user_name,user_phone,user_email,dealer,delivery)
            VALUES(:P{$b->c}user_id,NOW(),2,null,null,:P{$b->c}user_name,:P{$b->c}user_phone,:P{$b->c}user_email,:P{$b->c}dealer,'');
            SET {$tn}=LAST_INSERT_ID();                 
            ");
        $b->push_params([
            ":P{$b->c}user_id" => $this->auth->authenticated ? $this->auth->id : null,
            ":P{$b->c}user_name" => $data['name'],
            ":P{$b->c}user_phone" => $data['phone'],
            ":P{$b->c}user_email" => $data['email'],
            ":P{$b->c}dealer" => ($this->auth->authenticated ? $this->auth->is_verified_dealer : false) ? 1 : 0
        ]);
        $b->inc_counter();
        //id	item_guid	item_product_id	product_name	color_name	product_article	sizes	price	qty
        $b->push("INSERT INTO clientorder__status(id,status) VALUES({$tn},0);
            INSERT INTO clientorder__items(id,item_guid,item_product_id,product_name,color_name,product_article,sizes,price,qty)
            VALUES({$tn},UUID(),:P{$b->c}product_id,:P{$b->c}name,:P{$b->c}color,:P{$b->c}article,:P{$b->c}sizes,:P{$b->c}price,:P{$b->c}qty);
        ");
        $sizeliststring = null;
        if (true) {
            $m = [];
            foreach ($selected_sizes as $ss) {
                $m[] = $ss->value;
            }
            $sizeliststring = implode(", ", $m);
        }
        $b->push_params([
            ":P{$b->c}product_id" => $product->id,
            ":P{$b->c}name" => $product->name,
            ":P{$b->c}color" => $selected_color ? $selected_color->name : null,
            ":P{$b->c}article" => $product->safe_article,
            ":P{$b->c}sizes" => $sizeliststring,
            ":P{$b->c}price" => $this->auth->is_verified_dealer ? $product->safe_gross : $product->safe_retail,
            ":P{$b->c}qty" => 1,
        ]);
        $b->inc_counter();
        $b->push("
            INSERT INTO clientorder__total (id,position,amount)
            SELECT {$tn},COUNT(*),SUM(qty*COALESCE(price,0))
                FROM clientorder__items WHERE id={$tn}
                GROUP BY id
            ON DUPLICATE KEY UPDATE position=VALUES(position),amount=VALUES(amount);
            ");
        $order_id = $b->execute_transact($tn);
        $params = \Content\Order\Tasks\PreorderNotifyTask::mk_params();
        $params->set("order_id", $order_id);
        $params->set_debug_out(false);
        $params->run();
        $this->out->add("order_id", $order_id);
    }

    protected function actionIndex() {
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('default'));
    }

    public static function get_default_action() {
        return "Index";
    }

    protected function API_add_to_basket() {
        $raw_data = $this->GP->get_filtered("data", ["Strip", 'Trim', "NEString", "JSONString", 'NEArray', "DefaultNull"]);
        $raw_data ? 0 : \Errors\common_error::R("invalid request");
        $data = \Filters\FilterManager::F()->apply_filter_array($raw_data, [
            "product_id" => ["IntMore0"],
            "color_id" => ["Strip", "Trim", "NEString", "DefaultNull"],
            'sizes' => ["NEArray", "ArrayOfInt", "DefaultNull"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $product = \DataModel\Product\Model\ProductModel::F($data['product_id']);
        $selected_color = null;
        if ($product->has_colors && !$data['color_id']) {
            \Errors\common_error::R("Не указан цвет");
        } else if (!$product->has_colors && $data['color_id']) {
            \Errors\common_error::R("У этого товара не найдено такого цвета");
        } else if ($product->has_colors && $data['color_id']) {
            $selected_color = $product->colors->get_by_guid($data['color_id']);
            if (!$selected_color) {
                \Errors\common_error::R("У этого товара не найдено такого цвета");
            }
        }
        $selected_sizes = []; /* @var $selected_sizes \DataModel\Product\Model\ProductSize[] */

        if ($product->has_sizes && !$data['sizes']) {
            \Errors\common_error::R("не выбран размер");
        } else if (!$product->has_sizes && $data['sizes']) {
            \Errors\common_error::R("У этого товара отстутсвуют выбраные размеры");
        } else if ($product->has_sizes && $data['sizes']) {
            foreach ($data['sizes'] as $size_id) {
                $size = $product->sizes->get_by_id($size_id);
                $size ? 0 : \Errors\common_error::R("У этого товара отстутствует выбранный размер");
                $selected_sizes[] = $size;
            }
        }
        $av_list = \Basket\StorageLoader::F()->get($product->id);
        $use_sizes = count($selected_sizes);
        if ($use_sizes) {
            $av_by_size = [];
            foreach ($av_list as $sr) {/* @var $sr \Basket\StorageResultItem */
                if ($selected_color === null || $selected_color->guid === $sr->color) {
                    $key = implode("", ["P", $sr->size ? $sr->size : "N"]);
                    if (!array_key_exists($key, $av_by_size)) {
                        $av_by_size[$key] = 0;
                    }
                    $av_by_size[$key] += $sr->qty;
                }
            }
            foreach ($selected_sizes as $size) {
                $key = "P{$size->id}";
                if (!(array_key_exists($key, $av_by_size) && $av_by_size[$key])) {
                    \Errors\common_error::RF("Размер \"%s\": нет в наличии", $size->value);
                }
            }
        } else {
            $q = 0;
            foreach ($av_list as $sr) {/* @var $sr \Basket\StorageResultItem */
                if ($selected_color === null || $selected_color->guid === $sr->color) {
                    $q += $sr->qty;
                }
            }
            if (!$q) {
                \Errors\common_error::R("Товара нет в наличии");
            }
        }
        // проверки завершены
        // кладем в корзинку
        $basket = \Basket\Basket::F();
        //$sizeliststring = null;
//        if ($use_sizes) {
//            $m = [];
//            foreach ($selected_sizes as $ss) {
//                $m[] = $ss->value;
//            }
//            $sizeliststring = implode(", ", $m);
//        }
        // в корзину кладеем объекты размеров, нам нужны еще и идентификаторы
        $basket->add($product->id, $selected_color ? $selected_color->guid : null, $selected_sizes, 1, $product);
        $this->out->add("basket_state", $basket->count);
    }

    protected function API_basket_content() {
        $this->basket->revalidate_if_need();
        $this->out->add("basket", $this->basket);
        $this->out->add("basket_count", $this->basket->count);
        $this->out->add("user_auth", $this->auth->is_authentificated() ? true : false);
        $this->out->add("user_dealer", $this->auth->is_verified_dealer ? true : false);
        $this->API_get_user_info();
    }

    protected function process_history() {
        $history = $this->GP->get_filtered('history', ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
        if ($history && count($history)) {
            $this->basket->process_history($history);
        }
    }

    protected function API_remove_item() {
        $this->process_history();
        $id = $this->GP->get_filtered('id', ["Trim", 'NEString', 'DefaultNull']);
        if ($id) {
            $this->basket->remove_item_by_hash($id);
        }
        $this->API_basket_content();
    }

    protected function API_sync() {
        $this->process_history();
        $this->API_basket_content();
    }

    //<editor-fold defaultstate="collapsed" desc="basket preorder valdataion">
    protected function validate_basket_for_order() {
        $this->basket->revalidate_if_need();
        $this->basket->empty ? \Errors\common_error::R("Корзина пуста") : 0;
        $ids = [];
        foreach ($this->basket as $item) { /* @var $item    \Basket\BasketItem */
            $ids[] = $item->id;
        }
        $ids = array_unique($ids);
        $storage = \Basket\StorageLoader::F()->get_array($ids);
        
        $used = []; // то что уже заказано - чтобы не потерять последние
        foreach ($this->basket as $item) { /* @var $item    \Basket\BasketItem */
            $product_key = "P{$item->id}";        
            $storage_state = array_key_exists($product_key, $storage) ? $storage[$product_key] : null;
            $this->validate_basket_item_for_order($item, $storage_state, $used);
        }
    }

    protected function create_storage_key(int $product_id, string $color_id = null, int $size_id = null) {
        //P50038Ca8309b81-a151-11e9-9352-2c56dc9ba4ecS7
        return sprintf("P%sC%sS%s", (string) $product_id, $color_id ? $color_id : 'N', $size_id ? ((string) $size_id) : 'N');
    }

    protected function validate_basket_item_for_order(\Basket\BasketItem $item, \Basket\StorageResult $storage = null, array &$used) {
        if (count($item->size_list)) {
            foreach ($item->size_list as $size_id) {
                $key = $this->create_storage_key($item->id, $item->color_id, $size_id);
                array_key_exists($key, $used) ? 0 : $used[$key] = 0;
                $available = 0;
                foreach ($storage->items as $storage_item) {
                    if ($storage_item->hash === $key) {
                        $available += $storage_item->qty;
                    }
                }
                $available -= $used[$key];
                if ($available < $item->qty) {
                    $size_value = $item->translate_size($size_id);
                    if ($size_value) {
                        
                        \Errors\common_error::RF("Товар отсутствует в  достаточном количестве: %s, размер: %s", "{$item->product_name} {$item->color_name}", $size_value);
                    } else {
                        \Errors\common_error::RF("Товар отсутствует в  достаточном количестве: %s", "{$item->product_name} {$item->color_name}");
                    }
                }
                $used[$key] = $item->qty;
            }
        } else {
            $key = $this->create_storage_key($item->id, $item->color_id, null);
            array_key_exists($key, $used) ? 0 : $used[$key] = 0;
            $available = 0;
            foreach ($storage->items as $storage_item) {
                if ($storage_item->hash === $key) {
                    $available += $storage_item->qty;
                }
            }
            $available -= $used[$key];
            if ($available < $item->qty) {
                \Errors\common_error::RF("Товар отсутствует в достаточном количестве:%s", "{$item->product_name} {$item->color_name}");
            }
            $used[$key] = $item->qty;
        }
    }

    //</editor-fold>

    protected function API_do_order() {
        $this->process_history();
        try {
            $form_data_raw = $this->GP->get_filtered("order_data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
            $form_data_raw ? 0 : \Errors\common_error::R("Некорректный запрос");
            $form_data = \Filters\FilterManager::F()->apply_filter_array($form_data_raw, [
                "family" => ["Strip", "Trim", "NEString"],
                "name" => ["Strip", "Trim", "NEString"],
                "phone" => ["Strip", 'Trim', 'NEString', 'PhoneMatch'],
                "email" => ["Strip", "Trim", "NEString", "EmailMatch"],
                "delivery" => ["Strip", "Trim", "NEString"],
                "comment" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "news" => ["Boolean", "DefaultTrue"],
                "apd" => ["Boolean", "DefaultFalse"],
            ]);
            \Filters\FilterManager::F()->raise_array_error($form_data);
            $form_data["user_id"] = $this->auth->authenticated ? $this->auth->id : null;
            $form_data["apd"] ? 0 : \Errors\common_error::R("access to personal data is required to be granted");
            $this->validate_basket_for_order();
            $builder = \DB\SQLTools\SQLBuilder::F();
            $user_var = "@u" . md5(__METHOD__);
            $order_var = "@o" . md5(__METHOD__);
            $new_user = false;
            $new_user_password = false;
            $builder->push("SET {$user_var}=NULL;");
            $b = $builder;
            if (!$form_data['user_id']) {
                $rui = \Auth\UserInfo::S($form_data['email']);
                if (!($rui && $rui->valid)) {
                    //создаем пользака
                    $new_user_password = \Helpers\Helpers::mk_password();
                    $new_encrypted_password = \Auth\UserInfo::encrypt_password($new_user_password);
                    $builder->push("
                        INSERT INTO user (guid,login,pass,role,is_dealer,is_approved,created)
                        VALUES(UUID(),:P{$b->c}email,:P{$b->c}pass,'client',0,1,NOW());
                        SET {$user_var}=LAST_INSERT_ID();
                           
                        INSERT INTO user__fields(id,name,family,eldername,phone)
                        VALUES({$user_var},:P{$b->c}name,:P{$b->c}family,'',:P{$b->c}phone);
                           
                        INSERT INTO user__search(id,search_name,search_phone) 
                        VALUES({$user_var},:P{$b->c}search_name,:P{$b->c}search_phone);");
                    $builder->push_params([
                        ":P{$b->c}email" => $form_data["email"],
                        ":P{$b->c}pass" => $new_encrypted_password,
                        ":P{$b->c}name" => $form_data["name"],
                        ":P{$b->c}family" => $form_data["family"],
                        ":P{$b->c}phone" => $form_data["phone"],
                        ":P{$b->c}search_name" => trim("{$form_data['family']} {$form_data['name']}"),
                        ":P{$b->c}search_phone" => preg_replace("/\D/i", "", $form_data["phone"]),
                    ]);
                    $builder->inc_counter();
                    $new_user = true;
                } else {// такой пользак уже есть
                }
            } else {//id пользака известен
                $builder->push("SET {$user_var}=:P{$b->c}user_id;");
                $builder->push_param(":P{$b->c}user_id", $form_data["user_id"]);
                $builder->inc_counter();
            }

            // создаем заказ из корзины
            $builder->inc_counter();
            $builder->push("INSERT INTO clientorder (user_id,created,reserve,shop_id,shop_name,user_name,user_phone,user_email,dealer,delivery)
                VALUES({$user_var},NOW(),0,NULL,NULL, :P{$b->c}name, :P{$b->c}phone, :P{$b->c}email, :P{$b->c}dealer, :P{$b->c}delivery);
                SET {$order_var}=LAST_INSERT_ID();   
                INSERT INTO clientorder__comment(id,`comment`) VALUES({$order_var},:P{$b->c}comment);
                INSERT INTO clientorder__status(id,status) VALUES({$order_var},0);
                ");

            $builder->push_params([
                ":P{$b->c}name" => trim("{$form_data["family"]} {$form_data['name']}"),
                ":P{$b->c}phone" => $form_data["phone"],
                ":P{$b->c}email" => $form_data["email"],
                ":P{$b->c}dealer" => ($this->auth->is_authentificated() && $this->auth->is_verified_dealer ? 1 : 0),
                ":P{$b->c}delivery" => $form_data["delivery"],
                ":P{$b->c}comment" => $form_data["comment"],
            ]);
            $builder->inc_counter();
            $ic = 0;
            $pp = [];
            $ii = [];
            $q = "";
            foreach ($this->basket as $item) { /* @var $item \Basket\BasketItem */
                $ii[] = "({$order_var},UUID(),:P{$b->c}_i{$ic}id,:P{$b->c}_i{$ic}name,:P{$b->c}_i{$ic}color,:P{$b->c}_i{$ic}article,:P{$b->c}_i{$ic}size,:P{$b->c}_i{$ic}price,:P{$b->c}_i{$ic}qty)";
                $pp[":P{$b->c}_i{$ic}id"] = $item->id; //id
                $pp[":P{$b->c}_i{$ic}name"] = $item->product_name; //name
                $pp[":P{$b->c}_i{$ic}color"] = $item->color_name; //color
                $pp[":P{$b->c}_i{$ic}article"] = $item->product_article; //article
                $pp[":P{$b->c}_i{$ic}size"] = $item->sizes; //size
                $pp[":P{$b->c}_i{$ic}price"] = $this->auth->authenticated && $this->auth->is_verified_dealer ? $item->price_gross : $item->price_retail; //price
                $pp[":P{$b->c}_i{$ic}qty"] = $item->qty; //qty
                $ic++;
            }
            $builder->push(sprintf("INSERT INTO clientorder__items (id,item_guid,item_product_id,product_name,color_name,product_article,sizes,price,qty) VALUES %s ;", implode(",", $ii)));
            $builder->push_params($pp);
            // обновить тотал ордера
            $builder->push("
                DELETE FROM clientorder__total WHERE id={$order_var};
                INSERT INTO clientorder__total (id,position,amount)
                    SELECT {$order_var},COUNT(*),SUM(qty*COALESCE(price,0))
                     FROM clientorder__items WHERE id={$order_var}
                     GROUP BY id
                ON DUPLICATE KEY UPDATE position=VALUES(position),amount=VALUES(amount);
            ");

            // все данные собраны, выполняем транзакцию       
            //die($builder->sql);
            $rv = $builder->execute_transact_ret_vars([$user_var => ["IntMore0", 'DefaultNull'], $order_var => ["IntMore0"]]);
            $this->basket->clear();
            $this->out->add("transaction_state", ["status" => "ok", "order_id" => $rv[$order_var]]);
            if ($new_user) {
                $new_user_id = $rv[$user_var];
                if ($new_user_id) {
                    $this->auth->force_login($new_user_id);
                }
            }
            \CommonTasks\TaskNewOrderCreated::mk_params()->run(["order_id" => $rv[$order_var], "new_user" => $new_user, "new_user_id" => $rv[$user_var], 'new_user_password' => $new_user_password]);
            // уведомляем всех обо всем одним таском
        } catch (\Throwable $ee) {
            $this->out->add("transaction_state", [
                'status' => 'error',
                'error_info' => [
                    'message' => $ee->getMessage(),
                    'file' => $ee->getFile(),
                    'line' => $ee->getLine(),
                    'trace' => $ee->getTraceAsString()
                ]
            ]);
        }
        $this->API_basket_content();
    }

    protected function actionSuccess() {
        $order_id = $this->GP->get_filtered('id', ["IntMore0", "DefaultNull"]);
        \smarty\SMW::F()->smarty->assign("new_order_id", $order_id);
        $this->render_view("front/layout", 'success');
    }

    protected function API_do_order_simple() {
        $this->process_history();
        try {
            $form_data_raw = $this->GP->get_filtered("order_data", ["Trim", "NEString", "JSONString", "NEArray", "DefaultNull"]);
            $form_data_raw ? 0 : \Errors\common_error::R("Некорректный запрос");
            $form_data = \Filters\FilterManager::F()->apply_filter_array($form_data_raw, [
                "family" => ["Strip", "Trim", "NEString", "DefaultNull"],
                "name" => ["Strip", "Trim", "NEString", "DefaultNull"],
                "phone" => ["Strip", 'Trim', 'NEString', 'PhoneMatch'],
                "email" => ["Strip", "Trim", "NEString", "EmailMatch", "DefaultNull"],
                "delivery" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "comment" => ["Strip", "Trim", "NEString", "DefaultEmptyString"],
                "news" => ["Boolean", "DefaultTrue"],
                "apd" => ["Boolean", "DefaultTrue"],
            ]);
            \Filters\FilterManager::F()->raise_array_error($form_data);
            $form_data["user_id"] = $this->auth->authenticated ? $this->auth->id : null;
            $form_data["apd"] ? 0 : \Errors\common_error::R("access to personal data is required to be granted");
            $form_data["name"] ? 0 : $form_data["name"] = "Не указано";
            $form_data["family"] ? 0 : $form_data[""] = "Не указано";
            $form_data["email"] ? 0 : $form_data["email"] = "Не указано";
            $this->validate_basket_for_order();
            $builder = \DB\SQLTools\SQLBuilder::F();
            $user_var = "@u" . md5(__METHOD__);
            $order_var = "@o" . md5(__METHOD__);
            $new_user = false;
            $new_user_password = false;
            $builder->push("SET {$user_var}=NULL;");
            $b = $builder;
            if ($form_data['user_id']) {//id пользака известен
                $builder->push("SET {$user_var}=:P{$b->c}user_id;");
                $builder->push_param(":P{$b->c}user_id", $form_data["user_id"]);
                $builder->inc_counter();
            }
            // создаем заказ из корзины
            $builder->inc_counter();
            $builder->push("INSERT INTO clientorder (user_id,created,reserve,shop_id,shop_name,user_name,user_phone,user_email,dealer,delivery)
                VALUES({$user_var},NOW(),0,NULL,NULL, :P{$b->c}name, :P{$b->c}phone, :P{$b->c}email, :P{$b->c}dealer, :P{$b->c}delivery);
                SET {$order_var}=LAST_INSERT_ID();   
                INSERT INTO clientorder__comment(id,`comment`) VALUES({$order_var},:P{$b->c}comment);
                INSERT INTO clientorder__status(id,status) VALUES({$order_var},0);
                ");

            $builder->push_params([
                ":P{$b->c}name" => trim("{$form_data["family"]} {$form_data['name']}"),
                ":P{$b->c}phone" => $form_data["phone"],
                ":P{$b->c}email" => $form_data["email"],
                ":P{$b->c}dealer" => ($this->auth->is_authentificated() && $this->auth->is_verified_dealer ? 1 : 0),
                ":P{$b->c}delivery" => $form_data["delivery"],
                ":P{$b->c}comment" => $form_data["comment"],
            ]);
            $builder->inc_counter();
            $ic = 0;
            $pp = [];
            $ii = [];
            $q = "";
            foreach ($this->basket as $item) { /* @var $item \Basket\BasketItem */
                $ii[] = "({$order_var},UUID(),:P{$b->c}_i{$ic}id,:P{$b->c}_i{$ic}name,:P{$b->c}_i{$ic}color,:P{$b->c}_i{$ic}article,:P{$b->c}_i{$ic}size,:P{$b->c}_i{$ic}price,:P{$b->c}_i{$ic}qty)";
                $pp[":P{$b->c}_i{$ic}id"] = $item->id; //id
                $pp[":P{$b->c}_i{$ic}name"] = $item->product_name; //name
                $pp[":P{$b->c}_i{$ic}color"] = $item->color_name; //color
                $pp[":P{$b->c}_i{$ic}article"] = $item->product_article; //article
                $pp[":P{$b->c}_i{$ic}size"] = $item->sizes; //size
                $pp[":P{$b->c}_i{$ic}price"] = $this->auth->authenticated && $this->auth->is_verified_dealer ? $item->price_gross : $item->price_retail; //price
                $pp[":P{$b->c}_i{$ic}qty"] = $item->qty; //qty
                $ic++;
            }
            $builder->push(sprintf("INSERT INTO clientorder__items (id,item_guid,item_product_id,product_name,color_name,product_article,sizes,price,qty) VALUES %s ;", implode(",", $ii)));
            $builder->push_params($pp);
            // обновить тотал ордера
            $builder->push("
                DELETE FROM clientorder__total WHERE id={$order_var};
                INSERT INTO clientorder__total (id,position,amount)
                    SELECT {$order_var},COUNT(*),SUM(qty*COALESCE(price,0))
                     FROM clientorder__items WHERE id={$order_var}
                     GROUP BY id
                ON DUPLICATE KEY UPDATE position=VALUES(position),amount=VALUES(amount);
            ");

            // все данные собраны, выполняем транзакцию       
            //die($builder->sql);
            $rv = $builder->execute_transact_ret_vars([$user_var => ["IntMore0", 'DefaultNull'], $order_var => ["IntMore0"]]);
            $this->basket->clear();
            $this->out->add("transaction_state", ["status" => "ok", "order_id" => $rv[$order_var]]);
            if ($new_user) {
                $new_user_id = $rv[$user_var];
                if ($new_user_id) {
                    $this->auth->force_login($new_user_id);
                }
            }
            \CommonTasks\TaskNewOrderCreated::mk_params()->run(["order_id" => $rv[$order_var], "new_user" => $new_user, "new_user_id" => $rv[$user_var], 'new_user_password' => $new_user_password]);
            // уведомляем всех обо всем одним таском
        } catch (\Throwable $ee) {
            $this->out->add("transaction_state", [
                'status' => 'error',
                'error_info' => [
                    'message' => $ee->getMessage(),
                    'file' => $ee->getFile(),
                    'line' => $ee->getLine(),
                    'trace' => $ee->getTraceAsString()
                ]
            ]);
        }
        $this->API_basket_content();
    }

}
