{$OUT->add_script("/assets/js/front/ScrollFix.js", 0, true)|void}
{$OUT->meta->set_metadata($this)|void}
{if !$controller->GP->get_filtered('no_catalog_wrapper',['Boolean','DefaultFalse'])}
    <div class="CatalogPageOuter CatalogPageOuterCustom{$this->properties->get_filtered('css_class',['Strip','Trim','NEString','DefaultEmptyString'])}">
        {assign var="catalog_block_uuid" value="a{$OUT->get_euid('catalog_block')}"}
        <div class="CatalogPageSideblock CatalogPageLeft " id="menu_{$catalog_block_uuid}">            
            {menu alias="left_side_menu" template="left_side"}
        </div>        
        <div class="CatalogPageSideblock CatalogPageRight {if $this->is_big_mode_active}CatalogViewLarge{/if}" id="block_{$catalog_block_uuid}">        
            <div class="CatalogPageTopBlock">
                <div class="CatalogPageBreadcrumbs">
                    {foreach $this->breadcrumbs as $breadcrumb}
                        <div class="frontLayoutBreadcrumbBlock">
                            {if $breadcrumb->has_link}<a href="{$breadcrumb->link}">{/if}{$breadcrumb->text}{if $breadcrumb->has_link}</a>{/if}
                        </div>
                    {/foreach}
                </div>
                <div class="CatalogPageViewSwitch">
                    <div class="CatalogPageViewSwitchButton CatalogPageViewSwitchButtonSmall" data-command="switch_small">
                        <svg><use xlink:href="#catalog_view_icon_small" /></svg>
                    </div>
                    <div class="CatalogPageViewSwitchButton CatalogPageViewSwitchButtonLarge" data-command="switch_large">
                        <svg><use xlink:href="#catalog_view_icon_big" /></svg>
                    </div>                    
                </div>
            </div>
            <div class="CatalogPageProductList" id="list_{$catalog_block_uuid}">
            {/if}            
            {if $this->is_small_mode_active && !$controller->is_device}
                {assign var="image_specification" value="SW_300H_455CF_1"}
            {else}
                {assign var="image_specification" value="SW_750H_1137CF_1"}   
            {/if}
            {foreach $this->products as $product}                
                {include "./../common_templates/product_tile.tpl"}                
            {/foreach}
            {if !$controller->GP->get_filtered('no_catalog_wrapper',['Boolean','DefaultFalse'])}            
            </div>

            <div class="CatalogPageLoadMarker" id="marker_{$catalog_block_uuid}">
                <div class="CatalogPageLoadMarkerInner" id="loader_{$catalog_block_uuid}">
                    {include {$controller->common_templtes("preloader")}}
                </div>
            </div>
        </div>
    </div>
    <script>{literal}
        (function () {
            var uid = '{/literal}{$catalog_block_uuid}{literal}';
            var alias = '{/literal}{$this->catalog_alias}{literal}';
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var block = jQuery(["#block_", uid].join(''));
                var list = jQuery(["#list_", uid].join(''));
                var marker = jQuery(["#marker_", uid].join(''));
                var loader = jQuery(["#loader_", uid].join(''));
                var loading = false;
                var loaded_all = false;
                var per_page = U.IntMoreOr('{/literal}{$this->requested_perpage}{literal}', 0, 24);                
                if (history.state) {                    
                    var pp = U.IntMoreOr(history.state.pp, 0, null);
                    var ppr = false;
                    if (false && pp) {
                        var product = jQuery('.ProductTileItem[data-id=' + pp + ']');
                        if (product && product.length) {
                            jQuery(window).scrollTop(product.offset().top);
                        }
                    }
                    if (!ppr) {
                        var uu = U.IntMoreOr(history.state.st, 0, null);
                        if (uu !== null) {                            
                            jQuery(window).scrollTop(uu);
                        }
                    }
                }

                //var menu = jQuery(["#menu_", uid].join(''));
                //var last_sroll_top = U.FloatMoreOr(jQuery(window).scrollTop(), 0, 0);
                jQuery(window).on('scroll', try_load_more);
                //  jQuery(window).on('scroll', posite_menu);
                block.on('click', '.ProductSmallTileButton', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;                    
                });

                block.on('click', '.ProductSmallTileItemInner>a', function () {
                    var new_length = block.find('.ProductSmallTileItemOuter').length;
                    var url = location.href;
                    var new_url = [url.replace(/cp=\d{1,}/i, ''), "cp=" + new_length].join(url.indexOf('?') < 0 ? '?' : '&');
                    new_url = new_url.replace(/&&/g, '&').replace(/\?&/g, '?');                        
                    history.replaceState({count: new_length, st: jQuery(window).scrollTop()}, null, new_url);
                });

                function try_load_more() {                    
                    if (!loading && !loaded_all) {
                        var b = marker.get(0);
                        var y = b.getBoundingClientRect().top;
                        var window_height = U.FloatMoreOr(window.innerHeight, 0, 0);
                        var trigger = window_height + 150;
                        if (y <= trigger) {
                            loading = true;                            
                            var loaded = block.find('.ProductTileItem').length;
                            var loaded_page = Math.ceil(loaded / per_page);
                            jQuery.get([["/Catalog", alias, loaded_page].join('/'), ['sys_render_template=raw', 'sys_render_layout=raw', 'no_catalog_wrapper=1'].join('&')].join('?'))
                                    .done(function (r) {
                                        var html = U.NEString(r, '');
                                        var length = block.find('.ProductSmallTileItemOuter').length;
                                        list.append(html);
                                        var new_length = block.find('.ProductSmallTileItemOuter').length;
                                        var delta = new_length - length;
                                        if (delta === 0) {
                                            loaded_all = true;
                                        }
                                        //posite_menu();
                                        EFO.Events.GEM().Run("REALIGN_REQUIRED");//                                        
                                    })
                                    .fail(function () {
                                        console.log(arguments);
                                    })
                                    .always(function () {
                                        loading = false;
                                        loader.hide();
                                    });
                        }
                    }
                }

                try_load_more();

                block.on('click', '[data-command="switch_small"]', switch_small).on('click', '[data-command="switch_large"]', switch_large);

                //<editor-fold defaultstate="collapsed" desc="cookies">
                function getCookie(name) {
                    var matches = document.cookie.match(new RegExp(
                            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
                            ));
                    return matches ? decodeURIComponent(matches[1]) : undefined;
                }
                function setCookie(name, value, options) {
                    options = options || {};

                    var expires = options.expires;

                    if (typeof expires == "number" && expires) {
                        var d = new Date();
                        d.setTime(d.getTime() + expires * 1000);
                        expires = options.expires = d;
                    }
                    if (expires && expires.toUTCString) {
                        options.expires = expires.toUTCString();
                    }

                    value = encodeURIComponent(value);

                    var updatedCookie = name + "=" + value;

                    for (var propName in options) {
                        updatedCookie += "; " + propName;
                        var propValue = options[propName];
                        if (propValue !== true) {
                            updatedCookie += "=" + propValue;
                        }
                    }

                    document.cookie = updatedCookie;
                }
                //</editor-fold>

                function switch_small() {
                    setCookie("catalog_view_large", 0, {
                        expires: 10 * 365 * 24 * 60 * 60 * 1000,
                        path: "/"
                    });
                    block.removeClass("CatalogViewLarge");
                    reload_dynamic_content();
                }

                function switch_large() {
                    setCookie("catalog_view_large", 1, {
                        expires: 10 * 365 * 24 * 60 * 60 * 1000,
                        path: "/"
                    });
                    block.addClass("CatalogViewLarge");
                    reload_dynamic_content();
                }

                function reload_dynamic_content() {
                    var loaded_tiles = block.find('.ProductTileItem').length;
                    if (loaded_tiles) {
                        jQuery.get([["/Catalog", alias].join('/'), ['sys_render_template=raw', 'sys_render_layout=raw', 'no_catalog_wrapper=1', 'load_tile_count=' + loaded_tiles].join('&')].join('?'))
                                .done(function (r) {
                                    var html = U.NEString(r, '');
                                    list.html(html);
                                    EFO.Events.GEM().Run("REALIGN_REQUIRED");//
                                    //posite_menu();
                                })
                                .fail(function () {
                                    console.log(arguments);
                                })
                                .always(function () {
                                    loading = false;
                                    loader.hide();
                                });
                    }
                }



                window.Eve.scroll_fix_ready = window.Eve.scroll_fix_ready || [];
                window.Eve.scroll_fix_ready.push(function () {
                    window.Eve.scroll_fix(["menu_", uid].join(''), jQuery('.BeforeFooterOffset:first').get(0), jQuery('.FrontLayoutPageHeader').get(0), 0, 20);
                });


            });
        })();


    </script>{/literal}
    <div style="display:none!important">{include "./infographics.svg"}</div>
{/if}