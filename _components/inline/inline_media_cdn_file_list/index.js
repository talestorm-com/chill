(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/PathBuilder/PathBuilder.js')
    ];
    //</editor-fold>    
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.flatController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = E.ADVTable;
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
            return  (F.is(this) ? this.init : F.F).apply(this, APS.call(arguments));
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.AL = null;
        F.prototype.ALA = null;
        F.prototype.CI = null;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">   '
        F.prototype.onInit = function (e_instance_id) {
            this.AL = {};
            e_instance_id = U.NEString(e_instance_id, null);
            e_instance_id ? 0 : U.Error("property editor requires instance id as parameter");
            this.instance_id = [MC, e_instance_id].join('_');
            PARP.onInit.apply(this, APS.call(arguments));
            this.get_instance_id = this._get_instance_id.bindToObject(this);
            this.init_table();

            return this;
        };

        F.prototype.set_ci = function (c) {
            this.CI = U.isObject(c) ? c : null;
            return this;
        };

        F.prototype.ci_get_id = function () {
            if (this.CI) {
                try {
                    return this.CI.file_list_get_id();
                } catch (e) {

                }
            }
            return null;
        };
        F.prototype.ci_get_path = function () {
            if (this.CI) {
                try {
                    return this.CI.file_list_get_path();
                } catch (e) {

                }
            }
            return null;
        };

        F.prototype.ci_get_root_pathbuilder = function () {
            if (this.CI) {
                try {
                    return this.CI.file_list_get_pathbuilder();
                } catch (e) {

                }
            }
            return null;
        };

        F.prototype._get_instance_id = function () {
            return this.instance_id;
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

        //</editor-fold>   
        F.prototype.get_table_def = function () {
            return {
                id: this.instance_id,
                filters: false,
                sorter: false, //'SimpleLocal',
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "cdn_id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "cdn_id", key: "cdn_id", property: "cdn_id", "text": "cdn_id", filter: false, sort: false},
                    {id: "selector", key: "selector", property: "selector", "text": "selector", filter: false, sort: false},
                    {id: "sort", key: "sort", property: "sort", "text": "sort", filter: false, sort: false},
                    {id: "content_type", key: "content_type", property: "content_type", "text": "Mime", filter: false, sort: true},
                    {id: "enabled", key: "enabled", property: "enabled", "text": "Вкл", filter: false, sort: true},
                    {id: "size", key: "size", property: "size", "text": "Р-р", filter: false, sort: false},
                    {id: "info", key: "info", property: "info", "text": "Info", filter: false, sort: true},
                    {id: "control", key: "control", property: "control", "text": "control", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_table = function () {
            var def = this.get_table_def();
            var TMPLTS = null;
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplatesRet(__DIR__,"TMPLTS","TPLS")?>*/
            ADVT.TemplateManager.LocalTemplateManager(this.instance_id, TMPLTS);
            this.table = ADVT.Table(def);

            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });

            this.table.addRenderer('is_element_enabled', (function (x) {
                return U.anyBool(x.enabled, false);
            }).bindToObjectWParam(this));



            this.table.addRenderer('row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                this._row_index++;
                return this._row_index;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                return this._row_index;
            }).bindToObject(this));
            this.table.addRenderer('row_index2', (function () {
                this._row_index2 = U.IntMoreOr(this._row_index2, 0, 0);
                this._row_index2++;
                return this._row_index2;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index2', (function () {
                this._row_index2 = U.IntMoreOr(this._row_index2, 0, 0);
                return this._row_index2;
            }).bindToObject(this));
            this.table.addRenderer('row_index3', (function () {
                this._row_index3 = U.IntMoreOr(this._row_index3, 0, 0);
                this._row_index3++;
                return this._row_index3;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index3', (function () {
                this._row_index3 = U.IntMoreOr(this._row_index3, 0, 0);
                return this._row_index3;
            }).bindToObject(this));

            this.source = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.source);
            this.table.appendTo(this.getRole(MC + "Table").get(0));
            return this;
        };



        F.prototype.set_data = function (x) {
            x = U.safeArray(x);
            var dsa = [];
            for (var i = 0; i < x.length; i++) {
                if (U.isObject(x[i])) {
                    var pi = U.NEString(x[i].cdn_id, null);
                    var pv = U.NEString(x[i].size, null);
                    var pn = U.NEString(x[i].info, null);
                    var pe = U.anyBool(x[i].enabled, false);
                    var pc = U.NEString(x[i].content_type, null);
                    if (pi && pv && pc) {
                        dsa.push({cdn_id: pi, info: pn, size: pv, enabled: pe, content_type: pc, selector: U.NEString(x[i].selector, null),sort:U.IntOr(x[i].sort,0),xi:i });
                    }
                }
            }
            dsa.sort(function(a,b){
                var r = a.sort-b.sort;
                return r===0?a.xi-b.xi:r;
            });
            this.source.setSource(dsa);
            return this;
        };

        /*
         *  оптимизировать пути загрузчика
         *  вывести трейлер в таблицу контент-типов 
         *для этого надо удалить все трейлеры
         */

        F.prototype.get_data = function () {
            return [].concat(this.source.source);
        };

        F.prototype.onCommandAdd_file = function () {
            this.showLoader();
            Y.load('cdn.FileManager')
                    .done(this, this.file_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.file_selector_ready = function (x) {
            var pathbuilder = this.ci_get_root_pathbuilder();
            x.show().load(pathbuilder).setCallback(this, this.file_select_done);
            return this;
        };

        F.prototype.file_select_done = function (sel) {
            var id = U.NEString(sel, null);
            if (id) {
                this.load_info(id);
            }
            return this;
        };

        F.prototype.load_info = function (id) {
            var self = this;
            this.showLoader();
            jQuery.getJSON('/admin/CDNAPI/API', {action: "info", id: id})
                    .done(this.on_info_ready.bindToObject(this))
                    .fail(this.on_info_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_info_ready = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_info_success(d.cdnapi);
                }
                if (d.status === 'error') {
                    return this.on_info_fail(d.error_info.message);
                }
            }
            return this.on_info_fail("invalid server response");
        };
        F.prototype.on_info_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this;
        };
        F.prototype.on_info_success = function (cd) {
            var id = U.NEString(cd.id, null);
            var width = 0;
            var height = 0;
            var ct = 'unknown';
            try {
                width = cd.info.advanced.video_streams[0].width;
                height = cd.info.advanced.video_streams[0].height;
            } catch (e) {

            }
            try {
                ct = U.NEString(cd.info.content_type, "unknown");
            } catch (e) {

            }
            var size = [width, 'x', height].join('');
            var info = JSON.stringify(cd.info);
            var item = {
                content_id: this.ci_get_id(),
                cdn_id: id,
                size: size, info: info,
                content_type: ct,
                enabled: true
            };
            this.add_item_replace(item);
            return this;
        };
        F.prototype.add_item_replace = function (item) {
            var ni = [];
            var sa = U.safeArray(this.source.source);
            for (var i = 0; i < sa.length; i++) {
                if (item.cdn_id !== sa[i].cdn_id) {
                    ni.push(sa[i]);
                }
            }
            ni.push(item);
            this.source.setSource(ni);
            return this;
        };


        F.prototype.onCommandFile_info = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                this.showLoader();
                Y.load('cdn.fileinfo')
                        .done(this, function (x) {
                            x.show().load(id);
                        })
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            }
            return this;
        };

        F.prototype.onCommandFile_remove = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                var s = [].concat(this.source.source);
                var ns = [];
                for (var i = 0; i < s.length; i++) {
                    if (s[i].cdn_id === uid) {
                        continue;
                    }
                    ns.push(s[i]);
                }
            }
            this.source.setSource(ns);
            return this;
        };

        F.prototype.get_by_uid = function (uid) {
            uid = U.NEString(uid, null);
            if (uid) {
                for (var i = 0; i < this.source.source.length; i++) {
                    if (this.source.source[i].cdn_id === uid) {
                        return this.source.source[i];
                    }
                }

            }
            return null;
        };

        F.prototype.onMonitorLanguage_selector = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                var prop = this.get_by_uid(uid);
                if (prop) {
                    prop.selector = U.NEString(t.val(), null);
                    t.val(prop.selector);
                }
            }
            return this;
        };
        F.prototype.onMonitorRow_sort = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                var prop = this.get_by_uid(uid);
                if (prop) {
                    prop.sort = U.IntOr(t.val(), 0);
                    t.val(prop.sort);
                }
            }
            return this;
        };

        F.prototype.onMonitorEnabled = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                var prop = this.get_by_uid(uid);
                if (prop) {
                    prop.enabled = U.anyBool(t.prop('checked'), false);
                    t.prop('checked', prop.enabled);
                }
            }
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.source.setSource([]);
            this._row_index = 0;
            this._row_index2 = 0;
            this._row_index3 = 0;
            return this;
        };

        //</editor-fold>        

        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
        };
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