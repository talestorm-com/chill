(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>', MEDIA_CONTEXT = '<?= \Content\TrainingHall\TrainingHall::MEDIA_CONTEXT?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise,
        Y.js("https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"),
        Y.css("https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css")
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC);// префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        var SVG = null;
        /*<?=$this->create_svg('SVG')?>*/
        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable', 'Tabbable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        //<editor-fold defaultstate="collapsed" desc="sizeable">
        F.prototype.sizeable_getParams = function () {
            var w = this.sizeable_defaultWidth();
            var h = this.sizeable_defaultHeight();
            try {
                var u = JSON.parse(localStorage.getItem(MC + 'rsz'));
                if (U.isObject(u) && ('w' in u) && ('h' in u) && typeof (u.h) === 'number' && !isNaN(u.h) && typeof (u.w) === 'number' && !isNaN(u.w)) {
                    w = u.w;
                    h = u.h;
                }
            } catch (e) {
                w = this.sizeable_defaultWidth();
                h = this.sizeable_defaultHeight();
            }
            return {w: w, h: h};
        };

        F.prototype.sizeable_setParams = function (x) {
            if (U.isObject(x)) {
                if (('w' in x) && ('h' in x) && typeof (x.w) === 'number' && typeof (x.h) === 'number' && !isNaN(x.w) && !isNaN(x.h)) {
                    try {
                        localStorage.setItem(MC + 'rsz', JSON.stringify(x));
                    } catch (e) {

                    }
                }
            }
            return this;
        };
        F.prototype.sizeable_scaleFactor = function () {
            return 2;
        };
        //</editor-fold>
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            this.init_ext_editors();
            this.get_feature_icon = this._get_feature_icon.bindToObjectWParam(this);
            return this;
        };

        F.prototype._get_feature_icon = function (x) {

            return "/assets/features/" + (U.NEString(x.image, "default.png"));
        };


        F.prototype.init_ext_editors = function () {
            var UF = Y.get_loaded_component('media.image_uploader');
            this.image_list = UF();
            this.image_list.set_params(MEDIA_CONTEXT, null);
            this.image_list.setContainer(this.getRole("image-list"));

            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };





        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            return this;
        };

        F.prototype.onBeforeHide = function () {
            return PARP.onBeforeHide.apply(this, APS.call(arguments));
        };
        F.prototype.getContentTemplate = function () {
            return EFO.TemplateManager().get([MC, 'Main'].join('.'));
        };

        F.prototype.getControllerAlias = function () {
            return MC;
        };

        F.prototype.getCssClass = function () {
            return MC;
        };

        F.prototype.getFooterButtons = function () {
            return [
                {'command': "cancel", 'text': "Отмена"},
                {'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Редактирование фитнес-зала";
        };

        F.prototype.enumSubTemplates = function () {
            var r = [].concat(
                    PARP.enumSubTemplates.call(this),
                    [
                        MC + ".TAB_main"
                                , MC + ".TAB_images"
                                , MC + ".TAB_properties"
                    ]);
            return r;
        };

        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/TrainingHall/API', {action: "get", id: id})
                        .done(this.on_data_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.load_metadata();
            }
            return this;
        };

        F.prototype.load_metadata = function () {
            jQuery.getJSON('/admin/TrainingHall/API', {action: "meta"})
                    .done(this.on_data_responce_meta.bindToObject(this))
                    .fail(this.on_network_fail_fatal.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
        };
        F.prototype.on_data_responce_meta = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_meta_success(U.safeObject(d.meta));
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_meta_success = function (mo) {
            this.init_dadata(U.NEString(mo.dadata_key, null));
            return this;
        };

        F.prototype.init_dadata = function (key) {
            if (!this.dadata) {
                this.getField('address').suggestions({
                    token: key,
                    type: "ADDRESS",
                    floating: true,
                    /* Вызывается, когда пользователь выбирает одну из подсказок */
                    onSelect: function (suggestion) {
                        console.log(suggestion);
                    }
                });
                this.dadata = true;
            }
            return this;
        };


        F.prototype.on_data_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this.on_meta_success(U.safeObject(d.meta));
                    this.on_data_success(U.safeObject(d.hall));
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_network_fail_fatal = function () {
            return this.on_network_fail.apply(this, APS.call(arguments)).hide().clear();
        };

        F.prototype.on_network_fail = function (m) {
            U.TError(m);
            return this;
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            return this;
        };


        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">
        F.prototype.onMonitorPhone = function (t) {
            t.val(U.NEString(EFO.Checks.formatPhone(t.val()), t.val()));
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="cancel command">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="login command">
        F.prototype.onCommandApply = function () {
            this.save(true);
            return this;
        };



        F.prototype.onNetworkFail = function (x) {
            var msg = "NetworkError";
            if (U.isError(x)) {
                msg = x.message;
            } else if (U.NEString(x, null)) {
                msg = x;
            } else if (U.isObject(x) && U.NEString(x.statusText, null)) {
                msg = x.statusText;
            }
            this.showError(msg);
            return this;
        };

        F.prototype.showError = function (tx) {
            new EveFlash({cssclass: "red", ICON: "stop", IMAGE: "stop", TO: 5000, CLOSE: false, TITLE: "Ошибка", TEXT: tx});
            return this;
        };

        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="restore command">
        F.prototype.onCommandSave = function () {
            //var df = EFO.Filter.Filter().applyFiltersToHash(this.getFields(), this.getFilters().getSectionExport('auth'));
            this.save(false);
            return this;
        };


        //</editor-fold>
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="save">             
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open, true);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('user'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (ee) {
                U.Error([MC, ee.message].join(':'));
            }
            this.showLoader();
            jQuery.post('/admin/TrainingHall/API', {action: 'post', data: JSON.stringify(data)})
                    .done(this.on_post_result.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_post_result = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.hall));
                    this.runCallback();
                    if (!this._keep_open) {
                        this.hide().clear();
                    }
                    return this;
                }
                if (d.status === "error") {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };


        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="feature manipulation">
        F.prototype.onCommandAdd_feature = function () {
            this.features.push({'feature_id': U.UUID(), image: "default.png", "name": "some feature", value: ""});
            this.render_features();
            return this;
        };

        F.prototype.render_features = function () {
            this.getField('features').html(Mustache.render(EFO.TemplateManager().get('feature', MC), this));
            return this;
        };

        F.prototype.onCommandRemove_feature = function (t) {
            var ftd = U.NEString(t.data('id'), null);
            if (ftd) {
                var feature = this.get_feature_object(ftd);
                if (feature) {
                    var fi = this.features.indexOf(feature);
                    if (fi >= 0) {
                        this.features = this.features.slice(0, fi).concat(this.features.slice(fi + 1));
                    }
                }
            }
            return this.render_features();
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="fields">

        F.prototype._set_field_id = function (x, fi) {
            this.getField('id').val(U.IntMoreOr(x.id, 0, null));
            this.image_list.set_params(MEDIA_CONTEXT, U.IntMoreOr(x.id, 0, null));
            return this;
        };

        F.prototype._set_field_features = function (x, fi) {
            this.features = U.safeArray(x.features);
            for (var i = 0; i < this.features.length; i++) {
                if (U.isObject(this.features[i])) {
                    this.features[i].feature_id = U.UUID();
                }
            }
            this.render_features();
            return this;
        };
        F.prototype._get_field_features = function () {
            var r = [];
            var s = U.safeArray(this.features);
            var filter = this.getFilters().getSectionExport('feature');
            for (var i = 0; i < s.length; i++) {
                try {
                    var data = EFO.Filter.Filter().applyFiltersToHash(s[i], filter);
                    EFO.Filter.Filter().throwValuesErrorFirst(data, true);
                } catch (ee) {
                    continue;
                }
                r.push(data);
            }
            return r;
        };
        //<editor-fold defaultstate="collapsed" desc="copypasted">

        F.prototype._set_field_default_image = function () {
            return this;
        };
        F.prototype._get_field_default_image = function () {
            return this.image_list.get_default_image();
        };
        F.prototype._set_field_images = function () {
            return this;
        };

        F.prototype._get_field_images = function () {
            return null;
        };

        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };
        //</editor-fold>



        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="command-point-selector">
        F.prototype.onCommandCoordinates = function () {
            this.showLoader();
            Y.load('selectors.map_selector').done(this, this.on_map_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_map_ready = function (x) {
            var address = U.NEString(this.getField('address').val(), null);
            var ll = [
                U.FloatOr(this.getField('lat').val(), null),
                U.FloatOr(this.getField('lon').val(), null)
            ];
            x.show().load(ll, address).setCallback(this, this.on_map_result);
            return this;
        };
        F.prototype.on_map_result = function (r) {
            r = U.safeArray(r);
            while (r.lengtgh < 2) {
                r.push(null);
            }
            var lat = U.FloatOr(r[0], null);
            var lng = U.FloatOr(r[1], null);
            this.getField('lat').val(lat === null ? '' : lat.toFixed(6));
            this.getField('lon').val(lng === null ? '' : lng.toFixed(6));
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="feature-icon-selector">
        F.prototype.onCommandSelect_feature_icon = function (t) {
            this._feature_to_select = U.NEString(t.data('id'), null);
            this.showLoader();
            Y.load('selectors.feature_icon_selector').done(this, this.on_feature_icon_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_feature_icon_selector_ready = function (x) {
            x.show().load().setCallback(this, this.on_icon_selected);
            return this;
        };

        F.prototype.get_feature_object = function (id) {
            var feature = null;
            for (var i = 0; i < this.features.length; i++) {
                if (this.features[i].feature_id === id) {
                    feature = this.features[i];
                    break;
                }
            }
            return feature;
        };

        F.prototype.on_icon_selected = function (xu) {
            var feature = this.get_feature_object(this._feature_to_select);
            if (feature) {
                feature.image = xu;
            }
            return this.render_features();
        };

        F.prototype.onMonitorFeature_name_change = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                var feature = this.get_feature_object(id);
                if (feature) {
                    feature.name = U.NEString(t.val(), feature.name);
                    t.val(feature.name);
                }
            }
            return this;
        };
        F.prototype.onMonitorFeature_value_change = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                var feature = this.get_feature_object(id);
                if (feature) {
                    feature.value = U.NEString(t.val(), null);
                    t.val(feature.value);
                }
            }
            return this;
        };


        //</editor-fold>





        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            this.showError("Ошибка при загрузке компонента!");
        };
        Y.reportSuccess(FQCN, F());
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