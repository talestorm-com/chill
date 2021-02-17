(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = window.Eve.ADVTable;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
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
            this.init_image_list();
            this.init_property_editor();
            this.init_table();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_props"

            ]);
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            return this;
        };
        F.prototype.onAfterHide = function () {
            return PARP.onAfterHide.apply(this, APS.call(arguments));
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
            return "Редактирование Баннера";
        };
        //</editor-fold>       
        //<editor-fold defaultstate="collapsed" desc="table">
        F.prototype.items_def = function () {
            return {
                id: MC,
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "language_id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "language_id", key: "language_id", property: "language_id", "text": "Язык", filter: false, sort: false},
                    {id: "bannertext", key: "bannertext", property: "bannertext", "text": "Текст", filter: false, sort: false},
                    {id: "url", key: "url", property: "url", "text": "url", filter: false, sort: false}
                ]
            };
        };

        F.prototype.init_table = function () {
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}","TPLS")?>*/
            this.table = ADVT.Table(this.items_def());
            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });
            this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('table').get(0));
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
                jQuery.getJSON('/admin/MediaContent/API', {action: "get", id: id, content_type: 'ctBANNER'})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.showLoader();
                jQuery.getJSON('/admin/MediaContent/API', {action: "language_list"})
                        .done(this.on_load_responce_meta.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };
        F.prototype.on_load_responce_meta = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.language_list = U.safeArray(U.safeObject(d.metadata).language_list);
                    this.on_data_success({});
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.language_list = U.safeArray(U.safeObject(d.metadata).language_list);
                    return this.on_data_success(d.data);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            this.image_list.set_owner_id(U.NEString(U.IntMoreOr(this.getField('id').val(), 0, null), null));
            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">        
        F.prototype.init_image_list = function () {
            var cf = Y.get_loaded_component('media.image_uploader');
            this.image_list = cf();
            this.image_list.setContainer(this.getRole('gallery'));
            this.image_list.set_params('media_content_poster', null);
            return this;
        };

        F.prototype.init_property_editor = function () {
            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };


        F.prototype._get_field_content_type = function () {
            return "ctBANNER";
        };


        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };


        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };

        F.prototype._set_field_default_poster = function (d, fi) {

        };

        F.prototype._get_field_default_poster = function () {
            return this.image_list.get_default_image();
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.image_list.set_owner_id(null);
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tabs">

        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="monitors">
        F.prototype.get_table_item = function (x) {
            x = U.NEString(x, null);
            if (x) {
                var sa = U.safeArray(this.datasource.source);
                for (var i = 0; i < sa.length; i++) {
                    if (sa[i].language_id === x) {
                        return sa[i];
                    }
                }

            }
            return null;
        };
        F.prototype.onMonitorUrl = function (t) {
            var lang_id = U.NEString(t.data('id'), null);
            if (lang_id) {
                var item = this.get_table_item(lang_id);
                if (item) {
                    item.url = U.NEString(t.val(), null);
                    t.val(item.url);
                }
            }
            return this;
        };
        F.prototype.onMonitorBannertext = function (t) {
            var lang_id = U.NEString(t.data('id'), null);
            if (lang_id) {
                var item = this.get_table_item(lang_id);
                if (item) {
                    item.bannertext = U.NEString(t.val(), null);
                    t.val(item.bannertext);
                }
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">

        //<editor-fold defaultstate="collapsed" desc="footer commands">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };
        F.prototype.onCommandApply = function () {
            this.save(true);
            return this;
        };
        F.prototype.onCommandSave = function () {
            this.save(false);
            return this;
        };
        //</editor-fold>
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="fields">
        F.prototype._set_field_strings = function (a, fi) {            
            var values = [];
            var sa = U.safeArray(this.language_list);
            for (var i = 0; i < sa.length; i++) {
                var id = sa[i].id;
                var bannertext = U.NEString(U.safeObject(U.safeObject(a.strings)[id]).bannertext, '');
                var url = U.NEString(U.safeObject(U.safeObject(a.strings)[id]).url, '');                
                values.push({language_id: id, bannertext: bannertext, url: url});
            }
            this.datasource.setSource(values);
        };

        F.prototype._get_field_strings = function () {
            return [].concat(this.datasource.source);
        };
        //</editor-fold>






        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            var post_data = {
                action: "put",
                data: JSON.stringify(data)
            };
            this.showLoader();
            jQuery.post('/admin/MediaContent/API', post_data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.data));
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

        F.prototype.on_network_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this;
        };

        F.prototype.on_network_fail_fatal = function (x) {
            this.on_network_fail.apply(this, APS.call(arguments));
            this.hide().clear();
            return this;
        };
        //</editor-fold>        
        //</editor-fold>       
        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            throw new Error("component load error");
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