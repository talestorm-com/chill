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
        var style = {"style":".EveCoreSlider.EveCoreSlider-product {\n    box-sizing: border-box;\n    width: 100%;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderInner {\n    box-sizing: border-box;\n    width: 100%;\n    display: flex;\n    flex-direction: row;\n    flex-wrap: nowrap;\n    justify-content: flex-start;\n    align-items: flex-start;\n    overflow: hidden;\n    max-width: 100%;\n    padding-top: 1em;\n}\n\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderImages {\n    width: 100%;\n    line-height: 0;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniatures {\n    width: 15%;\n    max-width: 15%;\n    min-width: 15%;\n    box-sizing: border-box;\n    padding: 0 .5em;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniature {\n    width: 100%;\n    box-sizing: border-box;\n    line-height: 0;\n    padding: .25em;\n    border: 1px solid silver;\n    overflow: hidden;\n    cursor: pointer;\n    margin-bottom: .5em;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniatureInner {\n    box-sizing: border-box;\n    width: 100%;\n    overflow: hidden;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniatureInner img {\n    width: 100%;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderImage {\n    width: 100%;\n    overflow: hidden;\n    border: 1px solid whitesmoke;\n    padding: .1em;\n    box-sizing: border-box;\n    margin-bottom: .25em;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderImage img {\n    width: 100%;\n    overflow: hidden;\n    box-sizing: border-box;\n}\n\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniature {\n    border: none;\n    padding: 0;\n    width: 115px;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniatures {\n    width: 125px;\n    max-width: 125px;\n    min-width: 125px;\n    padding: .1em 10px 0 0;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderMiniature {\n    margin-bottom: .6em;\n}\n\n\nbody.slider_product_fullview_visible {\n    box-sizing: border-box;\n    height: 100%;\n    overflow: hidden;\n}\n\n.CoreSliderProductImageViewWrapper {\n    box-sizing: border-box;\n    position: fixed;\n    top: 0;\n    left: 0;\n    width: 100%;\n    height: 100%;\n    overflow: hidden;\n    z-index: 3;\n}\n\n.CoreSliderProductImageViewInner {\n    box-sizing: border-box;\n    height: 100%;\n    width: 100%;\n    overflow: auto;\n    \/* position: relative; *\/\n}\n\n.CoreSliderProductImageViewImage {\n    box-sizing: border-box;\n    width: 100%;\n}\n\n.CoreSliderProductImageViewImage img {\n    width: 100%;\n}\n\n.CoreSliderProductImageViewSApinner {\n    box-sizing: border-box;\n    background: transparent;\n    width: 100%;\n    height: 100%;\n    position: absolute;\n    top: 0;\n    left: 0;\n    pointer-events: none;\n    background: rgba(255,255,255,0);\n}\n\n.CoreSliderProductImageViewSApinner {\n    display: flex;\n    flex-direction: row;\n    justify-content: center;\n    align-items: center;\n}\n\n.CoreSliderProductImageViewSApinnerInner {\n    width: 5em;\n    height: 5em;\n}\n\n.CoreSliderProductImageViewSApinnerInner svg {\n    width: 100%;\n    height: 100%;\n}\n\n.CoreSliderProductImageViewSApinner {\n    display: flex;\n    flex-direction: row;\n    justify-content: center;\n    align-items: center;\n    display:none;\n}\n\n.CoreSliderProductImageViewSApinnerInner {\n    box-sizing: border-box;\n    width: 4em;\n    height: 4em;    \n}\n\n.CoreSliderProductImageViewSApinnerInner svg {\n    width: 100%;\n    height: 100%;\n}\n\n.CoreSliderProductImageViewClose {\n    position: absolute;\n    top: 1em;\n    right: 1em;\n    cursor: pointer;\n}\n\n.CoreSliderProductImageViewCloseBt {\n    background: white;\n    padding: .25em 1em;\n    border-radius: 5%;\n    box-shadow: 0px .1em .4em 0 black;\n}\n\n\n\n.CoreSliderProductImageViewInner {\n    background: white;\n}\n\n.CoreSliderProductImageViewNavigation {\n    box-sizing: border-box;\n    position: absolute;\n    bottom: 0;\n    left: 0;\n    display: block;\n    width: 100%;\n}\n\n.CoreSliderProductImageViewNavigationInner {\n    box-sizing: border-box;\n    width: 100%;\n    padding: .25em .5em;\n    display: flex;\n    flex-direction: row;\n    justify-content: center;\n    align-items: center;\n}\n\n.CoreSliderProductImageViewNavigationInner a {\n    line-height: 0;\n    width: .75em;\n    height: .75em;\n    margin: 0 .75em;\n    background: white;\n    border-radius: 50%;\n    margin-bottom: 1em;\n    border: 1px solid black;\n}\n\na.CoreSliderProductLayoutImageSelectedPin {\n    background: #f15a24;\n}\n\n.CoreSliderProductImageViewInner{\n    cursor:url('\/assets\/cursor\/zoom-out.png'),auto;\n}\n\n.EveCoreSlider.EveCoreSlider-product .EveCoreSliderImage{\n    cursor:url('\/assets\/cursor\/zoom-in-2.png'),auto;\n}"};
        var TEMPLATES = {"image":"<div class=\"EveCoreSliderImage Marker_{{image}}\" data-image=\"{{image}}\" data-index=\"{{index}}\" data-slider-layout-command=\"show_image\">    \n    <img src=\"{{image_url}}\" title=\"{{title}}\" \/>    \n<\/div>","image_view":"<div class=\"CoreSliderProductImageViewWrapper\">\n    <div class=\"CoreSliderProductImageViewInner\">\n        <div class=\"CoreSliderProductImageViewClose\" data-command=\"close\"><div class=\"CoreSliderProductImageViewCloseBt\">\u0417\u0430\u043a\u0440\u044b\u0442\u044c<\/div><\/div>\n        <div class=\"CoreSliderProductImageViewImage\" data-command=\"close\"><img data-role=\"image\" src=\"void(0)\"\/><\/div>\n        <div class=\"CoreSliderProductImageViewSApinner\" data-role=\"loader\">\n            <div class=\"CoreSliderProductImageViewSApinnerInner\">\n                <?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><svg xmlns:svg=\"http:\/\/www.w3.org\/2000\/svg\" xmlns=\"http:\/\/www.w3.org\/2000\/svg\" xmlns:xlink=\"http:\/\/www.w3.org\/1999\/xlink\" version=\"1.0\" width=\"100px\" height=\"100px\" viewBox=\"0 0 128 128\" xml:space=\"preserve\"><g><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#000000\" fill-opacity=\"1\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#cccccc\" fill-opacity=\"0.2\" transform=\"rotate(30 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#cccccc\" fill-opacity=\"0.2\" transform=\"rotate(60 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#cccccc\" fill-opacity=\"0.2\" transform=\"rotate(90 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#cccccc\" fill-opacity=\"0.2\" transform=\"rotate(120 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#b2b2b2\" fill-opacity=\"0.3\" transform=\"rotate(150 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#999999\" fill-opacity=\"0.4\" transform=\"rotate(180 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#7f7f7f\" fill-opacity=\"0.5\" transform=\"rotate(210 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#666666\" fill-opacity=\"0.6\" transform=\"rotate(240 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#4c4c4c\" fill-opacity=\"0.7\" transform=\"rotate(270 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#333333\" fill-opacity=\"0.8\" transform=\"rotate(300 64 64)\"\/><path d=\"M59.6 0h8v40h-8V0z\" fill=\"#191919\" fill-opacity=\"0.9\" transform=\"rotate(330 64 64)\"\/><animateTransform attributeName=\"transform\" type=\"rotate\" values=\"0 64 64;30 64 64;60 64 64;90 64 64;120 64 64;150 64 64;180 64 64;210 64 64;240 64 64;270 64 64;300 64 64;330 64 64\" calcMode=\"discrete\" dur=\"960ms\" repeatCount=\"indefinite\"><\/animateTransform><\/g><\/svg>\n            <\/div>\n        <\/div>\n        <div class=\"CoreSliderProductImageViewNavigation\">\n            <div class=\"CoreSliderProductImageViewNavigationInner\" data-role=\"nav_block\"><\/div>\n        <\/div>\n    <\/div>\n    <div style=\"display:none\">\n        <svg version=\"1.1\" id=\"CoreSliderProductImageViewCursorZoomOut\" xmlns=\"http:\/\/www.w3.org\/2000\/svg\" xmlns:xlink=\"http:\/\/www.w3.org\/1999\/xlink\" x=\"0px\" y=\"0px\"\n             viewBox=\"0 0 52.966 52.966\" style=\"enable-background:new 0 0 52.966 52.966;\" xml:space=\"preserve\">\n            <g>\n                <path d=\"M28.983,20h-14c-0.552,0-1,0.448-1,1s0.448,1,1,1h14c0.552,0,1-0.448,1-1S29.535,20,28.983,20z\"\/>\n                <path d=\"M51.704,51.273L36.845,35.82c3.79-3.801,6.138-9.041,6.138-14.82c0-11.58-9.42-21-21-21s-21,9.42-21,21s9.42,21,21,21\n                      c5.083,0,9.748-1.817,13.384-4.832l14.895,15.491c0.196,0.205,0.458,0.307,0.721,0.307c0.25,0,0.499-0.093,0.693-0.279\n                      C52.074,52.304,52.086,51.671,51.704,51.273z M2.983,21c0-10.477,8.523-19,19-19s19,8.523,19,19s-8.523,19-19,19\n                      S2.983,31.477,2.983,21z\"\/>\n            <\/g>\n        <\/svg>\n    <\/div>\n<\/div>","miniature":"<div class=\"EveCoreSliderMiniature Marker_{{image}}\" data-image=\"{{image}}\" data-index=\"{{index}}\" data-slider-layout-command=\"nav\">    \n    <div class=\"EveCoreSliderMiniatureInner\">    \n        <img src=\"{{miniature_url}}\" title=\"{{title}}\" \/>    \n    <\/div>\n<\/div>","nav_view":"{{#do_display_nav}}\n{{#images}}\n<a href=\"#\" data-image=\"{{image}}\" data-command=\"select_image\" class=\"{{#is_current_image}}CoreSliderProductLayoutImageSelectedPin{{\/is_current_image}}\"><\/a>\n{{\/images}}\n{{\/do_display_nav}}","wrapper":"<div class=\"EveCoreSlider EveCoreSlider-{{layout_name}}\" id=\"{{uid}}\">\n    <div class=\"EveCoreSliderInner\">\n        <div class=\"EveCoreSliderMiniatures\" id=\"miniatures_{{uid}}\">\n            {{#images}}\n            {{>miniature}}\n            {{\/images}}\n        <\/div>\n        <div class=\"EveCoreSliderImages\">\n            {{#images}}\n            {{>image}}\n            {{\/images}}\n        <\/div>\n    <\/div>\n<\/div>"};
        jQuery(['<style type="text/css" data-id="slider.layout_simple">', style.style, '</style>'].join('')).appendTo('body');
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

            return this;
        };

        FP.prepare_images = function (slider) {
            for (var i = 0; i < slider.images.length; i++) {
                slider.images[i].miniature_url = slider.images[i].url_prefix + ".SW_115H_173CF_1B_ffffff.jpg";
            }
            return this;
        };

        FP.on_slide = function (slider) {

        };

        FP.get_image_spec = function (slider) {
            return {
                width: U.IntMoreOr(screen.width / 2, 0, null)
            };
        };

        FP.on_render_complete = function (slider) {
            var id = "miniatures_" + slider.uid;
            window.Eve.scroll_fix_ready = window.Eve.scroll_fix_ready || [];
            window.Eve.scroll_fix_ready.push(function () {
                window.Eve.scroll_fix(id, jQuery('.BeforeFooterOffset:first').get(0), jQuery('.FrontLayoutPageHeader').get(0), 0, 16);
            });
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
            return this;
        };

        FP.on_command_show_image = function (n, e, s) {
            var image_id = U.NEString(n.data('image'), null);
            if (image_id) {
                if (!this.image_view) {
                    this.image_view = image_view();
                }
                this.image_view.setup(s, image_id).show();
            }
            return this;
        };


        FP.layout_command_order_by_color = function (p, slider) {
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




        function image_view() {
            return (image_view.is(this) ? this.init : image_view.F).apply(this, APS.call(arguments));
        }
        var IVP = U.FixCon(image_view).prototype;

        IVP.images = null;
        IVP.handle = null;
        IVP.image_container = null;
        IVP.loader = null;
        IVP.nav_block = null;
        IVP.current_image_index = null;

        IVP.init = function () {
            this.handle = jQuery(Mustache.render(TEMPLATES.image_view, this));
            this.image_container = this.handle.find("[data-role=\"image\"]");
            this.loader = this.handle.find("[data-role=\"loader\"]");
            this.nav_block = this.handle.find("[data-role=\"nav_block\"]");
            this.handle.on('click', "[data-command]", this.on_command.bindToObjectWParam(this));
            this.do_display_nav = this._do_display_nav.bindToObject(this);
            this.is_current_image = this._is_current_image.bindToObjectWParam(this);
            try {
                this.image_container.get(0).addEventListener('load', this.hide_loader.bindToObject(this), true);
            } catch (e) {

            }
            return this;
        };

        IVP._do_display_nav = function () {
            return (U.isArray(this.images) && this.images.length > 1) ? true : false;
        };

        IVP._is_current_image = function (ci) {            
            var cu_id = this.images[this.current_image_index].image;
            return ci.image === cu_id;
        };


        IVP.on_command = function (n, e) {
            var jn = jQuery(n);
            var cmd = U.NEString(jn.data('command'), null);
            if (cmd) {
                var fn = ["on_command_", cmd.toLowerCase()].join('');
                if (U.isCallable(this[fn])) {
                    this[fn](jn, e);
                }
            }
            return this;
        };
        IVP.on_command_select_image = function (jn, e) {
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            this.setup(this, U.NEString(jn.data('image'), null)).show();
            return this;
        };

        IVP.on_command_close = function () {
            this.hide();
            return this;
        };


        IVP.setup = function (slider, selected_image) {            
            this.images = [].concat(slider.images);
            this.current_image_index = 0;
            for (var i = 0; i < this.images.length; i++) {
                if (this.images[i].image === selected_image) {
                    this.current_image_index = i;
                    break;
                }
            }
            this.nav_block.html(Mustache.render(TEMPLATES.nav_view, this, TEMPLATES));
            return this;
        };


        IVP.get_image_spec = function () {
            var ww = document.documentElement.clientWidth;
            var ww5 = Math.ceil(ww / 500);
            var rw = ww5 * 500;
            return ["SW_", rw, "CF_1"].join('');
        };

        IVP.get_current_image_url = function () {
            var image = this.images[this.current_image_index];
            return ["/media/", image.context, "/", image.owner_id, "/", image.image, ".", this.get_image_spec(), ".jpg"].join('');
        };


        IVP.show = function () {
            if (U.isArray(this.images)) {
                this.handle.appendTo('body');
                this.handle.show();
                jQuery('body').addClass('slider_product_fullview_visible');
                this.show_loader();
                this.image_container.attr("src", this.get_current_image_url());
                this.image_container.show();
            }
            return this;
        };

        IVP.hide = function () {
            this.handle.hide();
            this.image_container.hide();
            jQuery('body').removeClass('slider_product_fullview_visible');
            this.images = null;
            return this;
        };

        IVP.show_loader = function () {
            this.loader.css("display", "flex");
            return this;
        };
        IVP.hide_loader = function () {
            this.loader.css("display", "none");
            return this;
        };

        E.SLIDER_CORE_LAYOUTS.push(function (x) {
            x.register_layout('product', layout);
        });

    }

})();