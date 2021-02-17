(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise,
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js')

    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = E.ADVTable;
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
            this.init_catalogs_table();
            return this;
        };
        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_properties"
                        , MC + ".TAB_admin"

            ]);
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            return this;
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
            return "Редактирование тайлблока";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/CatalogTile/API', {action: "get", id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.showLoader();
                jQuery.getJSON('/admin/CatalogTile/API', {action: "get_metadata", id: id})
                        .done(this.on_meta_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_meta_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_meta_success(d);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_meta_success = function (d) {
            d = U.safeObject(U.safeObject(d).catalog_tile_metadata);
            var templates = U.safeArray(d.templates);
            this._option_item = templates;
            this.getField('template').html(Mustache.render(EFO.TemplateManager().get('option', MC), this));
            var loaders = U.safeArray(d.loaders);
            this._option_item = loaders;
            this.getField('loader').html(Mustache.render(EFO.TemplateManager().get('option', MC), this));
            this._option_item = null;
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_meta_success(d);
                    return this.on_data_success(d.catalog_tile);
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
            this.image_list.set_params('catalog_tile', null);
            this.image_list.set_meta_editor('media.title_editor');
            this.image_list.LEM.on('IMAGELIST_CHANGED', this, this.on_image_list_changed);
            return this;
        };

        F.prototype.init_property_editor = function () {
            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };

        F.prototype.get_catalogs_table_def = function () {
            return {
                id: MC,
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "id", key: "id", property: "id", "text": "ID", filter: false, sort: false},
                    {id: "path", key: "path", property: "path", "text": "Наименование", filter: false, sort: false},
                    {id: "sort", key: "sort", property: "sort", "text": "Сортировка", filter: false, sort: false},
                    {id: "override", key: "override", property: "override", "text": "Отображение", filter: false, sort: false},
                    {id: "image_id", key: "image_id", property: "image_id", "text": "Изображение", filter: false, sort: false},
                    {id: "control", key: "control", property: "control", "text": "Контроль", filter: false, sort: false}
                ]
            };
        };

        F.prototype.init_catalogs_table = function () {
            var def = this.get_catalogs_table_def();
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}","TPLS")?>*/
            this.catalogs_table = ADVT.Table(def);

            this.catalogs_table.addRenderer('getMC', function () {
                return MC;
            });
            this.catalogs_table.addRenderer('getMD', function () {
                return MD;
            });
            this.catalogs_table.addRenderer("name_override_ph", function () {
                var pa = this.path.split("\\");
                return pa[pa.length - 1];
            });

            this.catalogs_table.addRenderer("get_catalog_image_url", (function (x) {
                
                var context = "";
                var id = null;
                var image = null;
                if (U.NEString(x.image_id, null)) {
                    context = "catalog_tile";
                    id = U.IntMoreOr(this.getField('id').val(), 0, null);
                    image = x.image_id;
                } else {
                    context = "product_group";
                    id = x.id;
                    image = x.default_image;
                }
                return "/media/" + context + "/" + id + "/" + image + ".SW_250H_250CF_1.jpg";
            }).bindToObjectWParam(this));
            this.catalogs_datasource = ADVT.DataSource.ArrayDataSource(this.catalogs_table.TableOptions);
            this.catalogs_table.setDataSource(this.catalogs_datasource);
            this.catalogs_table.appendTo(this.getField('catalogs').get(0));
        };

        F.prototype.on_image_list_changed = function (ilo) {            
            if (U.isArray(ilo.image_list)) {
                var t = {};
                for (var i = 0; i < ilo.image_list.length; i++) {
                    t[ilo.image_list[i].image] = ilo.image_list[i].image;
                }
                var cs = [].concat(this.catalogs_datasource.source);
                for (var i = 0; i < cs.length; i++) {
                    if (cs[i].image_id) {
                        if (t[cs[i].image_id] !== cs[i].image_id) {
                            cs[i].image_id = null;
                        }
                    }
                }
                this.catalogs_datasource.setSource(cs);
            }
            return this;
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
        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };

        F.prototype._set_field_catalogs = function (x) {
            var m = U.safeArray(x.catalogs);
            m.sort(this.catalog_sort_func);
            this.catalogs_datasource.setSource([].concat(m));
            return this;
        };

        F.prototype._get_field_catalogs = function () {
            return [].concat(this.catalogs_datasource.source);
        };

        //<editor-fold defaultstate="collapsed" desc="monitors">

        F.prototype.onMonitorCatalog_sort = function (t) {
            var id = U.IntMoreOr(t.data('catalog'), 0, null);
            if (id) {
                var source_item = this.get_catalog_source_item(id);
                if (source_item) {
                    source_item.sort = U.IntOr(t.val(), 0);
                    t.val(source_item.sort);
                }
            }
            return this;
        };

        F.prototype.onMonitorCatalog_override = function (t) {
            var v = U.NEString(t.val(), null);
            var id = U.IntMoreOr(t.data('catalog'), 0, null);
            if (id) {
                var source_item = this.get_catalog_source_item(id);
                if (source_item) {
                    source_item.override = v;
                    t.val(U.NEString(source_item.override, ''));
                }
            }
            return this;
        };

        F.prototype.get_catalog_source_item = function (id) {
            for (var i = 0; i < this.catalogs_datasource.source.length; i++) {
                if (U.IntMoreOr(this.catalogs_datasource.source[i].id, 0, null) === id) {
                    return this.catalogs_datasource.source[i];
                }
            }
            return null;
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
        F.prototype.onCommandAdd_catalog = function () {
            this.showLoader();
            Y.load('selectors.catalog_group')
                    .done(this, this.on_catalog_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_catalog_selector_ready = function (x) {
            x.show().load().set_allow_multi(true).setCallback(this, this.on_catalogs_selected);
            return this;
        };

        F.prototype.on_catalogs_selected = function (x) {
            var result = [];
            for (var i = 0; i < x.length; i++) {
                var o = {
                    id: U.IntMoreOr(x[i].id, 0, null),
                    path: U.NEString(x[i].get_name_path("\\"), null),
                    sort: 0,
                    override: null,
                    image_id: null,
                    default_image: U.NEString(x[i].default_image, null)
                };
                if (o.id && o.path) {
                    result.push(o);
                }
            }

            var dataset = [].concat(this.catalogs_datasource.source);
            var dataindex = {};
            for (var i = 0; i < dataset.length; i++) {
                var key = ["A", dataset[i].id].join('');
                dataindex[key] = key;
            }

            for (var i = 0; i < result.length; i++) {
                var key = ["A", result[i].id].join("");
                if (dataindex[key] !== key) {
                    dataset.push(result[i]);
                }
            }
            dataset.sort(this.catalog_sort_func);
            this.catalogs_datasource.setSource(dataset);
            return this;
        };

        F.prototype.catalog_sort_func = function (a, b) {
            var r = U.IntOr(a.sort, 0) - U.IntOr(b.sort, 0);
            return r === 0 ? (a.path < b.path ? 1 : (a.path > b.path ? -1 : 0)) : r;
        };

        F.prototype.onCommandCatalog_remove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                var item = this.get_catalog_source_item(id);
                if (item) {
                    var source = [].concat(this.catalogs_datasource.source);
                    var i = source.indexOf(item);
                    if (i >= 0) {
                        source = source.slice(0, i).concat(source.slice(i + 1));
                    }
                    this.catalogs_datasource.setSource(source);
                }
            }
            return this;
        };

        F.prototype.onCommandSelectImage = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this._catalog_to_replace_image = id;
                this.showLoader();
                Y.load('media.in_place_linked_selector')
                        .done(this, this.inner_selector_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            }
            return this;
        };

        F.prototype.inner_selector_ready = function (x) {
            x.show().setCallback(this, this.on_image_selected)
                    .set_multi(false)
                    .enable_clear(true)
                    .set_source(this.image_list);
            return this;
        };

        F.prototype.on_image_selected = function (x) {
            if (U.isArray(x) && x.length) {
                var item = this.get_catalog_source_item(this._catalog_to_replace_image);
                if(item){
                    item.image_id = x[0]===null?null:x[0].image;
                    this.catalogs_datasource.setSource([].concat(this.catalogs_datasource.source));
                }
            }
            return this;
        };
        //</editor-fold>




        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };

        F.prototype.check_data_common = function (raw, out) {
            var data = EFO.Filter.Filter().applyFiltersToHash(raw, this.getFilters().getSectionExport('node'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            for (var k in data) {
                if (data.hasOwnProperty(k) && !U.isCallable(data[k])) {
                    out[k] = data[k];
                }
            }
            return this;
        };

        F.prototype.check_data_catalogs = function (raw, out) {
            var filter_pool = this.getFilters().getSectionExport('catalog');
            var cat = U.safeArray(raw.catalogs);
            var r = [];
            for (var i = 0; i < cat.length; i++) {
                var cdat = EFO.Filter.Filter().applyFiltersToHash(U.safeObject(cat[i]), filter_pool);
                try {
                    EFO.Filter.Filter().throwValuesErrorFirst(cdat, true);
                    r.push(cdat);
                } catch (e) {
                    continue;
                }
            }
            out.catalogs = r;
            return this;
        };

        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            var data_to_send = {};
            try {
                for (var k in this) {
                    if (U.isCallable(this[k])) {
                        if (/^check_data_.{1,}$/i.test(k)) {
                            this[k](raw_data, data_to_send);
                        }
                    }
                }
            } catch (e) {
                U.TError(MC + ":" + e.message);
                return this;
            }

            var post_data = {
                action: "post",
                data: JSON.stringify(data_to_send)
            };
            this.showLoader();
            jQuery.post('/admin/CatalogTile/API', post_data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.catalog_tile));
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

        //
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