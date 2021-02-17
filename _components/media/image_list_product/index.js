(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.load('media.image_uploader').promise
    ];
    //</editor-fold>
    function initPlugin() {
        Y.load("media.image_uploader").done(par_ready);
    }
    function par_ready(PAR) {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        function F() {
            return  (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;

        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">        
        F.prototype.onInit = function () {
            PARP.onInit.apply(this, APS.call(arguments));
            this.color_override = {};
            return this;
        };

        F.prototype.getCssClass = function () {
            return PARP.getCssClass.apply(this, APS.call(arguments));
        };

        F.prototype.getMyClass = function () {
            return MC;
        };
        //</editor-fold>   
        F.prototype.get_parts_templates = function () {
            var result = PARP.get_parts_templates.apply(this, APS.call(arguments));
            result.one_image = EFO.TemplateManager().get('one_image', MC);
            result.image_color_selector = EFO.TemplateManager().get('image_color_selector', MC);
            return result;
        };

        F.prototype.set_colors_source = function (cs) {
            if (this.color_source) {
                this.color_source.LEM.off('CHANGE', this, this.color_list_changed);
            }
            var mce = Y.get_loaded_component('media.color_editor');
            if (mce && mce.is(cs)) {
                this.color_source = cs;
            } else {
                this.color_source = null;
            }
            if (this.color_source) {
                this.color_source.LEM.on('CHANGE', this, this.color_list_changed);
            }
            return this;
        };

        F.prototype.color_list_changed = function () {
            this.color_list = U.safeArray(U.safeObject(U.safeObject(this.color_source).color_list).items);
            this.update_colors_selectors();
            return this; //rerender on open?
        };

        F.prototype.update_colors_selectors = function () {
            var colors_html = Mustache.render(EFO.TemplateManager().get('color_list', MC), this);
            var selectors = this.handle.find('select');
            for (var i = 0; i < selectors.length; i++) {
                var selector = jQuery(selectors.get(i));
                var image_id = U.NEString(selector.data('image'), null);
                if (image_id) {
                    var color_val = this.get_selected_color_for(image_id);
                    selector.html(colors_html);
                    selector.val(color_val);
                }
            }
            return this;
        };

        F.prototype.get_selected_color_for = function (image_id) {// нужна проверка на существование цвета
            var e = U.NEString(this.color_override[image_id], null);
            var color_id = e;
            if (e && "fake" !== e) {
                color_id = this.check_color_exists(e);
            }
            if (!color_id) {
                var iml = U.safeArray(this.image_list);
                for (var i = 0; i < iml.length; i++) {
                    if (iml[i].image === image_id) {
                        color_id = this.check_color_exists(U.NEString(U.safeObject(U.safeObject(iml[i]).properties).color, "fake"));
                        break;
                    }
                }
            }
            return U.NEString(color_id, "fake");
        };

        F.prototype.check_color_exists = function (color_id) {
            var cl = U.safeArray(this.color_list);
            for (var i = 0; i < cl.length; i++) {
                if (cl[i].guid === color_id) {
                    return color_id;
                }
            }
            return null;
        };

        F.prototype.onMonitorColor_changed = function (t) {
            var image_id = U.NEString(t.data('image'), null);
            if (image_id) {
                var color_id = U.NEString(t.val(), "fake");
                this.color_override[image_id] = color_id;
            }
            return this;
        };

        F.prototype.set_color_source = F.prototype.set_colors_source;

        F.prototype.reset_temp_links = function () {
            this.color_override = {};
            return this;
        };

        F.prototype.get_images_params = function () {
            var result = {};
            var selectors = this.handle.find('select');
            for (var i = 0; i < selectors.length; i++) {
                var selector = jQuery(selectors.get(i));
                var image_id = U.NEString(selector.data('image'), null);
                if (image_id) {
                    var color_val = this.get_selected_color_for(image_id);
                    if ("fake" !== color_val) {
                        result[image_id] = {"color": color_val};
                    }
                }
            }
            return result;
        };

        F.prototype.clear = function () {
            this.color_override = {};
            return PARP.clear.apply(this, APS.call(arguments));
        };

        F.prototype.on_after_render_images = function () {
            this.update_colors_selectors();
            return PARP.on_after_render_images.apply(this, APS.call(arguments));
        };

        //<editor-fold defaultstate="collapsed" desc="misc &&callback">        
        Y.reportSuccess(FQCN, F);// конструктор, не инстанс
        //</editor-fold>
    }
    //<editor-fold defaultstate="collapsed" desc="dependecy resolver">
    if (imports.length) {
        window.Eve.EFO.EFOPromise.waitForArray(imports)
                .done(initPlugin)
                .fail(function () {
                    Y.report_fail(FQCN, "Ошибке при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();