(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready1);
    function ready1() {
        jQuery(ready);
    }
    function ready() {
        window.ANIME_READY = window.ANIME_READY || [];
        window.ANIME_READY.push(ready2);
    }
    function ready2() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice, LMH = null;
        window.Eve.SLIDER_CORE_LAYOUTS = window.Eve.SLIDER_CORE_LAYOUTS || [];
        var T = {"image": "<div class=\"EveCoreSliderImage Marker_{{image}}\" data-image=\"{{image}}\" data-index=\"{{index}}\">\n    {{#has_link}}<a href=\"{{link}}\">{{\/has_link}}\n        <img src=\"{{image_url}}\" title=\"{{title}}\" \/>\n    {{#has_link}}<\/a>{{\/has_link}}\n<\/div>", "wrapper": "<div class=\"EveCoreSlider EveCoreSlider-{{layout_name}}\" id=\"{{uid}}\">\n    <div class=\"EveCoreSliderInner\">\n        {{#images}}\n        {{>image}}\n        {{\/images}}\n    <\/div>\n<\/div>"};
        //<editor-fold defaultstate="collapsed" desc="layout manager">
        function layout_manager() {
            return layout_manager.is(LMH) ? LMH : ((layout_manager.is(this) ? this.init : layout_manager.F).apply(this, APS.call(arguments)));
        }
        var LMP = U.FixCon(layout_manager).prototype;

        LMP._layouts = null;

        LMP.init = function () {
            LMH = this;
            this._layouts = {};
            var c = [].concat(window.Eve.SLIDER_CORE_LAYOUTS);
            window.Eve.SLIDER_CORE_LAYOUTS = this;
            for (var i = 0; i < c.length; i++) {
                this.push(c[i]);
            }
            return this;
        };

        LMP.push = function () {
            var args = U.safeArray(APS.call(arguments));
            for (var i = 0; i < args.length; i++) {
                if (U.isCallable(args[i])) {
                    try {
                        args[i](this);
                    } catch (e) {
                        U.TError(e);
                    }
                }
            }
        };
        LMP.register_layout = function (layout_name, layout_func) {
            layout_name = U.NEString(layout_name, null);
            layout_func = U.isCallable(layout_func) ? layout_func : null;
            if (layout_name && layout_func) {
                if ((!layout_defer.is(this._layouts[layout_name]))) {
                    var ld = layout_defer(layout_name, layout_func);
                    this._layouts[ld.name] = ld;
                } else if ((layout_defer.is(this._layouts[layout_name]) && !this._layouts[layout_name].resolved)) {
                    this._layouts[layout_name].resolve(layout_func);
                }
            }
            return this;
        };
        LMP.get_layout = function (layout_name, co, ca, ta) {
            if (!layout_defer.is(this._layouts[layout_name])) {
                this._layouts[layout_name] = layout_defer(layout_name, null);
            }
            this._layouts[layout_name].register_listener(co, ca, ta);
        };
        //<editor-fold defaultstate="collapsed" desc="layout_defer">
        function layout_defer() {
            return (layout_defer.is(this) ? this.init : layout_defer.F).apply(this, APS.call(arguments));
        }
        var LDP = U.FixCon(layout_defer).prototype;
        LDP.name = null;
        LDP.layout_func = null;
        LDP.listeners = null;
        LDP.resolved = false;
        LDP.init = function (ln, lf) {
            this.name = U.NEString(ln, null);
            this.layout_func = U.isCallable(lf) ? lf : null;
            this.resolved = (this.layout_func ? true : false);
            return this;
        };
        LDP.resolve = function (lf) {
            if (!this.layout_func) {
                this.layout_func = U.isCallable(lf) ? lf : null;
                if (this.layout_func) {
                    this.resolved = true;
                    var c = U.safeArray(this.listeners);
                    for (var i = 0; i < c.length; i++) {
                        layout_def_callback.is(c[i]) ? c[i].run(this.layout_func) : 0;
                    }
                    this.listeners = null;
                }
            }
        };

        LDP.register_listener = function (aco, aca) {
            if (!this.resolved) {
                U.isArray(this.listeners) ? 0 : this.listeners = [];
                this.listeners.push(layout_def_callback(aco, aca));
                return;
            }
            layout_def_callback(aco, aca).run(this.layout_func);
        };
        //<editor-fold defaultstate="collapsed" desc="layout defer calback">
        function layout_def_callback() {
            return (layout_def_callback.is(this) ? this.init : layout_def_callback.F).apply(this, APS.call(arguments));
        }
        var CP = U.FixCon(layout_def_callback).prototype;
        CP._context = null;
        CP._callable = null;
        CP.init = function (co, ca) {
            this._context = (U.isObject(co) ? co : (U.isObject(ca) ? ca : null));
            this._callable = (U.isCallable(ca) ? ca : (U.isCallable(co) ? co : null));
            return this;
        };

        CP.run = function () {
            var co = U.isObject(this._context) ? this._context : this;
            if (U.isCallable(this._callable)) {
                try {
                    this._callable.apply(co, APS.call(arguments));
                } catch (e) {
                    U.TError(e);
                }
            }
        };
        //</editor-fold>
        //</editor-fold>
        //</editor-fold>
        var instances = {};

        function slider(id) {
            var xid = U.NEString(id, null);
            return (xid && slider.is(instances[xid])) ? instances[xid] : ((slider.is(this) ? this.init : slider.F).apply(this, APS.call(arguments)));
        }
        var FP = U.FixCon(slider).prototype;

        FP.id = null;
        FP.uid = null;
        FP.timeout = null;
        FP.images = null;
        FP.layout_name = null;
        FP.layout = null;
        FP.root = null;
        FP.crop_fill = false;
        FP.images_objects = null;
        FP.crop = true;
        FP.background = null;
        FP.layout_params = null;

        FP.fallback_hidden = null;
        FP.fallback_event = null;

        FP.random_seed = function () {
            return ['r', U.UUID().replace(/-/g, ''), U.UUID().replace(/-/g, '')].join('');
        };

        FP.init = function (id, layout, images, timeout, crop, crop_fill, background, layout_params) {
            this.id = U.NEString(id, null);
            instances[this.id] = this;
            this.uid = [this.random_seed(), this.id].join('');
            this.layout_name = U.NEString(layout, 'simple');
            var imga = U.safeArray(images);
            this.timeout = U.IntMoreOr(timeout, 0, 5000);
            this.root = jQuery(document.getElementById(this.id));
            this.root.on('click', '[data-slider-layout-command]', this.on_layout_command.bindToObjectWParam(this));
            try {
                this.root.get(0).addEventListener('error', this.on_img_error.bindToObject(this), true);
            } catch (e) {
                U.TError(e);
            }
            if (typeof document.hidden !== "undefined") {
                this.fallback_hidden = "hidden";
                this.fallback_event = "visibilitychange";
            } else if (typeof document.msHidden !== "undefined") {
                this.fallback_hidden = "msHidden";
                this.fallback_event = "msvisibilitychange";
            } else if (typeof document.webkitHidden !== "undefined") {
                this.fallback_hidden = "webkitHidden";
                this.fallback_event = "webkitvisibilitychange";
            }
            this.crop = U.anyBool(crop, true);
            this.crop_fill = U.anyBool(crop_fill, false);
            this.background = U.NEString(background, '');
            this.background = U.NEString(this.background.replace(/^#/g, ''), null);
            this.layout_params = U.safeObject(layout_params);
            this.images = [];
            for (var i = 0; i < imga.length; i++) {
                var im = img(imga[i]);
                im && img.is(im) && im.is_valid() ? this.images.push(im) : 0;
            }
            this.has_link = this._has_link.bindToObjectWParam(this);
            if (this.images.length) {
                this.reindex_images();
                layout_manager().get_layout(this.layout_name, this, this.on_layout_ready);
            } else {
                this.root.hide();
            }
            try {
                if (this.fallback_event) {
                    document.addEventListener(this.fallback_event, this.on_visibility_change.bindToObject(this), false);
                }
            } catch (e) {

            }
            return this;
        };

        FP.on_layout_command = function (t, e) {
            var jt = jQuery(t);
            var command = U.NEString(jt.data('sliderLayoutCommand'), '').toLowerCase();
            var fn = ["on_command_", command].join('');
            if (U.isCallable(this.layout[fn])) {
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                e.stopPropagation();
                this.layout[fn](jt, e, this);
            }
            return this;
        };

        FP.on_visibility_change = function () {
            if (document[this.fallback_hidden]) {
                this.stop();
            } else {
                this.play();
            }
        };

        FP.exec_layout_command = function (command, params) {
            params = U.safeObject(params);
            command = U.NEString(command, null);
            if (command) {
                if (U.isCallable(this.layout.exec_command)) {
                    try {
                        this.layout.exec_command(command, params, this);
                    } catch (ee) {
                        U.TError(ee);
                    }
                }
            }
            return this;
        };


        FP._has_link = function (x) {
            return x.has_link();
        };

        FP.on_img_error = function (e) {
            try {
                if (e.srcElement.nodeName === 'IMG') {
                    var id = U.NEString(jQuery(e.srcElement).data('image'), null);
                    if (id) {
                        //this.root.find('.Marker_' + id).remove();

//                        for (var i = 0; i < this.images.length; i++) {
//                            if (this.images[i].image === id) {
//                                this.images[i].invalid = true;
//                            }                            
//                            if (!this.images.length) {
//                                this.root.hide();
//                                window.clearInterval(this.INT_HD);
//                                this.INT_HD = null;
//                            }
//                        }
//                        this.images = ti;
                    }
                }
            } catch (e) {
                U.TError(e);
            }
        };

        FP.on_layout_ready = function (lf) {
            this.layout = lf(this);
            this.layout.prepare_images(this);

            this.image_spec = this.layout.get_image_spec(this);
            for (var i = 0; i < this.images.length; i++) {
                this.images[i].image_url = this.images[i].get_url(this.image_spec.width, this.image_spec.height, this.use_crop, this.crop_fill, this.background);
            }
            this.reindex_images();
            this.render_images();

            this.update_nodes_index();
            this.on_slide();
            this.play();
            return this;
        };

        FP.reindex_images = function () {
            for (var i = 0; i < this.images.length; i++) {
                this.images[i].index = i;
            }
        };

        FP.update_nodes_index = function () {
            var self = this;
            for (var i = 0; i < this.images.length; i++) {
                this.images[i].node = null;
            }
            this.root.find('.EveCoreSliderImage').each(function () {
                var imn = jQuery(this);
                var index = U.IntMoreOr(imn.data('index'), -1, null);
                if (null !== index && self.images[index]) {
                    self.images[index].node = imn;
                    self.images[index].image_node = imn.find('img');
                }
            });
        };


        FP.render_images = function () {
            var templates = U.isCallable(this.layout.get_templates) ? this.layout.get_templates() : T;
            var html = Mustache.render(templates.wrapper, this, templates);
            this.root.html(html);
            if (U.isCallable(this.layout.on_render_complete)) {
                this.layout.on_render_complete(this);
            }
            return this;
        };

        FP.on_slide = function () {
            try {
                this.layout.on_slide(this);
            } catch (e) {

            }
            return this;
        };

        FP.stop = function () {
            window.clearInterval(this.INT_HD);
            this.INT_HD = null;
        };

        FP.play = function () {
            this.stop();
            this.INT_HD = window.setInterval(this.on_slide.bindToObject(this), this.timeout);
        };


        slider.get_slider_instance = function (id) {
            id = U.NEString(id, null);
            if (id) {
                return slider.is(instances[id]) ? instances[id] : null;
            }
            return null;
        };





        function img() {
            return (img.is(this) ? this.init : img.F).apply(this, APS.call(arguments));
        }
        var IP = U.FixCon(img).prototype;


        IP.context = null;
        IP.owner_id = null;
        IP.image = null;
        IP.title = null;
        IP.link = null;
        IP.url_prefix = null;
        IP.image_url = null;
        IP.index = null;
        IP.node = null;
        IP.properties = null;

        IP.init = function (x) {
            x = U.safeObject(x);
            this.context = U.NEString(x.context, null);
            this.owner_id = U.NEString(x.owner_id, null);
            this.image = U.NEString(x.image, null);
            this.title = U.NEString(x.title, null);
            this.link = U.NEString(U.safeObject(x.properties).url, null);
            this.url_prefix = ["/media", this.context, this.owner_id, this.image].join("/");
            try {
                this.properties = JSON.parse(JSON.stringify(U.safeObject(x.properties)));
            } catch (ee) {
                this.properties = {};
            }

            return this;
        };

        IP.clone = function () {
            return img(this);
        };

        IP.is_valid = function () {
            return !!(this.context && this.owner_id && this.image);
        };

        IP.get_property = function (x, def) {
            return this.properties.hasOwnProperty(x) ? this.properties[x] : def;
        };

        IP.get_url = function (width, height, use_crop, crop_fill, background) {
            var spec = ["S"];
            width = U.IntMoreOr(width, 0, null);
            width ? spec.push(["W_", width].join('')) : 0;
            height = U.IntMoreOr(height, 0, null);
            height ? spec.push(["H_", height].join('')) : 0;
            use_crop = U.anyBool(use_crop, true);
            use_crop ? 0 : spec.push("C_0");
            if (use_crop) {
                crop_fill = U.anyBool(crop_fill, false);
                crop_fill ? spec.push("CF_1") : 0;
            }
            background = U.NEString(background, null);
            background ? spec.push(["B_", background].join('')) : 0;
            return [this.url_prefix, spec.join(''), 'jpg'].join('.');
        };

        IP.has_link = function () {
            return this.link ? true : false;
        };

        slider.image = IP;


        //<editor-fold defaultstate="collapsed" desc="readyblock">
        E.SLIDER_CORE = slider;
        E.SLIDER_CORE_READY = E.SLIDER_CORE_READY || [];
        var cx = [].concat(E.SLIDER_CORE_READY);
        E.SLIDER_CORE_READY = {
            push: function (x) {
                if (U.isCallable(x)) {
                    try {
                        x();
                    } catch (e) {
                        U.TError(e);
                    }
                }
            }
        };
        for (var i = 0; i < cx.length; i++) {
            E.SLIDER_CORE_READY.push(cx[i]);
        }
        //</editor-fold>
    }
})();