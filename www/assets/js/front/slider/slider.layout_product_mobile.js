(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
      window.Eve.EFO.Ready.push(ready1);
    function ready1(){
     jQuery(ready);
    }

    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        E.SLIDER_CORE_LAYOUTS = E.SLIDER_CORE_LAYOUTS || [];
        function layout() {
            return (layout.is(this) ? this.init : layout.F).apply(this, APS.call(arguments));
        }
        var style = {"style": ".EveCoreSlider.EveCoreSlider-product_mobile {\n    width: 100%;\n    box-sizing: border-box;\n    padding: 0;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderInner {\n    box-sizing: border-box;\n    width: 100%;\n    overflow: hidden;\n    position: relative;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderImage {\n    box-sizing: border-box;\n    width: 100%;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderImage img {\n    width: 100%;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderImage{\n    position:absolute;\n    top:0;\n    left:101%;\n}\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderImage:nth-child(1){\n    left:0;\n} \n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderInner .EveCoreSliderImages {\n    position: relative;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderNvaligation {\n    position: absolute;\n    left: 0;\n    bottom: 0;\n    width: 100%;    \n    margin: 0;\n    padding: 0;    \n    padding-bottom: .5em;\n    padding-left: .25em;\n    z-index:2;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderNvaligation ul {\n    box-sizing: border-box;\n    width: 100%;\n    margin: 0;\n    padding: 0;\n    display: flex;\n    flex-direction: row;\n    justify-content: flex-start;\n    flex-wrap: nowrap;\n    padding: .1em;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderNvaligation ul li {\n    display: block;\n    margin: 0;\n    padding: 0;\n    width: .75em;\n    height: .75em;\n    box-sizing: border-box;\n    border: 2px solid white;\n    margin-right: .5em;\n}\n\n.EveCoreSlider.EveCoreSlider-product_mobile .EveCoreSliderNvaligation ul li.EveCoreSliderNavigtionMonitorCurrent {\n    outline: 1px solid transparent;\n    background: #f15a24;\n    border: 2px solid white;\n}"};
        var TEMPLATES = {"image": "<div class=\"EveCoreSliderImage Marker_{{image}}\" data-image=\"{{image}}\" data-index=\"{{index}}\" data-slider-layout-command=\"show_image\">    \n    <img src=\"{{image_url}}\" title=\"{{title}}\" \/>    \n<\/div>", "nav_view": "{{#do_display_nav}}\n{{#images}}\n<a href=\"#\" data-image=\"{{image}}\" data-command=\"select_image\" class=\"{{#is_current_image}}CoreSliderProductLayoutImageSelectedPin{{\/is_current_image}}\"><\/a>\n{{\/images}}\n{{\/do_display_nav}}", "wrapper": "<div class=\"EveCoreSlider EveCoreSlider-{{layout_name}}\" id=\"{{uid}}\">\n    <div class=\"EveCoreSliderInner\">        \n        <div class=\"EveCoreSliderImages\">\n            {{#images}}\n            {{>image}}\n            {{\/images}}\n        <\/div>\n        <div class=\"EveCoreSliderNvaligation\">\n            <ul>\n            {{#navigations}}\n            <li class=\"EveCoreSliderNavigtionMonitor\" data-id=\"{{.}}\"><\/li>\n            {{\/navigations}}\n            <\/ul>\n        <\/div>\n    <\/div>\n<\/div>"};
        jQuery(['<style type="text/css" data-id="slider.layout_product_mobile">', style.style, '</style>'].join('')).appendTo('body');
        var FP = U.FixCon(layout).prototype;
        FP.start_index = 0;

        FP.init = function (slider) {
            this.init_style(slider);
            slider.timeout = Number.MAX_SAFE_INTEGER;
            slider.crop_fill = true;
            slider.crop = true;
            return this;
        };

        FP.get_templates = function () {
            return TEMPLATES;
        };

        FP.init_style = function (slider) {
            var styletext = ["#", slider.uid, ' .EveCoreSliderInner .EveCoreSliderImages{ padding-top:150%!important;}'];
            this.style = jQuery('<style type="text/css"></style>');
            this.style.html(styletext.join(''));
            this.style.appendTo('body');
            if (U.safeArray(slider.images).length > 1) {
                this.init_slider_touch(slider);
            }
            return this;
        };

        FP.init_slider_touch = function (slider) {
            this._slider = slider;
            this._slider.root.on('touchstart', this.touch_start.bindToObject(this));

        };


        FP.touch_start = function (e) {
            this._current_image = U.IntOr(this._current_image, 0);
            this._touch_x = U.IntMoreOr(e.originalEvent.touches[0].pageX, 0, 0);
            this._touch_y = U.IntMoreOr(e.originalEvent.touches[0].pageY, 0, 0);
            this._slider.root.off('touchstart', this.touch_start.bindToObject(this));
            jQuery(document).on('touchend', this.abort_touch.bindToObject(this));
            jQuery(document).on('touchmove', this.check_delta_touch.bindToObject(this));
        };

        FP.check_delta_touch = function (e) {
            var cx = U.IntMoreOr(e.originalEvent.touches[0].pageX, 0, 0);
            var cy = U.IntMoreOr(e.originalEvent.touches[0].pageY, 0, 0);
            var dx = Math.max(this._touch_x, cx) - Math.min(this._touch_x, cx);
            var dy = Math.max(this._touch_y, cy) - Math.min(this._touch_y, cy);
            var delta = Math.sqrt((dx * dx) + (dy * dy));
            if (delta > 5 && (dx / 3) * 2 > dy) {
                this.on_drag_began(e);
                e.stopPropagation();
                // e.preventDefault ? e.preventDefault() : e.returnValue = false;
            }
        };

        FP.on_drag_began = function (e) {
            this.abort_touch();
            this._slider.root.off('touchstart', this.touch_start.bindToObject(this));
            jQuery(document).on('touchmove', this.while_drag.bindToObject(this));
            jQuery(document).on('touchend', this.drag_success.bindToObject(this));
            jQuery(document).on('touchend', this.abort_touch.bindToObject(this));
            this.next_image = this._slider.images[this._current_image + 1] ? this._slider.images[this._current_image + 1].node : this._slider.images[0].node;
            this.prev_image = this._slider.images[this._current_image - 1] ? this._slider.images[this._current_image - 1].node : this._slider.images[this._slider.images.length - 1].node;
            this.prev_image.css({"left": "-100%", "z-index": 2});
            this.next_image.css({"left": "100%", "z-index": 2});
            this._slider.images[this._current_image].node.css({"z-index": 1});
            this.while_drag(e);
        };

        FP.while_drag = function (e) {
            var cx = U.IntMoreOr(e.originalEvent.touches[0].pageX, 0, 0);
            var delta = this._touch_x - cx;
            var delta_pc = delta / (window.innerWidth / 100);
            this.prev_image.css({"left": (-100 - delta_pc).toFixed(5) + "%", "z-index": 2});
            this.next_image.css({"left": (100 - delta_pc).toFixed(5) + "%", "z-index": 2});
            e.stopPropagation();
            // e.preventDefault ? e.preventDefault() : e.returnValue = false;
        };

        FP.drag_success = function (e) {
            var cx = U.IntMoreOr(e.originalEvent.changedTouches[0].pageX, 0, 0);
            var delta = this._touch_x - cx;
            var delta_pc = delta / (window.innerWidth / 100);
            if (Math.abs(delta_pc) >= 10) {
                if (delta_pc > 0) { // next image
                    this._current_image++;
                    this.animate(this.next_image, 0, this.prev_image, -100, true);
                } else { //previous image
                    this._current_image--;
                    this.animate(this.prev_image, 0, this.next_image, 100, true);
                }
            } else {
                this.animate(this.next_image, 100, this.prev_image, -100);
            }
            this.sync_current_image();
        };

        FP.sync_current_image = function () {
            if (this._slider) {
                if (this._current_image > this._slider.images.length - 1) {
                    this._current_image = 0;
                }
                if (this._current_image < 0) {
                    this._current_image = this._slider.images.length - 1;
                }
                var code = this._slider.images[this._current_image].image;
                this._slider.root.find('li').removeClass('EveCoreSliderNavigtionMonitorCurrent').filter('[data-id=' + code + ']').addClass('EveCoreSliderNavigtionMonitorCurrent');
            }
        };

        FP.animate = function (n, tn, on, ont, fz) {
            var tl = anime.timeline({
                easing: 'linear',
                duration: 300
            });
            tl.pause();
            tl.add({
                targets: n.get(0),
                left: tn + "%"
            }, 0);
            tl.add({
                targets: on.get(0),
                left: ont + "%"
            });
            var self = this;
            tl.complete = function () {
                self.prev_image.css({"z-index": 1});
                self.next_image.css({"z-index": 1});
                if (U.anyBool(fz, false)) {
                    n.css({"z-index": 2});
                }
            };
            tl.play();
        };



        FP.abort_touch = function () {
            this._slider.root.off('touchstart', this.touch_start.bindToObject(this));
            jQuery(document).off('touchend', this.abort_touch.bindToObject(this));
            jQuery(document).off('touchmove', this.check_delta_touch.bindToObject(this));
            jQuery(document).off('touchmove', this.while_drag.bindToObject(this));
            jQuery(document).off('touchend', this.drag_success.bindToObject(this));
            jQuery(document).off('touchend', this.abort_touch.bindToObject(this));


            this._slider.root.on('touchstart', this.touch_start.bindToObject(this));
        };

        FP.prepare_images = function (slider) {
            if (slider.images.length > 1) {
                slider.navigations = [];
                for (var i = 0; i < slider.images.length; i++) {
                    slider.navigations.push(slider.images[i].image);
                }
                if (slider.images.length === 2) {
                    slider.images.push(slider.images[0].clone());
                    slider.images.push(slider.images[1].clone());
                }
            }
            return this;
        };

        FP.on_slide = function (slider) {

        };

        FP.get_image_spec = function (slider) {
            var max_dimension = Math.max(screen.width, screen.height);
            max_dimension = Math.ceil(max_dimension / 200) * 200;
            return {
                width: U.IntMoreOr(max_dimension, 0, null),
                height: U.IntMoreOr(max_dimension * 1.5, 0, null)
            };
        };

        FP.on_render_complete = function (slider) {

            this._current_image = U.IntOr(this._current_image, 0);
            this.sync_current_image();
//            var id = "miniatures_" + slider.uid;
//            window.Eve.scroll_fix_ready = window.Eve.scroll_fix_ready || [];
//            window.Eve.scroll_fix_ready.push(function () {
//                window.Eve.scroll_fix(id, jQuery('.BeforeFooterOffset:first').get(0), jQuery('.FrontLayoutPageHeader').get(0), 0, 16);
//            });
            //slider.root.on('click','.EveCoreSliderMiniature',this.on_miniature_click.bindToObjectWParam(this));

        };

        FP.exec_command = function (cmd, prms, slider) {
            var cname = ["layout_command_", cmd].join('');
            if (U.isCallable(this[cname])) {
                this[cname](prms, slider);
            }
            return this;
        };



        FP.on_command_nav = function (n, e, s) {
            if (false) {
                var image = null;
                var image_id = U.NEString(n.data('image'), null);
                if (image_id) {
                    for (var i = 0; i < s.images.length; i++) {
                        if (s.images[i].image === image_id) {
                            image = s.images[i];
                            break;
                        }
                    }
                    if (image) {
                        var node = image.node;
                        var page_pos = image.node.get(0).getBoundingClientRect().top + window.scrollY;
                        var ao = {
                            t: window.scrollY
                        };
                        anime({
                            targets: ao, t: [window.scrollY, page_pos], easing: "linear", duration: 300,
                            update: function () {
                                window.scrollTo(0, ao.t);
                            }
                        });

                    }
                }
            }
            return this;
        };

        FP.on_command_show_image = function (n, e, s) {
            if (false) {
                var image_id = U.NEString(n.data('image'), null);
                if (image_id) {
                    if (!this.image_view) {
                        this.image_view = image_view();
                    }
                    this.image_view.setup(s, image_id).show();
                }
            }
            return this;
        };


        FP.layout_command_order_by_color = function (p, slider) {
            return this;
            var prim = U.NEString(U.safeObject(p).primary_color, null);
            if (!this.mini_handle) {
                this.mini_handle = slider.root.find('.EveCoreSliderMiniatures');
            }
            if (!this.img_handle) {
                this.img_handle = slider.root.find('.EveCoreSliderImages');
            }
            if (prim) {
                var a = [];
                for (var i = 0; i < slider.images.length; i++) {
                    a.push(slider.images[i]);
                    if (!slider.images[i].miniature_node) {
                        slider.images[i].miniature_node = this.mini_handle.find([".EveCoreSliderMiniature.Marker_", slider.images[i].image].join(''));
                    }
                }
                a.sort(function (x, y) {
                    var xcolor = U.NEString(U.safeObject(x.properties).color);
                    var ycolor = U.NEString(U.safeObject(y.properties).color);
                    var xoffset = (xcolor && xcolor === prim) ? -1000000 : 0;
                    var yoffset = (ycolor && ycolor === prim) ? -1000000 : 0;
                    return (x.index + xoffset) - (y.index + yoffset);
                });
                var mp = this.img_handle;
                if (mp && mp.length) {
                    for (var i = 0; i < a.length; i++) {
                        mp.append(a[i].node);
                        this.mini_handle.append(a[i].miniature_node);
                    }
                }
                var ao = {
                    t: window.scrollY
                };
                anime({
                    targets: ao, t: [window.scrollY, 0], easing: "linear", duration: 300,
                    update: function () {
                        window.scrollTo(0, ao.t);
                    }
                });
            }
        };


        E.SLIDER_CORE_LAYOUTS.push(function (x) {
            x.register_layout('product_mobile', layout);
        });

    }

})();