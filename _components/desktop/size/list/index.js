(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
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
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.init_table_pre();
            return this;
        };


        F.prototype.onAfterShow = function () {
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

        F.prototype.clear = function () {

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
            this.items = E.SIP_COLLECTION(items, als, MC);
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
                    {id: "id", key: "id", property: "id", "text": "ID", filter: "Int", sort: true},
                    {id: "guid", key: "guid", property: "guid", "text": "id 1C", filter: "String", sort: true, column_title: "Размер, как он приходит от 1c - для импорта."},
                    {id: "larro", key: "larro", property: "size", "text": "larro", filter: "String", sort: true, column_title: "Размер по размерной таблице магазина"}

                ]
            };
            for (var i = 0; i < this._ext_sizes.length; i++) {
                result.columns.push({
                    id: "size_" + this._ext_sizes[i].id,
                    key: "size_" + this._ext_sizes[i].id,
                    property: "size" + this._ext_sizes[i].id,
                    "text": this._ext_sizes[i].short_name,
                    column_title: this._ext_sizes[i].name,
                    filter: "String",
                    sort: true});
            }
            result.columns.push({id: "control", key: "control", property: "control", text: "Контроль", sort: false, filter: false});
            return result;
        };

        F.prototype.init_table = function () {
            window.AAA = this;
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
            //var DSParams = ADVT.DataSource.SimplePostParams({'url': '/admin/SizeVoc/API?action=get_list', method: 'post'}, ADVT.DataSource.Extractor.MixExtractor({}));
            //this.datasource = ADVT.DataSource.RemoteDataSource(DSParams, this.table.TableOptions);
            this.datasource = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('body').get(0));
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
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        F.prototype.reload = function () {
            this.datasource.setSource(U.safeArray(U.safeObject(this.items).items));
            return this;

        };
        //</editor-fold>                        
        F.prototype.install = function (x) {
            var node = document.getElementById(x);
            if (node) {
                this.container_node = node;
                this.handle.appendTo(node);
                this.show();
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

        F.prototype.onCommandAdd = function () {
            this.items.add_default();
            this.datasource.setSource(this.items.items);
            window.setTimeout(this.scrollDown.bindToObject(this), 50);
            return this;
        };
        F.prototype.scrollDown = function () {
            this.table.LayoutManager.getLayoutArea('scrollFixArea').scrollTop = 500000000;
            return this;
        };



        F.prototype.onCommandRemove = function (t) {
            var id = U.NEString(t.data('id'), null);
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text("Удалить этот размер?<br><b style=\"color:gray;font-size:.9em\">Это действие удалит все записи размера из всех товаров<b><br><b style=\"color:gray;font-size:.9em\">Изменения вступят в силу после сохранения таблицы!<b>")
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            this.items.remove_item(id);
                            this.reload();
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не удалять", "Удалить"])
                    .show();
            return this;
        };



        F.prototype.onMonitorGuid = function (t) {
            var v = U.NEString(t.val(), null);
            var id = U.NEString(t.data('id'), null);
            if (id) {
                var sip_key = ['SIP', id].join('');
                if (E.SIP_COLLECTION.item.is(this.items.index[sip_key])) {
                    this.items.index[sip_key].guid = v;
                    t.val(U.NEString(this.items.index[sip_key].guid, ''));
                }
            }
            console.log(this.items);
            return this;
        };

        F.prototype.onMonitorValue = function (t) {
            var v = U.NEString(t.val(), null);
            var size_id = U.NEString(t.data('id'), null);
            var alter_id = U.NEString(t.data('sizeId'), null);
            if (size_id) {
                var sip_key = ['SIP', size_id].join('');
                if (E.SIP_COLLECTION.item.is(this.items.index[sip_key])) {
                    if (alter_id) {
                        this.items.index[sip_key].add_alias(alter_id, v);
                        t.val(U.NEString(this.items.index[sip_key].get_alias_value(alter_id), ''));
                    } else {
                        this.items.index[sip_key].size = v;
                        t.val(U.NEString(this.items.index[sip_key].size, ''));
                    }
                }
            }
            return this;
        };

        F.prototype.onCommandSave = function () {
            try {
                this.items.self_check();
            } catch (e) {
                var html_id = U.NEString(e.html_id, null);
                if (html_id) {
                    var node = this.handle.find('#' + html_id);
                    if (node && node.length) {
                        node.addClass(MC + "InvalidRow");
                        this.table.LayoutManager.getLayoutArea('scrollFixArea').scrollTop = node.get(0).getBoundingClientRect().top;
                    }
                }
                U.TError(e);
                return this;
            }
            var data = {action: 'post_sizes_table', data: JSON.stringify(this.items.export())};
            this.showLoader();
            jQuery.post('/admin/SizeVoc/API', data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_post_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_post_success(U.safeArray(d.sizes), U.safeArray(d.aliases));
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_post_fail(d.error_info.message);
                }
            }
            return this.on_post_fail("invalid server responce");
        };

        F.prototype.on_post_success = function (s, a) {
            localStorage.setItem('SIP_COLLECTION', (new Date()).getTime());
            this.on_load(s, a);
            return this;
        };


        F.prototype.on_post_fail = function (x) {
            x = U.NEString(x, "network error");
            U.TError(x);
            return this;
        };

        F.prototype.onMonitorRow = function (x) {
            x.closest('.ADVTableRow').removeClass(MC + "InvalidRow");
            return this;
        };

        F.prototype.onCommandReload = function () {
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text("Перезагрузить таблицу?<br><b style=\"color:gray;font-size:.9em\">Все изменения будут сброшены!<b>")
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            this.load();
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Отмена", "Перезагрузить"])
                    .show();
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