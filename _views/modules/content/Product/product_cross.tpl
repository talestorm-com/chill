{if count($this->product->cross)}
    {$OUT->add_css("/assets/css/front/tileblock/tileblock.default.css", 0)|void}
    {$OUT->add_css("/assets/css/front/tileblock/tileblock.product-line.css", 0)|void}
    {assign var="tileblock_uuid" value="a{$OUT->get_euid('tileblock')}"}
    <div class="TileBlock TileBlock-ProductLine TileBlock-custom-ProductCrossGenerated" id="tileblock_{$tileblock_uuid}">
        <div class="TileBlockInner">            
            <div class="TileBlockHeader">Вас может заинтересовать:
                <div class="TileBlockHeaderControls">
                    <div class="TileBlockHeaderButton TileBlockHeaderButtonLeft">
                        <svg><use xlink:href="#global_arrow_chevron" /></svg>
                    </div>
                    <div class="TileBlockHeaderButton TileBlockHeaderButtonRight">
                        <svg><use xlink:href="#global_arrow_chevron" /></svg>
                    </div>
                </div>
            </div>            
            <div class="TileBlockContentFrame">
                <div class="TileBlockContent" style="width:{(18.64*count($this->product->cross)+1.7*(count($this->product->cross)-1))|format_percent:'vw'}">
                    {if $controller->is_device}
                        {assign var="image_specification" value="SW_622H_933CF_1"}
                    {else}
                        {assign var="image_specification" value="SW_415H_622CF_1"}
                    {/if}
                    {foreach $this->product->cross as $product}
                        {if ($product->enabled)}
                            {include "./../common_templates/product_tile.tpl"}                            
                        {/if}
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
    <script>
        {literal}
            (function () {
                window.Eve = window.Eve || {};
                window.Eve.EFO = window.Eve.EFO || {};
                window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                window.Eve.EFO.Ready.push(ready);
                function ready() {
                    var WHT = null, drag_start_x = 0, drag_start_y = 0, drag_start_s = 0, U = window.Eve.EFO.U, drag_active = false;
                    var handle = jQuery('#tileblock_{/literal}{$tileblock_uuid}{literal}');
                    var content = handle.find('.TileBlockContent');
                    handle.on('click', '.ProductSmallTileButton', function (e) {
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    });
                    function measure_view() {
                        var all_items = handle.find('.ProductSmallTileItemOuter');
                        var total = 0;
                        all_items.each(function () {
                            var style = jQuery(this).get(0).currentStyle || window.getComputedStyle(jQuery(this).get(0));
                            var width = (jQuery(this).get(0).getBoundingClientRect().width);
                            total += width + U.FloatMoreOr(style.marginRight, 0, 0);
                        });
                        content.css({width: total + 'px'});
                    }
                    measure_view();
                    handle.on('click', '.TileBlockHeaderButton', function (e) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        var t = jQuery(this);
                        var item_width = U.FloatMoreEqOr(handle.find('.ProductSmallTileItemOuter:first').outerWidth(true), 0, 0);
                        var delta = 0;
                        if (t.hasClass('TileBlockHeaderButtonLeft')) {
                            delta = 1 * item_width;
                        } else if (t.hasClass('TileBlockHeaderButtonRight')) {
                            delta = -1 * item_width;
                        }
                        if (anime) {
                            anime({
                                targets: handle.find('.TileBlockContentFrame').get(0),
                                marginLeft: (delta < 0 ? "-=" : "+=") + Math.abs(delta),
                                duration: 300,
                                complete: _fix_scroll_pos,
                                easing: "linear"
                            });
                        } else {
                            var vh = U.FloatOr(handle.find('.TileBlockContentFrame').css('marginLeft'), 0) + delta;
                            handle.find('.TileBlockContentFrame').css('marginLeft', vh + "px");
                            _fix_scroll_pos();
                        }
                    });
                    jQuery(window).on('resize', function () {
                        measure_view();
                        fix_scroll_pos();
                    });

                    function _fix_scroll_pos(dir) {
                        if (WHT) {
                            window.clearTimeout(WHT);
                            WHT = null;
                        }
                        //var item = handle.find('.TileBlockItemOuter:first');
                        var all_items = handle.find('.ProductSmallTileItemOuter');
                        var item = all_items.filter(':first');
                        var item_width = /*Math.round*/(U.FloatMoreEqOr(item.outerWidth(false), 0, 0));
                        var item_width_with_margin = /*Math.round*/(U.FloatMoreEqOr(item.outerWidth(true), 0, 0));
                        var items_on_screen = U.IntMoreOr(Math.round(U.FloatMoreEqOr(jQuery(window).outerWidth(false), 0, 0) / item_width), 0, 0);
                        // debugger;
                        var item_qty = all_items.length;
                        var offset = U.FloatOr(handle.find('.TileBlockContentFrame').css('margin-left'), 0);
                        var aligned_offset_in_items = null;
                        if (dir === 1) {
                            aligned_offset_in_items = -1 * Math.floor(offset / item_width_with_margin);
                        } else if (dir === -1) {
                            aligned_offset_in_items = -1 * Math.ceil(offset / item_width_with_margin);
                        } else {
                            aligned_offset_in_items = -1 * Math.round(offset / item_width_with_margin);
                        }
                        //var aligned_offset_in_items = -1 * Math.round(offset / item_width_with_margin);
                        aligned_offset_in_items = Math.min(aligned_offset_in_items, item_qty - items_on_screen);
                        aligned_offset_in_items = Math.max(aligned_offset_in_items, 0);
                        var ev = 0;
                        for (var i = 0; i < aligned_offset_in_items; i++) {
                            var style = all_items.get(i).currentStyle || window.getComputedStyle(all_items.get(i));
                            ev -= (all_items.get(i).getBoundingClientRect().width + U.FloatMoreOr(style.marginRight, 0, 0));
                        }
                        if (window.anime) {
                            anime({
                                targets: handle.find('.TileBlockContentFrame').get(0),
                                marginLeft: ev,
                                duration: 300,
                                easing: "linear"
                            });
                        } else {
                            handle.find('.TileBlockContentFrame').css('margin-left', ev + 'px');
                        }
                    }

                    function fix_scroll_pos() {
                        if (WHT) {
                            window.clearTimeout(WHT);
                            WHT = null;
                        }
                        WHT = window.setTimeout(_fix_scroll_pos, 300);
                    }
                    function do_abort_drag() {
                        jQuery(document).off('mousemove touchmove', check_drag_distance);
                        jQuery(document).off('mouseup touchend', do_abort_drag);
                        jQuery(document).off('mousemove touchmove', while_drag);
                        jQuery(document).off('mouseup touchend', drag_success);
                    }

                    function on_drag_touch(n, e) {
                        if (/touch/i.test(e.type)) {
                            drag_start_x = e.originalEvent.touches[0].pageX;
                            drag_start_y = e.originalEvent.touches[0].pageY;
                        } else {
                            drag_start_x = e.pageX;
                            drag_start_y = e.pageY;
                        }
                        drag_start_s = -1 * U.FloatOr(handle.find('.TileBlockContentFrame').css('margin-left'), 0);
                        jQuery(document).on('mousemove touchmove', check_drag_distance);
                        jQuery(document).on('mouseup touchend', do_abort_drag);
                    }

                    function check_drag_distance(e) {
                        var cx = 0, cy = 0;
                        if (/touch/i.test(e.type)) {
                            cx = e.originalEvent.touches[0].pageX;
                            cy = e.originalEvent.touches[0].pageY;
                        } else {
                            cx = e.pageX;
                            cy = e.pageY;
                        }
                        var dx = Math.max(drag_start_x, cx) - Math.min(drag_start_x, cx);
                        var dy = Math.max(drag_start_y, cy) - Math.min(drag_start_y, cy);
                        var delta = Math.sqrt((dx * dx) + (dy * dy));
                        if (delta > 10 && (dx / 3) * 2 > dy) {
                            on_drag_began(e);
                        }
                    }

                    function on_drag_began(e) {
                        do_abort_drag();
                        drag_active = true;
                        jQuery(document).on('mousemove touchmove', while_drag);
                        jQuery(document).on('mouseup touchend', drag_success);
                        jQuery(document).on('mouseup touchend', do_abort_drag);
                        while_drag(e);
                    }

                    function while_drag(e) {
                        var cx = 0;
                        if (/touch/i.test(e.type)) {
                            cx = e.originalEvent.touches[0].pageX;
                        } else {
                            var cx = e.pageX;
                        }
                        var delta = drag_start_x - cx;
                        handle.find('.TileBlockContentFrame').css('margin-left', (-1 * (drag_start_s + delta)) + 'px');
                    }

                    function drag_success(e) {
                        try {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        } catch (eee) {

                        }
                        var delta = null;
                        var cx = 0;
                        if (/touch/i.test(e.type)) {
                            cx = e.originalEvent.changedTouches[0].pageX;
                        } else {
                            var cx = e.pageX;
                        }
                        delta = drag_start_x - cx;
                        delta = Math.abs(delta) > 10 ? Math.sign(delta) : null;
                        _fix_scroll_pos(delta);
                        window.setTimeout(function () {
                            drag_active = false;
                        }, 100);
                    }


                    handle.find('.TileBlockContentFrame').on('mousedown touchstart', function (e) {
                        on_drag_touch(jQuery(this), e);
                    });

                    handle.on('click', 'a', function (e) {
                        if (drag_active) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        }
                    });
                }
            })();
        {/literal}
    </script>
{/if}