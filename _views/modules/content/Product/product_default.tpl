{assign var="MC" value="ProductDefaultContent"}
{$OUT->add_script("/assets/vendor/anime/anime.min.js", 0, true)|void}
{$OUT->add_script("/assets/js/front/slider/slider.core.js", 0, true)|void}
{if $controller->is_device}
    {$OUT->add_script("/assets/js/front/slider/slider.layout_product_mobile.js", 0, true)|void}
{else}
    {$OUT->add_script("/assets/js/front/slider/slider.layout_product.js", 0, true)|void}
{/if}
<div class="{$MC}" id="handle_{$product_block_uuid}">    
    <div class="{$MC}Slider" id="slider_{$product_block_uuid}"></div>
    <div class="{$MC}InformationOuter">
        <div class="{$MC}Information" id="info_{$product_block_uuid}">
            <div class="{$MC}Name">{$this->product->name}</div>
            <div class="{$MC}ArtFav">
                <div class="{$MC}Art"><b>Арт.:</b> {$this->product->safe_article}</div>
                <div class="{$MC}Fav {if $this->is_product_favorite()}NowFavorite{/if}" data-command="add_to_favorite" data-id="{$this->product->id}">
                    <svg><use xlink:href="#product_favorite_heart"/></svg>
                </div>
            </div>
            {if $this->product->has_colors && count($this->product->colors)>1}
                <div class="{$MC}ColorList" id="colors_{$product_block_uuid}">        
                    <div class="{$MC}ColorListHeader">Цвет:</div>
                    <div class="{$MC}Colors">
                        {foreach $this->product->colors as $color}
                            <div class="{$MC}ColorItem" data-color-id="{$color->guid}" data-product-id="{$this->product->id}" data-command="select_color">
                                <div class="{$MC}ColorDisplay">
                                    {if $color->image_exists}
                                        <div class="{$MC}ColorComposite">
                                            <div class="{$MC}ColorDisplaySimple" style="background:{$color->html_color}"></div>
                                            <div class="{$MC}ColorDisplayImage"><img src="/media/_color/{$color->guid}.SW_100H_100CF_1.jpg" alt=""/></div>
                                        </div>
                                    {else}
                                        <div class="{$MC}ColorDisplaySimple" style="background:{$color->html_color}"></div>
                                    {/if}                                    
                                </div>
                                <div class="{$MC}ColorName">{$color->name}</div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            {/if}
            {if $this->has_sizes}
                <div class="{$MC}SizesBlock" id="sizes_{$product_block_uuid}">
                    <div class="{$MC}SizesBlockInner">
                        <div class="{$MC}SizeHeader">
                            <div class="{$MC}SizeHeaderItem">{if $this->has_alter_sizes}Размер ЕВРО{else}Размер{/if}</div>
                            {if $this->has_alter_sizes}
                                {foreach $this->sizes->defs as $alter_size}
                                    <div class="{$MC}SizeHeaderItem">{$alter_size->short_name}</div>
                                {/foreach}
                            {/if}
                        </div>
                        <div class="{$MC}SizeList">    
                            {if $this->has_alter_sizes}
                                <div class="{$MC}CompositeSizeRow">
                                    {foreach $this->sizes->items as $size}
                                        <div class="{$MC}SizeColumn" data-size-id="{$size->id}" data-product-id="{$this->product->id}" data-command="select_size">
                                            <div class="{$MC}SizeCell"><span class="{$MC}SizeValue">{$size->value}</span></div>
                                                {foreach $this->sizes->defs as $alter_size}
                                                <div class="{$MC}SizeCell"><span class="{$MC}SizeValue">{$size->get_alter_value_by_id($alter_size->id,'--')}</span></div>
                                                {/foreach}
                                        </div>
                                    {/foreach}
                                </div>
                            {else}
                                <div class="{$MC}SimpleSizeRow">
                                    {foreach $this->sizes->items as $size}
                                        <div class="{$MC}SizeColumn"  data-size-id="{$size->id}" data-product-id="{$this->product->id}" data-command="select_size">
                                            <div class="{$MC}SizeCell"><span class="{$MC}SizeValue">{$size->value}</span></div>
                                        </div>
                                    {/foreach}
                                </div>
                            {/if}
                        </div>
                    </div>

                </div>
            {/if}
            <div class="{$MC}ColorNotAvailable" id="no_color_av_{$product_block_uuid}">Выбранного цвета нет в наличии</div>    
            <div class="{$MC}ColorNotAvailableSize" id="no_color_av_sz{$product_block_uuid}">Для этого цвета выбранный размер отсутствует</div>    
            <div class="{$MC}NotAvailable" id="notav_{$product_block_uuid}">Временно нет в наличии</div>    
            <div class="{$MC}BasketButtonBlock" >
                <div class="{$MC}BasketButtonBlockInner">
                    <div class="{$MC}BasketButtonBlockPrice">
                        {if false && {product_has_price product=$this->product old=true}}
                            <div class="{$MC}BasketButtonBlockPriceOldValue">
                                <span class="{$MC}BasketPriceValue">{product_price product=$this->product old=true}</span>
                                <span class="{$MC}BasketPriceRurSign">руб.</span>
                            </div>
                        {/if}
                        {if {product_has_price product=$this->product}}
                            <div class="{$MC}BasketButtonBlockPriceValue {if false && {product_has_price product=$this->product old=true}}{$MC}PriceHilight{/if}">
                                <span class="{$MC}BasketPriceValue">{product_price product=$this->product}</span>
                                <span class="{$MC}BasketPriceRurSign">руб.</span>
                            </div>
                        {else}
                            <div class="{$MC}BasketButtonBlockPriceValue">
                                <span class="{$MC}BasketPriceValueNone">По запросу</span>
                            </div>
                        {/if}
                    </div>                    
                    <div class="{$MC}ButtonPositionMarker" id="button_pos_{$product_block_uuid}" style="width:0;height:0;max-width:0;max-height:0;"></div>
                    <div class="{$MC}BasketButtonBlockButton" id="button_{$product_block_uuid}" data-command="run_basket">
                        <div class="{$MC}BacketButtonBlockButtonImage">
                            <svg><use xlink:href="#global_cart_filled"/></svg>
                        </div>
                        <div class="{$MC}BacketButtonBlockButtonText">Купить</div>
                    </div>
                    
                </div>                
            </div>
            <div class="{$MC}Availablity" id="remains_by_shops_{$product_block_uuid}">        
                <div class="{$MC}AvailablityHeader">Наличие в магазинах <div class="{$MC}AvailablityHeaderChevron"><svg><use xlink:href="#global_menu_arrow" /></svg></div></div>       
                <div class="{$MC}AvailabilityBody"></div>
            </div>
            {if $this->product->orderable}
                <div class="{$MC}PreorderBlock">
                    <div class="{$MC}PreorderBlockInner">
                        <div class="{$MC}PreorderBlockText">
                            <div class="{$MC}PreorderBlockTextLine">Нет размера?</div>
                            <div class="{$MC}PreorderBlockTextLine">Закажите <span class="{$MC}PreorderChevron"><svg><use xlink:href="#global_menu_arrow" /></svg></span></div>
                        </div>
                        <div class="{$MC}PreorderBlockButton" data-command="run_preorder">Я хочу!</div>
                    </div>
                </div>
            {/if}
            {if $this->product_has_consists||$this->product_has_description}
                <div class="{$MC}Description">
                    {if $this->product_has_description}
                        <div class="{$MC}DescriptionHeader">Описание:</div>
                        <div class="{$MC}DescriptionBody">{$this->product->description}</div>
                    {/if}
                    {if $this->product_has_consists}
                        <div class="{$MC}DescriptionHeader">Состав:</div>
                        <div class="{$MC}DescriptionBody">{$this->product->consists}</div>
                    {/if}
                </div>
            {/if}
            <div class="{$MC}Share">
                <div class="{$MC}ShareInner">
                    <div class="{$MC}ShareHeader">Поделиться:</div>
                    <div class="{$MC}ShareVariants">
                        <div class="{$MC}ShareVariant">
                            <a href="https://vk.com/share.php?url={$controller->current_url()|urlencode}" target="_blank"><svg><use xlink:href="#product_share_vk"></svg></a>
                        </div>
                        <div class="{$MC}ShareVariant">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={$controller->current_url()|urlencode}" target="_blank"><svg><use xlink:href="#product_share_fb"></svg></a>
                        </div>
                        <div class="{$MC}ShareVariant  {$MC}ShareVariantIg" style="display:none">
                            <a href="#"><svg><use xlink:href="#product_share_ig"></svg></a>
                        </div>
                        <div class="{$MC}ShareVariant">
                            <a href="https://connect.ok.ru/offer?url={$controller->current_url()|urlencode}" target="_blank"><svg><use xlink:href="#product_share_ok"></svg></a>
                        </div>
                        <div class="{$MC}ShareVariant">
                            <a href="whatsapp://send?text={$controller->current_url()|urlencode}" target="_blank"><svg><use xlink:href="#product_share_wa"></svg></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>{literal}
    (function () {
        var uid = '{/literal}{$product_block_uuid}{literal}';
        var product_id = {/literal}{$this->product->id}{literal};
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            var E = window.Eve, EFO = E.EFO, U = EFO.U;
    {/literal}
        {if !$controller->is_device}
                window.Eve.scroll_fix_ready = window.Eve.scroll_fix_ready || [];
                window.Eve.scroll_fix_ready.push(function () {
                    window.Eve.scroll_fix(["info_", uid].join(''), jQuery('.BeforeFooterOffset:first').get(0), jQuery('.FrontLayoutPageHeader').get(0), 0, 20);
                });
        {/if}
        {literal}
                    var images_for_slider = U.safeArray({/literal}{$this->images_as_json}{literal});
                    var slideruuid = "{/literal}slider_{$product_block_uuid}{literal}";
                    window.Eve.SLIDER_CORE_READY = window.Eve.SLIDER_CORE_READY || [];
                    var slider_layout = null;
        {/literal}
        {if $controller->is_device}
                    slider_layout = "product_mobile";
        {else}
                    slider_layout = "product";
        {/if}
        {literal}
                    if (slider_layout) {
                        window.Eve.SLIDER_CORE_READY.push(function () {
                            window.Eve.SLIDER_CORE(slideruuid, slider_layout, images_for_slider, 0, 0, 0, null,{/literal}{$this->product->properties->kvs|json_encode}{literal});
                            try{
                              update_fab_offset();  
                            }catch(e){
                                console.log(e);
                            }
                        });
                    }
                    var handle = jQuery(["#handle_", uid].join(''));
                    var button_marker = handle.find(['#button_pos_',uid].join(''));
                    var size_handle = jQuery(["#sizes_", uid].join(''));
                    var color_handle = jQuery(["#colors_", uid].join(''));
                    var by_button = jQuery(["#button_", uid].join(''));
                    var not_avilable = jQuery(["#notav_", uid].join(''));
                    var remains_block = jQuery(["#remains_by_shops_", uid].join(''));
                    var no_color_av = jQuery(["#no_color_av_", uid].join(''));
                    var no_size_for_color = jQuery(["#no_color_av_sz", uid].join(''));
                    var remains_by_shops = jQuery(['#remains_by_shops_', uid].join(''));
                    var remains_opened = false;
                    var remains_by_shops_body = remains_by_shops.find('.ProductDefaultContentAvailabilityBody');
                    var selected_color = null;
                    var selected_size = null;
                    var FT = {/literal}{$this->create_front_templates('front_tpl')}{literal};
                    var by_shop_selection = {};
                    {/literal}{if $controller->is_device}{literal}
                    var fab_offset = null;
                    var fab_state = null;
                    var fab_node = null;
                    var fab_size = null;
                    function check_fab_state(){
                        if(!fab_node){
                            fab_node=button_marker.get(0);
                            fab_size = U.IntMoreOr(by_button.outerHeight(true),0,0);
                        }
                        fab_offset = fab_node.getBoundingClientRect().top+window.scrollY-window.innerHeight+fab_size;
                        if(window.scrollY>=fab_offset && fab_state!=='static'){
                            fab_state='static';
                            jQuery('body').addClass('bye-fab-is-static').removeClass('bye-fab-is-floating');
                        }else if(window.scrollY<fab_offset && fab_state!=='fixed'){
                            fab_state='fixed';
                            jQuery('body').removeClass('bye-fab-is-static').addClass('bye-fab-is-floating');
                        }
                    }
                    function update_fab_offset(){                        
                       // var fab_pos = button_marker.get(0).getBoundingClientRect().top;
                       // fab_offset = fab_pos+window.scrollY-window.innerHeight;
                       // console.log("fab_offset",fab_offset);
                        check_fab_state();
                    }
                    update_fab_offset();
                    jQuery(window).on('resize',function(){
                        update_fab_offset();
                    });
                    
                    jQuery(document).on('scroll touchmove',function(){
                        check_fab_state();
                    });
                    {/literal}{/if}{literal}

                    E.product_manager_ready = E.product_manager_ready || [];
                    E.product_manager_ready.push(function () {
                        var PM = E.ProductManager();
                        PM.load_product(product_id, window, function (pi) {
                            var total_qty = PM.get_total_remains_of(product_id);
                            if (pi.color_count === 1) {
                                selected_color = pi.colors.items[0].guid;
                            }
                            //<editor-fold defaultstate="collapsed" desc="main availability">
                            function update_availability_view_main() {
                                if (!total_qty) {
                                    update_availability_view_main_not_in_stock();
                                } else {
                                    update_availability_view_main_in_stock();
                                }
                                render_remains_by_shops_if();
                            }
                            function update_availability_view_main_not_in_stock() {
                                size_handle.addClass("disabled");
                                by_button.addClass("disabled");
                                not_avilable.show();
                                remains_block.hide();
                            }
                            function update_availability_view_main_in_stock() {
                                size_handle.removeClass("disabled");
                                by_button.removeClass("disabled");
                                not_avilable.hide();
                                remains_block.show();//это для всех
                                if (pi.size_count && pi.color_count) { //есть и цвета и размеры
                                    if (selected_color) {//вилка                   
                                        // определяем видимость размеров
                                        var sizes_av = 0; // сколько всего размеров выбранного цвета есть в наличии   
                                        var qt_of_selected_size = 0;
                                        for (var i = 0; i < pi.size_count; i++) {
                                            var sl = pi.sizes.items[i];
                                            var qty = PM.get_remains_by_size_and_color(pi.product_id, sl.id, selected_color);
                                            size_handle.find('[data-size-id=' + sl.id + ']')[(qty ? 'removeClass' : 'addClass')]('disabled');
                                            qty ? sizes_av++ : 0;
                                            sl.id === selected_size ? qt_of_selected_size = qty : 0;
                                        }
                                        no_color_av[(sizes_av ? 'hide' : 'show')]();// выбранного цвета нет в наличии                               
                                        // прячем маркер #для данного цвета выбранный размер отсутствует#
                                        no_size_for_color.hide();
                                        if (selected_size && sizes_av) { // если до этого уже был выбранный размер
                                            if (!qt_of_selected_size) {
                                                //selected_size = null;
                                                //size_handle.find('.selected_size').removeClass('selected_size');
                                                no_size_for_color.show();
                                            }
                                        }
                                    } else {
                                        size_handle.find('.disabled').removeClass('disabled'); // если цвет не выбран - снимаем все ограничения
                                        //и прячем все маркеры
                                    }
                                } else if (pi.size_count) { //есть только размеры
                                    // просто маскируем все отсутствующие, с самого начала без выбора
                                    for (var i = 0; i < pi.size_count; i++) {
                                        var sl = pi.sizes.items[i];
                                        var qty = PM.get_remains_by_size_and_color(pi.product_id, sl.id, selected_color);//null
                                        size_handle.find('[data-size-id=' + sl.id + ']')[(qty ? 'removeClass' : 'addClass')]('disabled');
                                    }
                                } else if (pi.color_count) {// есть только цвета                        
                                    no_color_av.hide();
                                    if (selected_color) {
                                        var qty = PM.get_remains_by_color(pi.product_id, selected_color);
                                        if (!qty) {
                                            no_color_av.show();
                                        }
                                    }
                                } else {//ничего нет вообще
                                    // в этом случае ничего делать и не надо
                                }

                            }
                            //</editor-fold>

                            //<editor-fold defaultstate="collapsed" desc="remains by shops">
                            function render_remains_by_shops_if() {
                                if (remains_opened) {
                                    render_remains_by_shops();
                                }
                            }
                            function render_remains_by_shops() {// на полный остаток не проверяем - блока просто не будет с без него
                                //сначала определяем магазы в которых зоть чтото есть
                                // лучше сразу отобрать нужные  
                                var sizelist = [];
                                for (var i = 0; i < pi.sizes.items.length; i++) {
                                    var si = {id: pi.sizes.items[i].id, items: [pi.sizes.items[i].value]};
                                    for (var j = 0; j < pi.sizes.defs.length; j++) {
                                        var key = pi.sizes.defs[j].key;
                                        si.items.push(pi.sizes.items[i].alters[key] ? pi.sizes.items[i].alters[key].value : '--');
                                    }
                                    sizelist.push(si);
                                }
                                var selected_shops = [];
                                for (var i = 0; i < PM.offline.items.length; i++) {
                                    var shop = PM.offline.items[i];
                                    if (PM.get_remains_by_storage(pi.product_id, shop.storage_id)) {
                                        selected_shops.push(shop);
                                    }
                                }

                                if (!selected_shops.length) {
                                    html = Mustache.render(FT.temporary_avoid, this, FT);
                                    remains_by_shops_body.html(html);
                                    return;
                                }

                                if (pi.color_count && pi.size_count) { // есть и цвет и размер
                                    if (selected_color) {
                                        // отрендерить наличие
                                        // пропускать магазы в которых ничего нет (по цвету)
                                        var rendered_shops = 0;
                                        var renderer = {
                                            shops: selected_shops,
                                            pi: pi,
                                            PM: PM,
                                            sl: sizelist,
                                            current_shop: null,
                                            _set_current_shop: function (x) {
                                                this.current_shop = x;
                                                return '';
                                            },
                                            _is_size_available: function (s) {
                                                return PM.get_remains_by_shop_color_size(pi.product_id, this.current_shop.storage_id, selected_color, s.id) ? true : false;
                                            },
                                            _render_this_shop: function (x) {
                                                var result = PM.get_remains_by_shop_color(pi.product_id, x.storage_id, selected_color) ? true : false;
                                                result ? rendered_shops++ : 0;
                                                return result;
                                            },
                                            _is_size_selected: function (x) {
                                                var key = ["P", pi.product_id, "C", (selected_color ? selected_color : "N"), "S", this.current_shop.id].join('');
                                                if (x.id === by_shop_selection[key]) {
                                                    return true;
                                                }
                                                return '';
                                            }
                                        };
                                        renderer.set_current_shop = renderer._set_current_shop.bindToObjectWParam(this);
                                        renderer.is_size_available = renderer._is_size_available.bindToObjectWParam(this);
                                        renderer.render_this_shop = renderer._render_this_shop.bindToObjectWParam(this);
                                        renderer.is_size_selected = renderer._is_size_selected.bindToObjectWParam(this);
                                        var html = Mustache.render(FT.select_size_in_shop, renderer, FT);
                                        if (!rendered_shops) {
                                            html = Mustache.render(FT.no_color_available, this, FT);
                                        }
                                        remains_by_shops_body.html(html);
                                    } else {
                                        render_do_select_color();
                                    }
                                } else if (pi.color_count) {// есть только цвет
                                    if (selected_color) {
                                        var rendered_shops = 0;
                                        var renderer = {
                                            shops: selected_shops,
                                            pi: pi,
                                            PM: PM,
                                            current_shop: null,
                                            _set_current_shop: function (x) {
                                                this.current_shop = x;
                                                return '';
                                            },
                                            _is_product_available_in_shop: function (x) {
                                                var result = PM.get_remains_by_shop_color(pi.product_id, x.storage_id, selected_color) ? true : false;
                                                result ? rendered_shops++ : 0;
                                                return result;
                                            }
                                        };
                                        renderer.set_current_shop = renderer._set_current_shop.bindToObjectWParam(this);
                                        renderer.render_this_shop = renderer._is_product_available_in_shop.bindToObjectWParam(this);
                                        var html = Mustache.render(FT.by_shop_and_color, renderer, FT);
                                        if (!rendered_shops) {
                                            html = Mustache.render(FT.no_color_available, this, FT);
                                        }
                                        remains_by_shops_body.html(html);
                                    } else {
                                        render_do_select_color();
                                    }
                                } else if (pi.size_count) {// есть только размер
                                    var rendered_shops = 0;
                                    var renderer = {
                                        shops: selected_shops,
                                        pi: pi,
                                        PM: PM,
                                        current_shop: null,
                                        _set_current_shop: function (x) {
                                            this.current_shop = x;
                                            return '';
                                        },
                                        _is_product_available_in_shop: function (x) {
                                            var result = PM.get_remains_shop_size(pi.product_id, this.current_shop.storage_id, x.id) ? true : false;
                                            result ? rendered_shops++ : 0;
                                            return result;
                                        },
                                        _is_size_selected: function (x) {
                                            var key = ["P", pi.product_id, "C", (selected_color ? selected_color : "N"), "S", this.current_shop.id].join('');
                                            if (x.id === by_shop_selection[key]) {
                                                return "selected";
                                            }
                                            return '';
                                        }
                                    };
                                    renderer.set_current_shop = renderer._set_current_shop.bindToObjectWParam(this);
                                    renderer.render_this_shop = renderer._is_product_available_in_shop.bindToObjectWParam(this);
                                    renderer.is_size_selected = renderer._is_size_selected.bindToObjectWParam(this);
                                    var html = Mustache.render(FT.by_shop_and_size, renderer, FT);
                                    if (!rendered_shops) {
                                        html = Mustache.render(FT.no_color_available, this, FT);
                                    }
                                    remains_by_shops_body.html(html);
                                } else { // ни цветов ни размеров
                                    // просто спис магазов в которых есть и кнопа
                                }
                            }
                            function render_do_select_color() {
                                // нужен блок темплатов для фронта и их генератор
                                remains_by_shops_body.html(Mustache.render(FT.do_select_color, this));
                            }

                            //</editor-fold>

                            update_availability_view_main();
                            if (total_qty && pi.size_count) {
                                size_handle.on("click", "[data-command=\"select_size\"]", function (e) {
                                    var id = U.IntMoreOr(jQuery(this).data('sizeId'), 0, null);
                                    var product_id = U.IntMoreOr(jQuery(this).data('productId'), 0, null);
                                    if (!jQuery(this).hasClass('disabled')) {
                                        if (id && product_id) {
                                            selected_size = id;
                                        }
                                        size_handle.find('.selected_size').removeClass("selected_size");
                                        jQuery(this).addClass('selected_size');
                                        update_availability_view_main();
                                    }
                                });
                            }
                            if (pi.color_count) {
                                color_handle.on('click', "[data-command=select_color]", function (e) {
                                    var id = U.NEString(jQuery(this).data('colorId'), 0, null);
                                    var product_id = U.IntMoreOr(jQuery(this).data('productId'), 0, null);
                                    if (id && product_id) {
                                        selected_color = id;
                                    }
                                    color_handle.find('.selected_color').removeClass("selected_color");
                                    jQuery(this).addClass('selected_color');
                                    update_availability_view_main();
                                    // пересортировать слайдер и перемотать наверх
                                    window.Eve.SLIDER_CORE_READY.push(function () {
                                        var slider = window.Eve.SLIDER_CORE.get_slider_instance(slideruuid);
                                        if (slider) {
                                            slider.exec_layout_command('order_by_color', {'primary_color': id});
                                        }
                                    });
                                });
                            }
                            if (total_qty) {
                                remains_by_shops.on('click', '.ProductDefaultContentAvailablityHeader', function () {
                                    if (!remains_opened) {
                                        remains_by_shops.addClass('opened');
                                        remains_opened = true;
                                        render_remains_by_shops();
                                    } else {
                                        remains_by_shops.removeClass('opened');
                                        remains_opened = false;
                                    }
                                });
                                // нужен блок записи для выбранных размеров по магазинам? и по цветам
                                remains_by_shops.on('click', '.ProductAvailabilityBlockOneShopSizeSelect .ProductAvailabilityBlockOneShopSizeColumn', function (e) {
                                    var t = jQuery(this);
                                    if (!t.hasClass('ProductAvailabilityBlockOneShopSizeColumnDisabled')) {
                                        var rp = t.closest('.ProductAvailabilityBlockOneShopSizeSelect');
                                        var shop_id = rp.data('shopId');
                                        var size_id = t.data('size');
                                        var key = ["P", pi.product_id, "C", (selected_color ? selected_color : 'N'), 'S', shop_id].join('');
                                        rp.find(".selected").removeClass('selected');
                                        t.addClass('selected');
                                        by_shop_selection[key] = size_id;
                                    }
                                });
                                remains_by_shops.on('click', '[data-command=hold_in_shop]', function () {
                                    var t = jQuery(this);
                                    var shop_id = t.data('shopId');
                                    EFO.Com().load('front.basket_dialog.reserve')
                                            .done(window, function (x) {
                                                var key = ["P", pi.product_id, "C", (selected_color ? selected_color : 'N'), 'S', shop_id].join('');
                                                x.show().load_product(pi.product_id, shop_id, selected_color, U.IntMoreOr(by_shop_selection[key], 0, null));
                                            });
                                    /*на завтра - добить корзниу, формы бронирования и заказа*/
                                });

                                handle.on('click', '[data-command=run_basket]', function () {
                                    EFO.Com().load('front.basket_dialog.basket')
                                            .done(window, function (x) {
                                                x.show().load_product(pi.product_id, null, selected_color, selected_size);
                                            });
                                });

                            }
                            handle.on('click', '[data-command=run_preorder]', function () {
                                EFO.Com().load('front.basket_dialog.preorder')
                                        .done(window, function (x) {
                                            x.show().load_product(pi.product_id, null, selected_color, selected_size);
                                        });
                            });
                        });
                    });//-->product_manager_ready
                    EFO.Events.GEM().on('PRODUCT_FAVORITE', window, function (product, fm) {
                        if (product === product_id) {
                            if (fm) {
                                handle.find('.ProductDefaultContentFav').addClass("NowFavorite");
                            } else {
                                handle.find('.ProductDefaultContentFav').removeClass("NowFavorite");
                            }
                        }
                    });
                });
            })();
        </script>{/literal}