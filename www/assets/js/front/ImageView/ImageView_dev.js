(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);

    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice, H = null;
        var TEMPLATES = {};
        /*HERE_TEMPLATES*/;
        var STYLES = {};
        /*HERE_STYLES*/;
        var style = jQuery('<style type="text/css" data-id="ImageView"></style>');
        jQuery('head').append(style);
        style.html(U.NEString(STYLES.style, ''));
        // FP.on_command_show_image = function (n, e, s) {
        //     var image_id = U.NEString(n.data('image'), null);
        //     if (image_id) {
        //         if (!this.image_view) {
        //             this.image_view = image_view();
        //         }
        //         this.image_view.setup(s, image_id).show();
        //     }
        //     return this;
        // };

        function image_view() {
            return image_view.is(H) ? H : ((image_view.is(this) ? this.init : image_view.F).apply(this, APS.call(arguments)));
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
            this.setup(this.images, U.NEString(jn.data('image'), null)).show();
            return this;
        };

        IVP.on_command_close = function () {
            this.hide();
            return this;
        };


        IVP.setup = function (images, selected_image) {
            images = U.safeArray(images);
            this.images = [];
            for (var i = 0; i < images.length; i++) {
                var image = new IVImage(images[i]);
                if (image && image.is_valid()) {
                    this.images.push(image);
                }
            }
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
                jQuery('body').addClass('imageview_fullview_visible');
                this.show_loader();
                this.image_container.attr("src", this.get_current_image_url());
                this.image_container.show();
            }
            return this;
        };

        IVP.hide = function () {
            this.handle.hide();
            this.image_container.hide();
            jQuery('body').removeClass('imageview_fullview_visible');
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


        function IVImage() {
            return (IVImage.is(this) ? this.init : IVImage.F).apply(this, APS.call(arguments));
        }
        var IP = U.FixCon(IVImage).prototype;

        IP.context = null;
        IP.owner_id = null;
        IP.image = null;
        IP.title = null;

        IP.init = function (x) {
            x = U.safeObject(x);
            this.context = U.NEString(x.context, null);
            this.owner_id = U.NEString(x.owner_id, null);
            this.image = U.NEString(x.image, null);
            this.title = U.NEString(x.title, null);
            return this;
        };

        IP.is_valid = function () {
            return (this.context && this.owner_id && this.image) ? true : false;
        };


        E.image_view = image_view;
        E.image_view_ready = E.image_view_ready || [];
        var b = U.safeArray(E.image_view_ready);
        E.image_view_ready = {
            push: function () {
                var args = APS.call(arguments);
                for (var i = 0; i < args.length; i++) {
                    if (U.isCallable(args[i])) {
                        try {
                            args[i]();
                        } catch (e) {
                            U.TError(e);
                        }
                    }
                }
            }
        };
        for (var i = 0; i < b.length; i++) {
            E.image_view_ready.push(b[i]);
        }
    }

})();