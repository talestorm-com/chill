{$OUT->add_css("/assets/css/front/gallery/gallery.default.css", 0)|void}
{$OUT->add_script("/assets/vendor/anime/anime.min.js", 0, true)|void}
{$OUT->add_script("/assets/js/front/ImageView/image_view.min.js", 0, true)|void}
{assign var="gallery_uuid" value="a{$OUT->get_euid('gallery')}"}
<div class="GalleryRendererWrapper" id="gallery_{$gallery_uuid}">
    <div class="GalleryRenderer GalleryRenderer-{$this->template}">
        <div class="GalleryRendererInner">            
            <div class="GalleryRendererHeader">
                <div class="GalleryRendererHeaderControls">
                    <div class="GalleryRendererHeaderButton GalleryRendererHeaderButtonLeft">
                        <svg><use xlink:href="#global_arrow_chevron" /></svg>
                    </div>
                    <div class="GalleryRendererHeaderButton GalleryRendererHeaderButtonRight">
                        <svg><use xlink:href="#global_arrow_chevron" /></svg>
                    </div>
                </div>
            </div>            
            <div class="GalleryRendererContentFrame">
                <div class="GalleryRendererContent" style="width:{(18.64*$this->count+1.7*($this->count-1))|format_percent:'vw'}">
                    {assign var="image_specification" value="SW_415H_622CF_1"}
                    {foreach $this->images as $image}                        
                        {include "./default_item.tpl"}                        
                    {/foreach}
                </div>
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
                var image_view = null;
                var handle = jQuery('#gallery_{/literal}{$gallery_uuid}{literal}');
                handle.on('click', '.GalleryRendererHeaderButton', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    var t = jQuery(this);
                    var item_width = U.FloatMoreEqOr(handle.find('.GalleryRendererItem:first').outerWidth(true), 0, 0);
                    var delta = 0;
                    if (t.hasClass('GalleryRendererHeaderButtonLeft')) {
                        delta = 1 * item_width;
                    } else if (t.hasClass('GalleryRendererHeaderButtonRight')) {
                        delta = -1 * item_width;
                    }
                    if (window.anime) {
                        anime({
                            targets: handle.find('.GalleryRendererContentFrame').get(0),
                            marginLeft: (delta < 0 ? "-=" : "+=") + Math.abs(delta),
                            duration: 300,
                            complete: _fix_scroll_pos,
                            easing: "linear"
                        });
                    } else {
                        var vh = U.FloatOr(handle.find('.GalleryRendererContentFrame').css('marginLeft'), 0) + delta;
                        handle.find('.GalleryRendererContentFrame').css('marginLeft', vh + "px");
                        _fix_scroll_pos();
                    }
                });
                jQuery(window).on('resize', function () {
                    fix_scroll_pos();
                });

                function _fix_scroll_pos() {
                    if (WHT) {
                        window.clearTimeout(WHT);
                        WHT = null;
                    }
                    //var item = handle.find('.TileBlockItemOuter:first');
                    var all_items = handle.find('.GalleryRendererItem');
                    var item = all_items.filter(':first');
                    var item_width = /*Math.round*/(U.FloatMoreEqOr(item.outerWidth(false), 0, 0));
                    var item_width_with_margin = /*Math.round*/(U.FloatMoreEqOr(item.outerWidth(true), 0, 0));
                    var items_on_screen = U.IntMoreOr(Math.round(U.FloatMoreEqOr(handle.outerWidth(false), 0, 0) / item_width), 0, 0);
                    // debugger;
                    var item_qty = all_items.length;
                    var offset = U.FloatOr(handle.find('.GalleryRendererContentFrame').css('margin-left'), 0);
                    var aligned_offset_in_items = -1 * Math.round(offset / item_width_with_margin);
                    aligned_offset_in_items = Math.min(aligned_offset_in_items, item_qty - items_on_screen);
                    aligned_offset_in_items = Math.max(aligned_offset_in_items, 0);
                    //debugger;
                    var ev = 0;
                    for (var i = 0; i < aligned_offset_in_items; i++) {
                        var style = all_items.get(i).currentStyle || window.getComputedStyle(all_items.get(i));
                        ev -= (all_items.get(i).getBoundingClientRect().width + U.FloatMoreOr(style.marginRight, 0, 0));
                    }
                    if (window.anime) {
                        anime({
                            targets: handle.find('.GalleryRendererContentFrame').get(0),
                            marginLeft: ev,
                            duration: 300,
                            easing: "linear"
                        });
                    } else {
                        handle.find('.GalleryRendererContentFrame').css('margin-left', ev + 'px');
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
                    jQuery(document).off('mousemove', check_drag_distance);
                    jQuery(document).off('mouseup', do_abort_drag);
                    jQuery(document).off('mousemove', while_drag);
                    jQuery(document).off('mouseup', drag_success);
                }

                function on_drag_touch(n, e) {
                    drag_start_x = e.pageX;
                    drag_start_y = e.pageY;
                    drag_start_s = -1 * U.FloatOr(handle.find('.GalleryRendererContentFrame').css('margin-left'), 0);
                    jQuery(document).on('mousemove', check_drag_distance);
                    jQuery(document).on('mouseup', do_abort_drag);
                }

                function check_drag_distance(e) {
                    var cx = e.pageX;
                    var cy = e.pageY;
                    var dx = Math.max(drag_start_x, cx) - Math.min(drag_start_x, cx);
                    var dy = Math.max(drag_start_y, cy) - Math.min(drag_start_y, cy);
                    var delta = Math.sqrt((dx * dx) + (dy * dy));
                    if (delta > 10) {
                        on_drag_began(e);
                    }
                }

                function on_drag_began(e) {
                    do_abort_drag();
                    drag_active = true;
                    jQuery(document).on('mousemove', while_drag);
                    jQuery(document).on('mouseup', drag_success);
                    jQuery(document).on('mouseup', do_abort_drag);
                    while_drag(e);
                }

                function while_drag(e) {
                    var cx = e.pageX;
                    var delta = drag_start_x - cx;
                    console.log(delta);
                    handle.find('.GalleryRendererContentFrame').css('margin-left', (-1 * (drag_start_s + delta)) + 'px');
                }

                function drag_success(e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    _fix_scroll_pos();
                    window.setTimeout(function () {
                        drag_active = false;
                    }, 100);
                }


                handle.find('.GalleryRendererContentFrame').on('mousedown', function (e) {
                    on_drag_touch(jQuery(this), e);
                });

                handle.on('click', '.GalleryRendererItem', function (e) {
                    if (drag_active) {
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    } else {
                        var images = [];
                        handle.find('.GalleryRendererItem').each(function () {
                            var t = jQuery(this);
                            var p = {
                                context: t.data('context'),
                                owner_id: t.data('owner'),
                                image: t.data('image'),
                                title: t.data('title')
                            };
                            images.push(p);
                        });
                        var current_image = jQuery(this).data('image');
                        if (!image_view) {
                            window.Eve.image_view_ready = window.Eve.image_view_ready || [];
                            window.Eve.image_view_ready.push(function () {
                                image_view = window.Eve.image_view();                            
                                image_view.setup(images, current_image);                                
                                image_view.show();
                            });
                        } else {                            
                            image_view.setup(images, current_image);                            
                            image_view.show();
                        }
                    }
                });
            }
        })();
    {/literal}
</script>