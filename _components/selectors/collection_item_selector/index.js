(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js')
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.allow_multi = true;
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
            this.selection = selection(this);
            this.selection.LEM.on("CHANGE", this, this.sync_selection);
            this.selection.LEM.on("CHANGE", this, this.sync_selection_text);
            this.sync_selection_text();
            this.restore_category_filter();
            this.init_table();
            return this;
        };
        F.prototype.sync_selection_text = function () {
            this.getRole('selection_len').html(this.selection.get_length());
            return this;
        };
        F.prototype.sync_selection = function (s) {
            var all = this.handle.find('input[type=checkbox]');
            for (var i = 0; i < all.length; i++) {
                var cb = jQuery(all.get(i));
                var id = U.NEString(cb.data('id'), null);
                if (id && s.exists(id)) {
                    cb.prop('checked', true);
                } else {
                    cb.prop('checked', false);
                }
            }
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            jQuery(document).on('keydown', this.on_key_down.bindToObject(this));
            jQuery(document).on('keyup', this.on_key_up.bindToObject(this));
            return this;
        };

        F.prototype.onAfterHide = function () {
            jQuery(document).off('keydown', this.on_key_down.bindToObject(this));
            jQuery(document).off('keyup', this.on_key_up.bindToObject(this));
            return PARP.onAfterHide.apply(this, APS.call(arguments));
        };

        F.prototype.on_key_down = function (e) {
            if (e.keyCode === 16) {
                this._shift = true;
            }
        };
        F.prototype.on_key_up = function (e) {
            if (e.keyCode === 16) {
                this._shift = false;
            }
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
                {'command': "save", 'text': "Выбрать"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Выбор элемента подборки";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function () {
            this.table.body.DataDriver.refresh();
            return this;
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
                    {id: "selector", key: "selector", property: "selector", "text": "селектор", filter: false, sort: false},
                    {id: "id", key: "id", property: "id", "text": "ID", filter: "Int", sort: true},
                    {id: "ctype", key: "ctype", property: "ctype", "text": "Тип", filter: false, sort: true},
                    {id: 'named', key: 'named', property: 'named', text: 'Наименование', columns: [
                            {id: "common_name", key: "common_name", property: "common_name", "text": "Ориг", filter: "String", sort: true},
                            {id: "name", key: "name", property: "name", "text": "ТЯ", filter: "String", sort: true}
                        ]},
                    {id: "enabled", key: "enabled", property: "enabled", "text": "Вкл", filter: "Bool", sort: true}
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
            this.table.addRenderer('render_ctype', (function (x) {
                return U.NEString({'ctVIDEO': "Видео", 'ctSEASON': "Сериал"}[x.ctype], x.ctype);
            }).bindToObjectWParam(this));
            this.table.addRenderer("is_product_selected", (function (x) {
                return this.selection.exists(x.id);
            }).bindToObjectWParam(this));
            this.table.addRenderer('row_index', (function () {
                this._index = U.IntMoreOr(this._index, 0, 0);
                this._index++;
                return this._index;
            }).bindToObject(this));
            this.table.TableOptions.LEM.on('ON_DATA_RENDER_COMPLETE', this, function () {
                this._index = void(0);
                this._last_selected = void(0);
            });
            var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/MediaContent/API?action=list_soap_and_videos', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            DSParams.onRequestParams(this, this.on_request_params);
            this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('table').get(0));
        };

        F.prototype.on_request_params = function (a, b, c) {

            return this;
        };

        F.prototype.get_item_cloned = function (x) {
            var data = U.safeArray(this.table.body.Renderer.rows);
            var search_id = U.NEString(x, null);
            if (search_id) {
                for (var i = 0; i < data.length; i++) {
                    if (U.NEString(data[i].id, null) === search_id) {
                        return JSON.parse(JSON.stringify(data[i]));
                    }
                }
            }
            return null;
        };
        //</editor-fold>



        F.prototype.onMonitorSelect = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (this.allow_multi) {
                var current_index = U.IntMoreOr(t.data('index'), 0, null);
                var last_index = U.IntMoreOr(this._last_selected, 0, null);

                var dir = t.prop('checked');
                if (id) {
                    if (this._shift) {

                        var items = [];
                        this.handle.find('input[type=checkbox]').each(function () {
                            var c = jQuery(this);
                            var i = U.IntMoreOr(c.data('index'), 0, null);
                            var id = U.NEString(c.data('id'), null);
                            if (i && id) {
                                if ((i <= last_index && i >= current_index) || (i >= last_index && i <= current_index)) {
                                    items.push(id);
                                }
                            }
                        });
                        this.selection[dir ? 'add_array' : "remove_array"](items);
                    } else {
                        this.selection[dir ? 'add' : 'remove'](id);
                    }
                    this._last_selected = current_index;
                }
            } else {
                this.selection.reset();
                this.selection.add(id);
            }
            return this;
        };

        F.prototype.set_allow_multi = function (x) {
            this.allow_multi = U.anyBool(x, false);
            return this;
        };


        F.prototype.save_catagory_filter = function () {
//            var key = MC + "catFilter";
//            localStorage.setItem(key, JSON.stringify({
//                i: U.IntMoreOr(this.getRole('catalog_filter_id').val(), 0, null),
//                n: U.NEString(this.getRole('catalog_filter').val(), null)
//            }));
            return this;
        };

        F.prototype.restore_category_filter = function () {
//            var key = MC + "catFilter";
//            var o = null;
//            try {
//                o = U.safeObject(JSON.parse(localStorage.getItem(key)));
//            } catch (e) {
//                o = {};
//            }
//            o = U.safeObject(o);
//            this.getRole('catalog_filter_id').val(U.IntMoreOr(o.i, 0, null));
//            this.getRole('catalog_filter').val(U.NEString(o.n, null));
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this._exclude = null;
            this.allow_multi = false;
            this._shift = false;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        F.prototype.onCommandSelect_all = function () {
            if (this.allow_multi) {
                var all = this.handle.find('input[type=checkbox]');
                var checked = this.handle.find('input[type=checkbox]:checked');
                var dir = !(all.length === checked.length);
                var ids = [];
                for (var i = 0; i < all.length; i++) {
                    var cb = jQuery(all.get(i));
                    var id = U.NEString(cb.data('id'), null);
                    id ? ids.push(id) : 0;
                }
                this.selection[dir ? "add_array" : "remove_array"](ids);
            }
            return this;
        };
        F.prototype.onCommandReset_selection = function () {
            this.selection.reset();
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="footer commands">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };
        F.prototype.onCommandSave = function () {
            this.save(true);
            return this;
        };
        //</editor-fold>
        //
        F.prototype.onCommandClear_catalog = function () {
            this.getRole('catalog_filter').val('');
            this.getRole('catalog_filter_id').val('');
            this.save_catagory_filter();
            this.load();
            return this;
        };
        F.prototype.onCommandSelect_catalog = function () {
            this.showLoader();
            Y.load('selectors.catalog_group').done(this, this.on_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_selector_done);
            return this;
        };

        F.prototype.on_selector_done = function (x) {
            var xx = U.safeArray(x);
            if (xx && xx.length) {
                this.getRole('catalog_filter').val(U.NEString(xx[0].get_name_path("\\"), null));
                this.getRole('catalog_filter_id').val(U.IntMoreOr(xx[0].id, 0, null));
                this.save_catagory_filter();
                this.load();
            } else {
                this.onCommandClear_catalog();
            }

            return this;
        };

        //</editor-fold>
        //



        //<editor-fold defaultstate="collapsed" desc="save">  

        F.prototype.save = function (keep_open) {
            var selection = [].concat(this.selection.items);
            if (selection.length) {
                this.runCallback(selection);
            } else {
                U.TError(MC + ":nothing selected");
                return this;
            }
            this.selection.reset();
            this.hide().clear();
            return this;
        };
        //</editor-fold>        
        //</editor-fold>


        //<editor-fold defaultstate="collapsed" desc="selection">
        function selection() {
            return (selection.is(this) ? this.init : selection.F).apply(this, APS.call(arguments));
        }
        var SP = U.FixCon(selection).prototype;
        SP.items = null;
        SP.index = null;
        SP.LEM = null;
        SP.ss = null;
        SP.init = function (source) {
            this.ss = source;
            this.reset_silent();
            this.LEM = EFO.Events.LEM();

            return this;
        };

        SP.reset_silent = function () {
            this.items = [];
            this.index = {};
            return this;
        };

        SP.reset = function () {
            this.reset_silent();
            this.LEM.run("CHANGE", this);
            return this;
        };


        SP.get_length = function () {
            return this.items.length;
        };


        SP.add = function (id) {
            if (this.add_silent(id)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.get_item_cloned = function (x) {
            return this.ss.get_item_cloned(x);
        };
        SP.add_silent = function (id) {
            id = U.NEString(id, null);
            if (id) {
                var key = ["P", id].join('');
                if (!U.isObject(this.index[key])) {
                    var item = this.get_item_cloned(id);
                    if (item) {
                        this.items.push(item);
                        this.index[key] = item;
                        return true;
                    }
                }
            }
            return false;
        };

        SP.add_array = function (ids) {
            if (this.add_array_silent(ids)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.add_array_silent = function (ids) {
            var ids = U.safeArray(ids);
            var ca = 0;
            for (var i = 0; i < ids.length; i++) {
                if (this.add_silent(ids[i])) {
                    ca++;
                }
            }
            return ca ? true : false;
        };

        SP.remove = function (id) {
            if (this.remove_silent(id)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.remove_array = function (ids) {
            if (this.remove_array_silent(ids)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.remove_silent = function (id) {
            id = U.NEString(id, null);
            if (id) {
                var key = ["P", id].join('');
                if (U.isObject(this.index[key])) {
                    var item = this.index[key];
                    delete(this.index[key]);
                    var ix = this.items.indexOf(item);
                    if (ix >= 0) {
                        this.items = [].concat(this.items.slice(0, ix), this.items.slice(ix + 1));
                    }
                    return true;
                }
            }
            return false;
        };

        SP.remove_array_silent = function (ids) {
            ids = U.safeArray(ids);
            var cc = 0;
            for (var i = 0; i < ids.length; i++) {
                if (this.remove_silent(ids[i])) {
                    cc++;
                }
            }
            return cc ? true : false;
        };

        SP.exists = function (x) {
            var key = ["P", U.NEString(x, null)].join('');
            return U.isObject(this.index[key]);
        };

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