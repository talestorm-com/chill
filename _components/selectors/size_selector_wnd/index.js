(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js'),
        Y.js('/assets/js/types/size_item.js')
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
//        var SVG = null;
//        /*<?=$this->create_svg('SVG')?>*/
//        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.allow_multi = false;
        F.prototype.selection = null;
        F.prototype.selection_index = null;
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
            this._selection = {};
            this.init_table_pre();
            this.reset_selection();

            return this;
        };

        F.prototype.on_key_down = function (e) {
            if (e.keyCode === 16) {
                this.shift = true;
            }
        };

        F.prototype.on_key_up = function (e) {
            if (e.keyCode === 16) {
                this.shift = false;
            }
        };

        F.prototype.reset_selection = function () {
            this.selection = [];
            this.selection_index = {};
            try {
                var s = U.safeArray(this.datasource.source);
                this.index = null;
                this.last_index = null;
                this.datasource.setSource([].concat(s));
            } catch (e) {

            }
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            jQuery(document).off('keydown', this.on_key_down.bindToObject(this));
            jQuery(document).off('keyup', this.on_key_up.bindToObject(this));
            jQuery(window).off('storage', this.on_storage_event.bindToObject(this));
            jQuery(document).on('keydown', this.on_key_down.bindToObject(this));
            jQuery(document).on('keyup', this.on_key_up.bindToObject(this));
            jQuery(window).on('storage', this.on_storage_event.bindToObject(this));
            this.placeAtCenter();
            return this;
        };

        F.prototype.onAfterHide = function () {
            jQuery(document).off('keydown', this.on_key_down.bindToObject(this));
            jQuery(document).off('keyup', this.on_key_up.bindToObject(this));
            jQuery(window).off('storage', this.on_storage_event.bindToObject(this));
            return PARP.onAfterHide.apply(this, APS.call(arguments));
        };

        F.prototype.on_storage_event = function (e) {
            if (e.originalEvent.key === 'SIP_COLLECTION') {
                this.load();
            }
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
                {'command': "reset_selection", 'text': "Сбросить выделение", buttonClass: MC + "pullLeft"},
                {'command': "add_new", 'text': "Добавить размер", buttonClass: MC + "pullLeft " + MC + "CrimsonBtn", is_link: true, target: MC + "editor", href: "/admin/SizeVoc/index"},
                {'command': "cancel", 'text': "Отмена"},
                {'command': "save", 'text': "Выбрать"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Выбор размеров";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="table">               
        F.prototype.init_table_pre = function () {
            this.showLoader();
            jQuery.getJSON('/admin/SizeVoc/API', {action: 'metadata'})
                    .done(this.on_meta_responce.bindToObject(this))
                    .fail(this.on_meta_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_meta_fail = function () {
            U.TError("Ошибка при загрузке метаданных");
            this.hide().clear();
            return this;
        };
        F.prototype.on_meta_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this._ext_sizes = U.safeArray(d.size_system_voc);
                    this.init_table();
                    this.load();
                    return this;
                }
            }
            return this.on_meta_fail();
        };

        F.prototype.load = function () {

            this.showLoader();
            jQuery.getJSON('/admin/SizeVoc/API', {action: "get_size_list"})
                    .done(this.on_load_responce.bindToObject(this))
                    .fail(this.on_load_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d) && d.status === 'ok') {
                this.on_load(U.safeArray(d.sizes), U.safeArray(d.aliases));
                return this;
            }
            return this.on_load_fail();
        };

        F.prototype.on_load_fail = function () {
            U.TError("ошибка при загрузке данных");
            return this.hide().clear();
        };



        F.prototype.on_load = function (items, als) {
            this.index = null;
            this.last_index = null;
            this.items = E.SIP_COLLECTION(items, als, MC);
            this.items.sort_by_values();
            this.datasource.setSource(this.items.items);
            return this;
        };

        F.prototype.table_def = function () {
            var result = {
                id: MC,
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "selector", key: "selector", property: "selector", "text": "selector", filter: false, sort: false},
                    {id: "id", key: "id", property: "id", "text": "ID", filter: false, sort: false},
                    {id: "guid", key: "guid", property: "guid", "text": "id 1C", filter: false, sort: false, column_title: "Размер, как он приходит от 1c - для импорта."},
                    {id: "larro", key: "larro", property: "size", "text": "larro", filter: false, sort: false, column_title: "Размер по размерной таблице магазина"}

                ]
            };
            for (var i = 0; i < this._ext_sizes.length; i++) {
                result.columns.push({
                    id: "size_" + this._ext_sizes[i].id,
                    key: "size_" + this._ext_sizes[i].id,
                    property: "size" + this._ext_sizes[i].id,
                    "text": this._ext_sizes[i].short_name,
                    column_title: this._ext_sizes[i].name,
                    filter: false,
                    sort: false});
            }
            return result;
        };

        F.prototype.init_table = function () {
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}","TPLS")?>*/
            this.table = ADVT.Table(this.table_def());
            var TM = ADVT.TemplateManager.LocalTemplateManager(MC);
            var proto_tp = TM.getTemplate('row.cellContent_proto');
            for (var i = 0; i < this._ext_sizes.length; i++) {
                var ntp = Mustache.render(proto_tp, {size_id: this._ext_sizes[i].id});
                TM.addTemplate('row.cellContent_size_' + this._ext_sizes[i].id, ntp);
            }
            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });
            this.table.addRenderer('description_subtext', function () {
                var st = U.NEString(U.NEString(U.repair_text(this.info), '').substr(0, 50), '');
                if (st !== this.info) {
                    st += "...";
                }
                return st;
            });
            this.table.addRenderer('get_larro_value', function () {
                return U.NEString(this.size, '');
            });
            this.table.addRenderer('get_alias_value', (function () {
                return this._get_alias_value.bindToObjectWParam(this);
            }).bindToObject(this));


            this.table.addRenderer('row_index', (function () {
                this.index = U.IntMoreOr(this.index, 0, 0);
                this.index++;
                return this.index;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index', (function () {
                this.index = U.IntMoreOr(this.index, 0, 0);
                return this.index;
            }).bindToObject(this));
            this.table.addRenderer('is_selected', (function (x) {
                var key = ["A", x.id].join('');
                return (this.selection_index[key] === x.id) ? true : false;
            }).bindToObjectWParam(this));
            //var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/SizeVoc/API?action=get_list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            //this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('table').get(0));
        };

        F.prototype._get_alias_value = function (c, a, b) {
            var al = U.safeObject(U.safeObject(c).aliases);
            var alias_key = ['A', a].join('');
            if (U.isObject(al[alias_key]) && U.NEString(a, null) === al[alias_key].i) {
                return U.NEString(al[alias_key].v, '');
            }
            return '';
        };
        //</editor-fold>

        F.prototype.set_allow_multi = function (x) {
            this.allow_multi = U.anyBool(x, false);
            return this;
        };

        F.prototype.onMonitorSelector = function (t) {
            var current_index = U.IntMoreOr(t.data('index'), 0, null);
            if (this.shift && this.last_index && this.last_index !== current_index) {
                var last_index = this.last_index;
                var items_to_check = jQuery();
                this.handle.find('input[type=checkbox]').each(function () {
                    var index = U.IntMoreOr(jQuery(this).data('index'), 0, null);
                    if (index && ((index <= current_index && index >= last_index) || (index >= current_index && index <= last_index))) {
                        items_to_check = items_to_check.add(this);
                    }
                });
                items_to_check.prop('checked', U.anyBool(t.prop('checked'), false));

            }
            this.last_index = current_index;
            this.rescan_selection();
            return this;
        };

        F.prototype.rescan_selection = function () {
            var sel = {};
            var sela = [];
            this.handle.find('input[type=checkbox]:checked').each(function () {
                var item = jQuery(this);
                var id = U.IntMoreOr(item.data('id'), 0, null);
                if (id) {
                    var key = ["A", id].join('');
                    sel[key] = id;
                    sela.push(id);
                }
            });
            this.selection = sela;
            this.selection_index = sel;
            return this;
        };



        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.reset_selection();
            this.index = null;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="footer commands">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };
        F.prototype.onCommandSave = function () {
            this.save(true);
            return this;
        };
        //</editor-fold>
        F.prototype.onCommandReset_selection = function () {
            this.reset_selection();
            return this;
        };

        F.prototype.onCommandSelectAll = function () {
            var total_length = this.handle.find('input[type=checkbox]').length;
            var selected_length = this.handle.find('input[type=checkbox]:checked').length;
            if (total_length === selected_length) {
                this.handle.find('input[type=checkbox]').prop('checked', false);
            } else {
                this.handle.find('input[type=checkbox]').prop('checked', true);
            }
            this.rescan_selection();
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="save">  

        F.prototype.save = function (keep_open) {
            var selected = [];
            for (var i = 0; i < this.datasource.source.length; i++) {
                var key = ["A", this.datasource.source[i].id].join('');
                if (this.selection_index[key] === this.datasource.source[i].id) {
                    selected.push(this.datasource.source[i]);
                }
            }
            if (!selected.length) {
                U.TError("nothing selected");
                return this;
            }

            this.runCallback(selected);
            this.hide().clear();
            return this;
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