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
        var style = {"style": "\/*\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n*\/\n\/* \n    Created on : 05.07.2019, 11:11:14\n    Author     : studio2\n*\/\n\n.EveCoreSlider.EveCoreSlider-triplex {\n    box-sizing: border-box;\n    line-height: 0;\n}\n\n.EveCoreSlider.EveCoreSlider-triplex .EveCoreSliderInner {\n    box-sizing: border-box;\n    width: 100%;\n    position: relative;\n    overflow:hidden;\n}\n\n.EveCoreSlider.EveCoreSlider-triplex .EveCoreSliderImage {\n    position: absolute;\n    top:100%;\n    right:0;\n    z-index: 1;        \n    will-change:scroll-position,opacity,transform;\n    width:33.333%;\n}\n\n\n.EveCoreSlider.EveCoreSlider-triplex .EveCoreSliderImage img {\n    width:100%;\n    max-width:100%;\n    min-width:100%;\n}\n\n.EveCoreSliderImageTextOuter {\n    position: absolute;\n    top: 0;\n    left: 0;\n    width: 100%;\n    height: 100%;\n    line-height: normal;    \n    display: flex;\n    padding: 1em;\n    font-size: 1.5em;\n    font-family: Helvetica Neue,larro-def-webfont,serif;\n    font-weight: 400;\n}\n\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-center {\n    flex-direction: row;\n    justify-content: center;\n    align-items: center;\n}\n\n.EveCoreSliderImageLogoOuter {\n    position: absolute;\n    top: 0;\n    left: 0;\n    width: 100%;\n    height: 100%;\n    display: flex;\n    flex-direction: row;\n    justify-content: center;\n    align-items: center;\n}\n\n.EveCoreSliderImageLogoInner {\n    width: 50%;\n}\n\n.EveCoreSliderImageLogoInner svg {\n    width: 100%;\n}\n\n\n\n.EveCoreSliderImageLogoOuter {\n    padding: 1em;\n}\n\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-topleft {\n    justify-content: flex-start;\n    align-items: flex-start;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-topcenter {\n    justify-content: center;\n    align-items: flex-start;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-topright {\n    justify-content: flex-end;\n    align-items: flex-start;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-midright {\n    justify-content: flex-end;\n    align-items: center;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-midleft {\n    justify-content: flex-start;\n    align-items: center;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-botleft {\n    justify-content: flex-start;\n    align-items: flex-end;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-botcenter {\n    justify-content: center;\n    align-items: flex-end;\n}\n.EveCoreSliderImageLogoOuter.EveCoreSliderImageLogoTextStyle-botright {\n    justify-content: flex-end;\n    align-items: flex-end;\n}\n\n\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-topleft {\n    align-items: flex-start;\n    justify-content: flex-start;\n}\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-topcenter {\n    align-items: flex-start;\n    justify-content: center;\n}\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-topright {\n    align-items: flex-start;\n    justify-content: flex-end;\n}\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-midleft {\n    align-items: center;\n    justify-content: flex-start;\n}\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-midright {\n    align-items: center;\n    justify-content: flex-end;\n}\n\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-botleft {\n    align-items: flex-end;\n    justify-content: flex-start;\n}\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-botcenter {\n    align-items: flex-end;\n    justify-content: center;\n}\n.EveCoreSliderImageTextOuter.EveCoreSliderImageLogoTextStyle-botright {\n    align-items: flex-end;\n    justify-content: flex-end;\n}\n.EveCoreSliderImageTextInner {\n    letter-spacing: .05em;\n}"};
        try {
            jQuery(['<style type="text/css" data-id="slider.layout_triplex">', style.style, '</style>'].join('')).appendTo('body');
        } catch (e) {
            console.log(e);
        }
        var TEMPLATES = {"image": "<div class=\"EveCoreSliderImage Marker_{{image}}\" data-image=\"{{image}}\" data-index=\"{{index}}\">\n    {{#has_link}}<a href=\"{{link}}\">{{\/has_link}}\n        <img src=\"{{image_url}}\" title=\"{{title}}\" \/>\n        {{#triplex_has_text}}{{#triplex_is_logo_text}}{{>logo_text}}{{\/triplex_is_logo_text}}{{^triplex_is_logo_text}}{{>simple_text}}{{\/triplex_is_logo_text}}{{\/triplex_has_text}}\n    {{#has_link}}<\/a>{{\/has_link}}\n<\/div>", "logo_text": "<div class=\"EveCoreSliderImageLogoOuter EveCoreSliderImageLogoTextStyle-{{_triplex_text_class}}\">\n    <div class=\"EveCoreSliderImageLogoInner\">\n        <svg><use xlink:href=\"#common_logo\" \/><\/svg>\n    <\/div>\n<\/div>", "simple_text": "<div class=\"EveCoreSliderImageTextOuter EveCoreSliderImageLogoTextStyle-{{_triplex_text_class}}\">\n    <div class=\"EveCoreSliderImageTextInner\">{{{triplex_get_image_text}}}<\/div>\n<\/div>", "wrapper": "<div class=\"EveCoreSlider EveCoreSlider-{{layout_name}}\" id=\"{{uid}}\">\n    <div class=\"EveCoreSliderInner\">\n        {{#images}}\n        {{>image}}\n        {{\/images}}\n    <\/div>\n<\/div>"};
        var FP = U.FixCon(layout).prototype;
        FP.start_index = 0;
        FP.animation_duration = null;
        FP.section_delay = null;
        FP.big_slide = null;
        FP.top_small_slide = null;
        FP.bottom_small_slide = null;
        FP.dimension_x = 1500;
        FP.dimension_y = 752;
        FP.dimension_k = null;
        FP.dimension_i = null;
        FP.ki_first = true;


        FP.init = function (slider) {
            this.start_index = 0;
            this.section_delay = U.IntMoreOr(slider.layout_params.section_delay, -1, 150);
            this.animation_duration = U.IntMoreOr(slider.layout_params.animation_duration, -1, 200);
            slider.triplex_has_text = this.get_has_text.bindToObjectWParam(this);
            slider.triplex_is_logo_text = this.get_is_logo_text.bindToObjectWParam(this);
            slider.triplex_get_image_text = this.get_cleared_text.bindToObjectWParam(this);
            this.init_style(slider);
            return this;
        };

        FP.get_has_text = function (x) {
            return x._triplex_has_text;
        };

        FP.get_is_logo_text = function (x) {
            return x._triplex_is_logo;
        };

        FP.get_cleared_text = function (x) {
            return x._triplex_text;
        };

        FP.get_templates = function () {
            return TEMPLATES;
        };

        FP.init_style = function (slider) {
            this.dimension_k = this.dimension_y / this.dimension_x;//~~ .5;
            this.dimension_i = this.dimension_y / (this.dimension_x * (2 / 3));

            var slider_height = (100 * this.dimension_k).toFixed(5);
            var styletext = ["#", slider.uid, ' .EveCoreSliderInner{ padding-top:', slider_height, "%}"];
            this.style = jQuery('<style type="text/css"></style>');
            this.style.html(styletext.join(''));
            this.style.appendTo('body');
            return this;
        };

        FP.prepare_images = function (slider) {
            var index = 0;
            var min_images = 6;
            if (slider.images.length < min_images) {
                while (slider.images.length < min_images) {
                    slider.images.push(slider.images[index].clone());
                    index++;
                }
            }
            var ap_style = [];
            for (var i = 0; i < slider.images.length; i++) {
                slider.images[i]._triplex_has_text = (U.NEString(slider.images[i].get_property('text'), null) ? true : false);
                if (slider.images[i]._triplex_has_text) {
                    var text = U.NEString(slider.images[i].get_property('text'), null);
                    slider.images[i]._triplex_is_logo = /\{\{logo\}\}/i.test(text);
                    var color = "#ffffff";
                    var m = /\{\{COLOR:(#[0-9a-f]{6})\}\}/i.exec(text);
                    m ? color = m[1] : 0;
                    ap_style.push(["#", slider.uid, " .Marker_", slider.images[i].image, " .EveCoreSliderImageTextOuter {color:", color, ";}"].join(''));
                    ap_style.push(["#", slider.uid, " .Marker_", slider.images[i].image, " .EveCoreSliderImageLogoOuter svg{fill:", color, ";}"].join(''));
                    var text_class = "center";
                    var m = /\{\{POS:(.{1,})\}\}/i.exec(text);
                    m ? text_class = m[1] : 0;
                    slider.images[i]._triplex_text_class = text_class;
                    slider.images[i]._triplex_text = U.NEString(text.replace(/\{\{(.*?)\}\}/ig, ''), null);
                    if (!slider.images[i]._triplex_is_logo) {
                        slider.images[i]._triplex_has_text = slider.images[i]._triplex_text ? true : false;
                    }
                }
            }
            if (ap_style.length) {
                this.style.append(ap_style.join(''));
            }
            return this;
        };

        FP.on_slide = function (slider) {
            if (window.stop_all_sliders) {
                slider.stop();
            }
            var tl = anime.timeline({
                easing: 'linear',
                duration: this.ki_first ? 0 : this.animation_duration
            });
            this.ki_first = false;
            tl.pause();
            if (this.start_index >= slider.images.length) {
                this.start_index = 0;
            }
            var p = this.start_index;
            this.start_index++;
            var new_big_slide = slider.images[p];
            p++;
            if (p >= slider.images.length) {
                p = 0;
            }
            var new_top_small_slide = slider.images[p];
            p++;
            if (p >= slider.images.length) {
                p = 0;
            }
            var new_bottom_small_slide = slider.images[p];
            this.big_slide ? this.big_slide.node.css("z-index", 1) : 0;
            tl.add({
                targets: new_big_slide.node.get(0),
                width: "66.6666%",
                top: "0%",
                right: "33.335%"
            }, 0);
            tl.add({
                targets: new_top_small_slide.node.get(0),
                width: "33.335%",
                top: ["49.9%", "0.05%"],
                right: "0%"
            }, 0);
            tl.add({
                targets: new_bottom_small_slide.node.get(0),
                width: "33.335%",
                top: ["100%", "49.95%"],
                right: "0%"
            }, 0);


            var self = this;
            tl.complete = function () {
                self.big_slide ? self.big_slide.node.get(0).style = "" : 0;//.css(''):0;
                self.big_slide = new_big_slide;
                self.top_small_slide = new_top_small_slide;
                self.bottom_small_slide = new_bottom_small_slide;
            };

            tl.play();
            if (window.AAAA) {
                slider.stop();
            }
        };

        FP.get_image_spec = function (slider) {
            var max_dim = Math.max(screen.width, screen.height);
            var image_width = U.IntMoreOr((max_dim / 3) * 2, 0, null);
            return {
                width: image_width,
                height: U.IntMoreOr((image_width * (this.dimension_i)), 0, null)
            };
        };

        E.SLIDER_CORE_LAYOUTS.push(function (x) {
            x.register_layout('triplex', layout);
        });

    }

})();