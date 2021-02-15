(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.image_list_product').promise,
        Y.load('media.color_editor').promise,
        Y.load('inline.property_editor').promise,
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js')
    ];
    //</editor-fold>
    function initPlugin() {
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            window.Eve.ADVTable = window.Eve.ADVTable || {};
            window.Eve.ADVTable.Ready = window.Eve.ADVTable.Ready || [];
            window.Eve.ADVTable.Ready.push(deps_ready);
        });
    }

    function deps_ready() {
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
            this.init_catalogs_table();
            this.init_editors();
            this.init_color_list();
            this.init_image_list();
            this.init_sizes_table();
            this.init_cross_table();
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            this.text_editor_consists.init_editor();
            this.text_editor_info.init_editor();
            return this;
        };

        F.prototype.onAfterHide = function () {
            this.text_editor_consists.destroy_editor();
            this.text_editor_info.destroy_editor();
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
            return "Редактирование товара";
        };

        F.prototype.enumSubTemplates = function () {
            return [].concat(
                    PARP.enumSubTemplates.call(this),
                    [
                        MC + ".TAB_common"
                                , MC + ".TAB_catalogs"
                                , MC + ".TAB_info"
                                , MC + ".TAB_consists"
                                , MC + ".TAB_images"
                                , MC + ".TAB_meta"
                                , MC + ".TAB_colors"
                                , MC + ".TAB_sizes"
                                , MC + ".TAB_price"
                                , MC + ".TAB_properties"
                                , MC + ".TAB_admin"
                                , MC + ".TAB_cross"

                    ]);
        };
        //</editor-fold>   
        //<editor-fold defaultstate="collapsed" desc="parts editors">
        //<editor-fold defaultstate="collapsed" desc="quill">
        F.prototype.init_editors = function () {
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.text_editor_info = cf();
            this.text_editor_info.setContainer(this.getField('description'));
            this.text_editor_consists = cf();
            this.text_editor_consists.setContainer(this.getField('consists'));
            this.init_property_editor();
            return this;
        };
        F.prototype.init_property_editor = function () {
            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };



        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="imagelist">
        F.prototype.init_image_list = function () {
            var UF = Y.get_loaded_component('media.image_list_product');
            this.image_list = UF();
            this.image_list.set_color_source(this.color_list);
            this.image_list.set_params("product", null);
            this.image_list.setContainer(this.getRole("image-list"));
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="color_list">
        F.prototype.init_color_list = function () {
            var UF = Y.get_loaded_component('media.color_editor');
            this.color_list = UF();
            this.color_list.set_colors(null, null);
            this.color_list.setContainer(this.getRole("color-list"));
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tables">                       
        //<editor-fold defaultstate="collapsed" desc="catalog">
        F.prototype.get_catalogs_table_def = function () {
            return {
                id: MC + "Catalogs",
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "id", key: "id", property: "id", "text": "ID", filter: false, sort: false},
                    {id: "guid", key: "guid", property: "guid", "text": "id 1C", filter: false, sort: false},
                    {id: "path", key: "path", property: "path", "text": "Наименование", filter: false, sort: false},
                    //{id: "sort", key: "sort", property: "sort", "text": "Сортировка", filter: false, sort: false,visible:false},
                    {id: "control", key: "control", property: "control", "text": "Контроль", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_catalogs_table = function () {
            var def = this.get_catalogs_table_def();
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}Catalogs","TPLSCatalogs")?>*/
            this.catalogs_table = ADVT.Table(def);

            this.catalogs_table.addRenderer('getMC', function () {
                return MC;
            });
            this.catalogs_table.addRenderer('getMD', function () {
                return MD;
            });
            this.catalogs_datasource = ADVT.DataSource.ArrayDataSource(this.catalogs_table.TableOptions);
            this.catalogs_table.setDataSource(this.catalogs_datasource);
            this.catalogs_table.appendTo(this.getField('catalogs').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="sizes">
        F.prototype.get_sizes_table_def = function () {
            return {
                id: MC + "Sizes",
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "id", key: "id", property: "id", "text": "ID", filter: false, sort: false},
                    {id: "guid", key: "guid", property: "guid", "text": "id 1C", filter: false, sort: false},
                    {id: "value", key: "value", property: "value", "text": "Размер larro", filter: false, sort: false},
                    {id: "enabled", key: "enabled", property: "enabled", "text": "Вкл", filter: false, sort: false},
                    {id: "control", key: "control", property: "control", "text": "Контроль", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_sizes_table = function () {
            var def = this.get_sizes_table_def();
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}Sizes","TPLSSizes")?>*/
            this.sizes_table = ADVT.Table(def);

            this.sizes_table.addRenderer('getMC', function () {
                return MC;
            });
            this.sizes_table.addRenderer('getMD', function () {
                return MD;
            });

            this.sizes_table.addRenderer('row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                this._row_index++;
                return this._row_index;
            }).bindToObject(this));
            this.sizes_table.addRenderer('repeat_row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                return this._row_index;
            }).bindToObject(this));

            this.sizes_datasource = ADVT.DataSource.ArrayDataSource(this.sizes_table.TableOptions);
            this.sizes_table.setDataSource(this.sizes_datasource);
            this.sizes_table.appendTo(this.getField('sizes').get(0));
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="crosses">
        F.prototype.get_cross_table_def = function () {
            return {
                id: MC + "Cross",
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "id", key: "id", property: "id", "text": "ID", filter: false, sort: false},
                    {id: "guid", key: "guid", property: "guid", "text": "id 1C", filter: false, sort: false},
                    {id: "article", key: "article", property: "article", "text": "Артикул", filter: false, sort: false},
                    {id: "name", key: "name", property: "name", "text": "Наименование", filter: false, sort: false},
                    {id: "control", key: "control", property: "control", "text": "Контроль", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_cross_table = function () {
            var def = this.get_cross_table_def();
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}Cross","TPLSCross")?>*/
            this.cross_table = ADVT.Table(def);

            this.cross_table.addRenderer('getMC', function () {
                return MC;
            });
            this.cross_table.addRenderer('getMD', function () {
                return MD;
            });
            this.cross_datasource = ADVT.DataSource.ArrayDataSource(this.cross_table.TableOptions);
            this.cross_table.setDataSource(this.cross_datasource);
            this.cross_table.appendTo(this.getField('cross').get(0));
        };


        //</editor-fold>
        //</editor-fold>
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер">                
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/Catalog/API', {action: "get_product", product_id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_load_success(d);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_network_fail = function (x) {
            x = U.NEString(x, 'network error');
            U.TError(x);
            return this;
        };

        F.prototype.on_network_fail_fatal = function () {
            this.on_network_fail.apply(this, APS.call(arguments));
            return this.hideclear();
        };

        F.prototype.on_load_success = function (x) {
            this.setFields(x.product);
            this.image_list.reset_temp_links();
            this.image_list.set_params('product', this.getField('id').val());
            return this;
        };
        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.image_list ? this.image_list.clear() : false;
            this.color_list ? this.color_list.clear() : false;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">

        F.prototype.onMonitorPrice = function (f) {
            var t = U.FloatMoreEqOr(f.val(), 0, null);
            f.val(t === null ? '' : EFO.Checks.formatPriceNSD(t, 2));
            return this;
        };

        F.prototype.onMonitorCatalog_sort = function (t) {
            var v = U.IntOr(t.val(), 0);
            var cid = U.IntMoreOr(t.data('catalog'), 0, null);
            if (cid) {
                var search = this.catalogs_datasource.source;
                for (var i = 0; i < search.length; i++) {
                    if (U.IntMoreOr(search[i].id, 0, null) === cid) {
                        search[i].sort = v;
                        t.val(search[i].sort);
                        break;
                    }
                }
            }
            return this;
        };
        F.prototype.onMonitorSort = function (t) {
            var v = U.IntOr(t.val(), 0);
            t.val(v);
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="field get/set overrides">
        F.prototype._get_field_cross = function () {
            var r = [];
            for (var i = 0; i < this.cross_datasource.source.length; i++) {
                var id = U.IntMoreOr(this.cross_datasource.source[i].id, 0, null);
                if (id) {
                    r.push(id);
                }
            }
            return r;
        };

        F.prototype._set_field_cross = function (a) {
            a = U.safeObject(a);
            this.cross_datasource.setSource(U.safeArray(a.cross));
            return this;
        };

        F.prototype._set_field_html_mode_c = function () {
            return this;
        };
        F.prototype._set_field_html_mode_d = function () {
            return this;
        };
        F.prototype._get_field_html_mode_c = function () {
            return this.text_editor_consists.get_check_state();
        };
        F.prototype._get_field_html_mode_d = function () {
            return this.text_editor_info.get_check_state();
        };

        F.prototype._set_field_default_image = function () {
            return this;
        };
        F.prototype._get_field_default_image = function () {
            return this.image_list.get_default_image();
        };

        F.prototype._get_field_catalogs = function () {
            var r = [];
            var s = U.safeArray(this.catalogs_datasource.source);
            for (var i = 0; i < s.length; i++) {
                try {
                    var id = U.IntMoreOr(s[i].id, 0, null);
                    var sort = U.IntOr(s[i].sort, 0);
                    if (id) {
                        r.push({group: id, sort: sort});
                    }
                } catch (ee) {

                }
            }
            return r;
        };
        F.prototype._set_field_catalogs = function (a) {
            a = U.safeObject(a);
            this.catalogs_datasource.setSource(U.safeArray(a.catalogs));
            return this;
        };

        F.prototype._get_field_sizes = function () {
            return [].concat(this.sizes_datasource.source);
        };
        F.prototype._set_field_sizes = function (a, b, c) {
            this.sizes_datasource.setSource(U.safeArray(a.sizes));
            return this;
        };

        F.prototype._set_field_images = function () {
            return this;
        };

        F.prototype._get_field_images = function () {
            return this.image_list.get_images_params();
        };

        F.prototype._get_field_description = function () {
            return this.text_editor_info.getText();
        };
        F.prototype._set_field_description = function (c) {
            this.text_editor_info.setText(U.NEString(c.description, ''), U.anyBool(c.html_mode_d, true));
            //this.text_editor_info.root.innerHTML = U.NEString(c.description, '');
            return this;
        };
        F.prototype._get_field_consists = function () {
            return this.text_editor_consists.getText();
        };
        F.prototype._set_field_consists = function (c) {
            this.text_editor_consists.setText(U.NEString(c.consists, ''), U.anyBool(c.html_mode_c, true));
            return this;
        };

        F.prototype._set_field_colors = function (a) {
            var colors = U.safeArray(a.colors);
            this.color_list.set_colors(100, colors);
            return this;
        };

        F.prototype._get_field_colors = function () {
            return this.color_list ? this.color_list.export() : [];
        };

        F.prototype._get_field_retail = function (f) {
            return U.FloatMoreEqOr(f.val(), 0, null);
        };

        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };

        F.prototype._get_field_gross = F.prototype._get_field_retail;
        F.prototype._get_field_retail_old = F.prototype._get_field_retail;
        F.prototype._get_field_gross_old = F.prototype._get_field_retail;
        F.prototype._get_field_discount_retail = F.prototype._get_field_retail;
        F.prototype._get_field_discount_gross = F.prototype._get_field_retail;

        F.prototype._set_field_retail = function (x, f) {
            f.val(U.NEString(x.retail, null) ? EFO.Checks.formatPriceNSD(x.retail, 2) : '');
            return this;
        };
        F.prototype._set_field_gross = function (x, f) {
            f.val(U.NEString(x.gross, null) ? EFO.Checks.formatPriceNSD(x.gross, 2) : '');
            return this;
        };
        F.prototype._set_field_retail_old = function (x, f) {
            f.val(U.NEString(x.retail_old, null) ? EFO.Checks.formatPriceNSD(x.retail_old, 2) : '');
            return this;
        };
        F.prototype._set_field_gross_old = function (x, f) {
            f.val(U.NEString(x.gross_old, null) ? EFO.Checks.formatPriceNSD(x.gross_old, 2) : '');
            return this;
        };
        F.prototype._set_field_discount_retail = function (x, f) {
            f.val(U.NEString(x.discount_retail, null) ? EFO.Checks.formatPriceNSD(x.discount_retail, 2) : '');
            return this;
        };
        F.prototype._set_field_discount_gross = function (x, f) {
            f.val(U.NEString(x.discount_gross, null) ? EFO.Checks.formatPriceNSD(x.discount_gross, 2) : '');
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
        //<editor-fold defaultstate="collapsed" desc="add catalog">
        F.prototype.onCommandAdd_catalog = function () {
            this.showLoader();
            Y.load('selectors.catalog_group')
                    .done(this, this.on_catalog_selector)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_catalog_selector = function (x) {
            x.show().load().set_allow_multi(true).setCallback(this, this.on_catalog_selected);
            return this;
        };

        F.prototype.on_catalog_selected = function (x, y, z) {
            x = U.safeArray(x);
            if (x && x.length) {
                var nodes_to_add = [];
                var nodes_added = {};
                for (var i = 0; i < x.length; i++) {
                    var node = x[i];
                    if (node) {
                        var data = {id: U.IntMoreOr(node.id, 0, null), path: node.get_name_path(), sort: 0, guid: node.guid, name: node.name};
                        if (data.id) {
                            var nokey = ["A", data.id].join('');
                            if (U.NEString(nodes_added[nokey], null) !== nokey) {
                                var f = false;
                                for (var i = 0; i < this.catalogs_datasource.source.length; i++) {
                                    if (U.IntMoreOr(this.catalogs_datasource.source[i].id, 0, null) === data.id) {
                                        f = true;
                                        break;
                                    }
                                }
                                if (!f) {
                                    nodes_to_add.push(data);
                                    nodes_added[nokey] = nokey;
                                }
                            }
                        }
                    }
                }
                if (nodes_to_add.length) {
                    this.catalogs_datasource.setSource(this.catalogs_datasource.source.concat(nodes_to_add));
                }
            }
            this.catalogs_table.LayoutManager.getLayoutArea('scrollFixArea').scrollTop = 500000000;
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="remove_catalog">
        F.prototype.onCommandCatalog_remove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                var src = this.catalogs_datasource.source;
                var nsrc = [];
                for (var i = 0; i < src.length; i++) {
                    if (U.IntMoreOr(src[i].id, 0, null) === id) {
                        continue;
                    }
                    nsrc.push(src[i]);
                }
                this.catalogs_datasource.setSource(nsrc);
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="addSize">
        F.prototype.onCommandAdd_size = function () {
            this.showLoader();
            Y.load('selectors.size_selector_wnd')
                    .done(this, this.on_size_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_size_selector_ready = function (c) {
            c.show().load().set_allow_multi(true).setCallback(this, this.on_sizes_selected);
            return this;
        };
        F.prototype.on_sizes_selected = function (x) {
            var current_dataset = [].concat(this.sizes_datasource.source);
            var selected_sizes = {};
            var new_items = [];
            for (var i = 0; i < current_dataset.length; i++) {
                var key = ["A", current_dataset[i].id].join('');
                selected_sizes[key] = key;
            }
            for (var i = 0; i < x.length; i++) {
                var key = ["A", x[i].id].join('');
                if (selected_sizes[key] !== key) {
                    new_items.push({id: x[i].id, enabled: true, guid: x[i].guid, value: x[i].size});
                }
            }
            var new_source = current_dataset.concat(new_items);
            new_source.sort(function (a, b) {
                var r = U.IntOr(a.value, 0) - U.IntOr(b.value, 0);
                return r === 0 ? (a.value < b.value ? 1 : (a.value > b.value ? -1 : 0)) : r;
            });
            this.sizes_datasource.setSource(new_source);
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="remove size">
        F.prototype.onCommandSize_remove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                var source = [].concat(this.sizes_datasource.source);
                var result = [];
                for (var i = 0; i < source.length; i++) {
                    if (U.IntMoreOr(source[i].id, 0, null) === id) {
                        continue;
                    }
                    result.push(source[i]);
                }
                this.sizes_datasource.setSource(result);
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="crosses">
        F.prototype.onCommandAdd_cross = function () {
            this.showLoader();
            Y.load('selectors.product_selector')
                    .done(this, this.on_product_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_product_selector_ready = function (x) {
            x.show().load().setCallback(this, this.on_product_selector_done);
            return this;
        };

        F.prototype.on_product_selector_done = function (x) {
            /*
             * 
             * actioned: "0"
             alias: "uslugi_po_poshivu_palto_34203"
             article: "Пальто"
             enabled: "0"
             gross: null
             guid: "34203"
             id: "50250"
             name: "Услуги по пошиву пальто"
             retail: null
             sort: "0"
             * 
             */
            var ds = [].concat(this.cross_datasource.source);
            var index = {};
            for (var i = 0; i < ds.length; i++) {
                var key = ["P", U.IntMoreOr(ds[i].id, 0, null)].join('');
                index[key] = key;
            }
            x = U.safeArray(x);
            for (var i = 0; i < x.length; i++) {
                var key = ["P", U.IntMoreOr(x[i].id, 0, null)].join('');
                if (index[key] !== key) {
                    ds.push({
                        id: U.IntMoreOr(x[i].id, 0, null),
                        alias: U.NEString(x[i].alias, null),
                        article: U.NEString(x[i].article, null),
                        guid: U.NEString(x[i].guid, null),
                        name: U.NEString(x[i].name, null)
                    });
                    index[key] = key;
                }
            }
            this.cross_datasource.setSource(ds);
            return this;
        };

        F.prototype.onCommandCross_remove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                var ds = [].concat(this.cross_datasource.source);
                var nds = [];
                for (var i = 0; i < ds.length; i++) {
                    if (U.IntMoreOr(ds[i].id, 0, null) === id) {
                        continue;
                    }
                    nds.push(ds[i]);
                }
            }
            this.cross_datasource.setSource(nds);
            return this;
        };
        //</editor-fold>
        //</editor-fold>



        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };

        //<editor-fold defaultstate="collapsed" desc="checkers">
        F.prototype.check_data_common = function (raw, out) {
            try {
                var data = EFO.Filter.Filter().applyFiltersToHash(raw, this.getFilters().getSectionExport('common'));
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
                for (var k in data) {
                    if (data.hasOwnProperty(k) && !U.isCallable(data[k])) {
                        out[k] = data[k];
                    }
                }
            } catch (e) {
                U.Error(MC + ":Common:" + e.message);
            }
            return this;
        };

        F.prototype.check_data_meta = function (raw, out) {
            try {
                var data = EFO.Filter.Filter().applyFiltersToHash(raw, this.getFilters().getSectionExport('meta'));
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
                out['meta'] = data;
            } catch (e) {
                U.Error(MC + ":Meta:" + e.message);
            }
            return this;
        };

        F.prototype.check_data_catalogs = function (raw, out) {
            try {
                var SE = this.getFilters().getSectionExport('catalog');
                var list = U.safeArray(raw.catalogs);
                var o = [];
                for (var i = 0; i < list.length; i++) {
                    try {
                        var fc = EFO.Filter.Filter().applyFiltersToHash(U.safeObject(list[i]), SE);
                        EFO.Filter.Filter().throwValuesErrorFirst(fc, true);
                        o.push(fc);
                    } catch (xee) {

                    }
                }
                out['catalogs'] = o;
            } catch (e) {
                U.Error(MC + ":Catalogs:" + e.message);
            }
            return this;
        };

        F.prototype.check_data_colors = function (raw, out) {
            try {
                var SE = this.getFilters().getSectionExport('color');
                var list = U.safeArray(raw.colors);
                var o = [];
                for (var i = 0; i < list.length; i++) {
                    try {
                        var fc = EFO.Filter.Filter().applyFiltersToHash(U.safeObject(list[i]), SE);
                        EFO.Filter.Filter().throwValuesErrorFirst(fc, true);
                        o.push(fc);
                    } catch (xee) {

                    }
                }
                out['colors'] = o;
            } catch (e) {
                U.Error(MC + ":colors:" + e.message);
            }
            return this;
        };

        F.prototype.check_data_sizes = function (raw, out) {
            try {
                var SE = this.getFilters().getSectionExport('size');
                var list = U.safeArray(raw.sizes);
                var o = [];
                for (var i = 0; i < list.length; i++) {
                    try {
                        var fc = EFO.Filter.Filter().applyFiltersToHash(U.safeObject(list[i]), SE);
                        EFO.Filter.Filter().throwValuesErrorFirst(fc, true);
                        o.push(fc);
                    } catch (xee) {

                    }
                }
                out['sizes'] = o;
            } catch (e) {
                U.Error(MC + ":sizes:" + e.message);
            }
            return this;
        };


        F.prototype.check_data_images = function (raw, out) {
            out.images = U.safeObject(raw.images);
            return this;
        };

        //</editor-fold>

        F.prototype.check_data = function (raw_data) {
            var data = {};
            try {
                for (var k in F.prototype) {
                    if (U.isCallable(F.prototype[k]) && F.prototype.hasOwnProperty(k)) {
                        if (/^check_data_.*/i.test(k)) {
                            F.prototype[k].apply(this, [raw_data, data]);
                        }
                    }
                }
            } catch (ee) {
                U.TError(ee);
                return false;
            }

            return data;
        };

        F.prototype.save = function (keep_open) {
            this.keep_open = U.anyBool(keep_open, true);
            var raw_data = this.getFields();
            var data = this.check_data(raw_data);
            if (data) {
                this.showLoader();
                jQuery.post('/admin/Catalog/API', {action: 'post_product', data: JSON.stringify(data)}, null, 'json')
                        .done(this.on_post_responce.bindToObject(this))
                        .fail(this.on_network_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));

            }
            return this;
        };

        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this.runCallback();
                    if (this.keep_open) {
                        this.on_load_success(d);
                    } else {
                        this.hideclear();
                    }
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };


        F.prototype.onTabSelectedInfo = function () {
            this.text_editor_info.refresh();
            return this;
        };

        F.prototype.onTabSelectedConsists = function () {
            this.text_editor_consists.refresh();
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