(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    (function () {
        /*<?=$this->include_lib('tree')?>*/
        /*<?=$this->include_lib('SortMonitor')?>*/
    })();
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var ADVT = E.ADVTable;
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.shift = false;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.selection = EFO.id_selection_collection().get_selection(MC);
            this.selection.LEM.on('SELECTION_CHANGED', this, this.on_selection_changed);
            this.on_selection_changed(this.selection);
            this.splitter = EFO.Widgets.Splitter()
                    .setController(this)
                    .setElements(this.getRole('tree'), this.getRole('table'))
                    .setContainer(this.getRole('body')).show();
            this.init_tree();
            this.init_table();
            jQuery(document).on('keydown', this.on_keydown.bindToObject(this));
            jQuery(document).on('keyup', this.on_keyup.bindToObject(this));
            this.selection.LEM.on('SELECTION_ADD', this, this.on_selection_add);
            this.selection.LEM.on('SELECTION_REMOVE', this, this.on_selection_remove);
            this.selection.LEM.on('SELECTION_RESET', this, this.on_selection_reset);
            this.init_dnd();
            this.handle.on('focus', 'input[type=text]', this.on_focus.bindToObjectWParam(this));
            this.handle.on('keydown', 'input[type=text]', this.on_sort_keydown.bindToObjectWParam(this));
            this.sort_monitor = window[MC + "SortMonitor"]();
            this.sort_monitor.LEM.on('CHANGED', this, this.on_sort_changed);
            return this;
        };

        F.prototype.on_sort_changed = function (sm) {
            this.getRole("sorted").html(sm.get_length());
            return this;
        };

        F.prototype.on_focus = function (x, e) {
            jQuery(x).select();
            return this;
        };

        F.prototype.on_sort_keydown = function (x, e) {
            if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 9 || e.keyCode === 13) {
                var dir = e.keyCode === 38 ? -1 : 1;
                dir = dir * (e.shiftKey ? -1 : 1);
                var index = U.IntMoreOr(jQuery(x).data('index'), 0, null);
                if (index) {
                    var ni = index + dir;
                    if (ni) {
                        var element = this.handle.find('input[type=text][data-index="' + ni + '"]');
                        if (element && element.length) {
                            element.focus();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        }
                    }
                }
            }
            return this;
        };


        F.prototype.init_dnd = function () {
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_Product', this, this.on_drag_starts);
        };

        F.prototype.on_drag_starts = function (node, custom_data, event) {
            custom_data.product_ids = [];
            var id = U.IntMoreOr(node.data('id'), 0, null);
            id ? custom_data.product_ids.push(id) : 0;
            if (!this.selection.empty()) {
                custom_data.product_ids = custom_data.product_ids.concat(this.selection.get_id_array());
            }
            custom_data.product_ids = U.unique_array_int(custom_data.product_ids);
            return this;
        };

        F.prototype.on_selection_add = function (s, aso) {
            aso = U.safeArray(aso);
            for (var i = 0; i < aso.length; i++) {
                var id = ['#', MC, 'Check', aso[i].id].join('');
                jQuery(id).prop('checked', true);
            }
            return this;
        };
        F.prototype.on_selection_remove = function (s, aso) {
            aso = U.safeArray(aso);
            for (var i = 0; i < aso.length; i++) {
                var id = ['#', MC, 'Check', aso[i].id].join('');
                jQuery(id).prop('checked', false);
            }
            return this;
        };

        F.prototype.on_selection_reset = function (s) {
            this.handle.find('input[type=checkbox]').prop('checked', false);
            return this;
        };

        F.prototype.on_keydown = function (e) {
            if (e.keyCode === 16) {
                this.shift = true;
            }
        };

        F.prototype.on_keyup = function (e) {
            if (e.keyCode === 16) {
                this.shift = false;
            }
        };

        F.prototype.on_selection_changed = function (s) {
            this.getRole('selected').html(s.get_length());
            return this;
        };


        F.prototype.onAfterShow = function () {
            this.reload();
            return PARP.onAfterShow.apply(this, APS.call(arguments));
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
            return [];
        };

        F.prototype.getDefaultTitle = function () {
            return "";
        };

        //</editor-fold>                 
        //<editor-fold defaultstate="collapsed" desc="table">   

        F.prototype.table_def = function () {
            return {
                id: MC,
                filters: true,
                sorter: "Remote",
                paginator: true,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "checker", key: "checker", property: "checker", "text": "checker", filter: false, sort: false},
                    {id: "id", key: "id", property: "id", "text": "ID", filter: "Int", sort: true},
                    {id: "article", key: "article", property: "article", "text": "Артикул", filter: "String", sort: true},
                    {id: "alias", key: "alias", property: "alias", "text": "Алиас", filter: "String", sort: true},
                    {id: "guid", key: "guid", property: "guid", "text": "Индентификатор 1C", filter: "String", sort: true},
                    {id: "name", key: "name", property: "name", "text": "Наименование", filter: "String", sort: true},
                    {id: "price", key: "price", property: "price", "text": "Ценник", columns: [
                            {id: "retail", key: "retail", property: "retail", "text": "Розничный", filter: "Numeric", sort: true},
                            {id: "gross", key: "gross", property: "gross", "text": "Дилерский", filter: "Numeric", sort: true}
                        ]},
                    {id: "sort", key: "sort", property: "sort", "text": "Сортировка", filter: "Int", sort: true},
                    {id: "actioned", key: "actioned", property: "actioned", "text": "Акц/Скид", filter: "Bool", sort: true},
                    {id: "enabled", key: "enabled", property: "enabled", "text": "Актив", filter: "Bool", sort: true},
                    {id: "control", key: "control", property: "control", text: "Контроль", sort: false, filter: false}
                ]
            };
        };

        F.prototype.init_table = function () {
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}","TPLS")?>*/
            this.table = ADVT.Table(this.table_def());
            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });
            this.table.addRenderer('row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                this._row_index++;
                return this._row_index;
            }).bindToObject(this));
            this.table.addRenderer('row_index2', (function () {
                this._row_index2 = U.IntMoreOr(this._row_index2, 0, 0);
                this._row_index2++;
                return this._row_index2;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                return this._row_index;
            }).bindToObject(this));
            this.table.addRenderer('get_sort_value', (function (x) {
                return this.sort_monitor.get_value(x.id, x.sort);
            }).bindToObjectWParam(this));
            this.table.addRenderer('is_row_checked', (function (x) {
                return this.selection.contains(x.id);
            }).bindToObjectWParam(this));

            this.table.addRenderer('price_retail', function () {
                var v = U.FloatOr(this.retail, null);
                return null === v ? '' : EFO.Checks.formatPriceNSD(v, 2);
            });
            this.table.addRenderer('price_gross', function () {
                var v = U.FloatOr(this.gross, null);
                return null === v ? '' : EFO.Checks.formatPriceNSD(v, 2);
            });

            var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/Catalog/API?action=get_list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            DSParams.onRequestParams(this, this.on_request_params);
            this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.table.TableOptions.LEM.on('ON_DATA_RENDER_COMPLETE', this, function () {
                this._row_index = 0;
                this._row_index2 = 0;
                this._last_selected = void(0);
            });
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('table').get(0));
        };

        F.prototype.on_request_params = function (a, b, c) {
            if (this.tree) {
                var sel = U.safeArray(this.tree.getSelection());
                if (sel && sel.length) {
                    a.filters.catalog = sel[0].id;
                    return this;
                }
            }
            b.process = false;
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tree">
        F.prototype.init_tree = function () {
            this.tree = E[MC + "tree"]();
            this.tree.setContainer(this.getRole('tree'));
            this.tree.LEM.on('NODE_SELECTED', this, this.on_node_selected);
            this.tree.reload();
            return this;
        };
        F.prototype.on_node_selected = function () {
            this.table.body.DataDriver.refresh();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        F.prototype.reload = function () {
            this.table.body.DataDriver.refresh();
            return this;

        };
        //</editor-fold>                        
        F.prototype.install = function (x) {
            var node = document.getElementById(x);
            if (node) {
                this.container_node = node;
                this.handle.appendTo(node);
                this.show();
                this.reload();
            }
            return this;
        };

        F.prototype.getContainer = function () {
            if (this.container_node) {
                return this.container_node;
            }
            if (!this.safe_node) {
                this.safe_node = document.createElement('div');
            }
            return this.safe_node;
        };


        F.prototype.onCommandClear_sort = function () {
            this.sort_monitor.reset();
            this.reload();
            return this;
        };

        F.prototype.onCommandApply_sort_all = function () {
            if (!this.sort_monitor.is_empty()) {
                var sort_data = this.sort_monitor.get_data();
                if (U.isArray(sort_data) && sort_data.length) {
                    var data = {action: "apply_sort", data: JSON.stringify(sort_data)};
                    this.showLoader();
                    jQuery.post("/admin/Catalog/API", data, null, 'JSON')
                            .done(this.on_sort_apply_resp.bindToObject(this))
                            .fail(this.on_sort_apply_fail.bindToObject(this))
                            .always(this.hideLoader.bindToObject(this));
                }
            }
            return this;
        };

        F.prototype.on_sort_apply_resp = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_apply_sort_success(d);
                }
                if (d.status === "error") {
                    return this.on_sort_apply_fail(d.error_info.message);
                }
            }
            this.on_sort_apply_fail("invalid server responce");
        };

        F.prototype.on_sort_apply_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this;
        };

        F.prototype.on_apply_sort_success = function () {
            this.sort_monitor.reset();
            this.reload();
            return this;
        };

        F.prototype.onCommandAdd = function () {
            this._id_to_edit = null;
            return this.load_editor();
        };

        F.prototype.onCommandEdit = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this._id_to_edit = id;
                this.load_editor();
            }
            return this;
        };

        F.prototype.load_editor = function () {
            this.showLoader();
            Y.load('data_editor.product')
                    .done(this, this.on_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_editor_ready = function (x) {
            return x.show().load(this._id_to_edit).setCallback(this, this.reload);
            return this;
        };

        F.prototype.onCommandRemove = function (t) {
            var product_id = U.IntMoreOr(t.data('id'), 0, null);
            var products = [];
            product_id ? products.push(product_id) : 0;
            if (!this.selection.empty()) {
                products = products.concat(this.selection.get_id_array());
                products = U.unique_array_int(products);
            }
            if (products.length) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text(["Удалить ", (products.length > 1 ? ["эти товары (", this.selection.get_length(), ")"].join('') : "этот товар"), "?<br><b style=\"color:gray;font-size:.9em\">Это действие нельзя отменить!<b>"].join(''))
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.showLoader();
                                jQuery.post('/admin/Catalog/API', {action: "remove_products", data: JSON.stringify({products: products})}, null, 'json')
                                        .done(this.on_remove_responce.bindToObject(this))
                                        .fail(this.on_remove_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не удалять", "Удалить"])
                        .show();
            }
            return this;
        };

        F.prototype.on_remove_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_remove_success();
                }
                if (d.status === 'error') {
                    return this.on_remove_fail(d.error_info.message);
                }
            }
            return this.on_remove_fail("invalid server responce");
        };

        F.prototype.on_remove_success = function () {
            this.selection.reset();
            this.reload();
            return this;
        };
        F.prototype.on_remove_fail = function (x) {
            x = U.NEString(x, "network error");
            U.TError(x);
            return this;
        };

        F.prototype.onCommandNone = function () {
            return this;
        };


        F.prototype.onMonitorProduct_sort = function (t, e) {
            var v = U.IntOr(t.val(), 0);
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this.sort_monitor.add(id, v);
                t.val(this.sort_monitor.get_value(id, 0));
            }
            return this;
        };

        F.prototype.onMonitorCheck = function (t) {
            var current_index = U.IntMoreOr(t.data('index'), 0, null);
            var dir = U.anyBool(t.prop('checked'), true);
            if (current_index) {
                if (this.shift && void(0) !== this._last_selected) {
                    var ids_to = [];
                    var last_selected = this._last_selected;
                    this.handle.find('input[type=checkbox]').each(function () {
                        var T = jQuery(this);
                        var tindex = U.IntMoreOr(T.data('index'), 0, null);
                        if (tindex && ((tindex <= current_index && tindex >= last_selected) || (tindex <= last_selected && tindex >= current_index))) {
                            var id = U.IntMoreOr(T.data('id'), 0, null);
                            if (id) {
                                ids_to.push(id);
                            }
                        }
                    });
                    this.selection[(dir ? 'add_selection_array_id' : 'remove_selection_array')](ids_to);
                } else {
                    var id = U.IntMoreOr(t.data('id'), 0, null);
                    id ? this.selection[(dir ? 'add_selection' : 'remove_selection')](id) : 0;
                }
                this._last_selected = current_index;
            }
            return this;
        };


        F.prototype.onCommandClear_selection = function () {
            this.selection.reset();
            return this;
        };

        F.prototype.onCommandSelect_all = function () {
            var ids = [];
            var has_unchecked = 0;
            this.handle.find('input[type=checkbox]').each(function () {
                var t = jQuery(this);
                has_unchecked += (t.prop('checked') ? 0 : 1);
                ids.push(U.IntMoreOr(t.data('id'), 0, null));
            });
            this.selection[(has_unchecked ? 'add_selection_array_id' : 'remove_selection_array')](ids);
            return this;
        };

        F.prototype.onCommandSelect_all_in_group = function () {            
            var n = this.tree.getSelection();
            if (U.safeArray(n).length) {
                var id = U.IntMoreOr(n[0].id, 0, null);
                if (id) {
                    var path = this.tree.tree.get_name_path(n[0].key);
                    EFO.simple_confirm()
                            .set_title("Подтверждение")
                            .set_text("Добавить в выделение все товары из группы<br>\"<b>" + path + "</b>\"<br>и подгрупп?")
                            .set_callback(this, function (confirm, index) {
                                if (U.IntMoreOr(index, 0, 0) === 2) {
                                    this.showLoader();
                                    jQuery.getJSON('/admin/Catalog/API', {action: "enum_product_ids_of", of: id})
                                            .done(this.on_selection_responce.bindToObject(this))
                                            .fail(this.on_selection_fail.bindToObject(this))
                                            .always(this.hideLoader.bindToObject(this));
                                }
                            })
                            .set_style("blue")
                            .set_icon("baloon?")
                            .set_buttons(["Отмена", "Добавить"])
                            .show();
                } else {
                    U.TError("Это виртульная группа");
                    return this;
                }
            } else {
                U.TError("Не выбран раздел");
                return this;
            }
            return this;
        };

        F.prototype.on_selection_responce = function (x) {
            if (U.isObject(x)) {
                if (x.status === 'ok') {
                    return this.on_selection_success(x.ids);
                }
                if (x.status === "error") {
                    return this.on_selection_fail(x.error_info.message);
                }
            }
            return this.on_selection_fail("invalid server responce");
        };

        F.prototype.on_selection_fail = function (x) {
            x = U.NEString(x, "network error");
            U.TError(x);
            return this;
        };

        F.prototype.on_selection_success = function (x) {
            x = U.safeArray(x);
            this.selection.add_selection_array_id(x);
            return this;
        };


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
                    Y.report_fail(FQCN, "Ошибка при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();