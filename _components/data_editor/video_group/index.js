(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.BlobImageUploader').promise,
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
            this.init_editor();
            this.init_image_list();
            this.init_property_editor();
            this.init_items();
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            this.editor.init_editor();
            return this;
        };
        F.prototype.onAfterHide = function () {
            this.editor.destroy_editor();
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
                //    {'command': "test", 'text': "test"},
                {'command': "cancel", 'text': "Отмена"},
                {'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Редактирование видеокурса";
        };
        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_info"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_properties"
                        , MC + ".TAB_presets"

            ]);
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/Video/API', {action: "get", id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(d.video_group);
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            this.uploader.set_owner_id(this.getField('id').val());
            this.uploader.reload();

            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">
        F.prototype.init_editor = function () {
            var fn = Y.get_loaded_component('inline.mce_cm_html');
            this.editor = fn();
            this.editor.setContainer(this.getField('info'));
            return this;
        };

        F.prototype._get_field_info = function () {
            return this.editor.getText();
        };
        F.prototype._set_field_info = function (c) {
            this.editor.setText(U.NEString(c.info, ''), U.anyBool(c.html_mode, true));
            return this;
        };
        F.prototype._set_field_cost = function (x, f) {
            f.val(EFO.Checks.formatPriceNSD(U.FloatMoreEqOr(x.cost, 0, 0)));
            return this;
        };
        F.prototype._get_field_cost = function () {
            return U.FloatMoreEqOr(this.getField('cost').val(), 0, 0);
        };

        F.prototype._set_field_items = function (c, f) {
            this.items_source.setSource(U.safeArray(c.items));
            return this;
        };
        F.prototype._set_field_html_mode = function () {
            return this;
        };
        F.prototype._get_field_html_mode = function () {
            return this.editor.get_check_state();
        };

        F.prototype._set_field_default_image = function () {
            return this;
        };

        F.prototype._get_field_default_image = function () {
            return this.uploader.get_default_image();
        };

        F.prototype._set_field_path = function (c) {
            this.getField('path').val(U.NEString(c.path, "Корневая"));
            return this;
        };

        F.prototype._set_field_import_processor = function (c) {
            var xx = U.safeArray(c.import_processor);
            var r = [];
            for (var i = 0; i < xx.length; i++) {
                var xs = U.NEString(xx[i], null);
                xs ? r.push(xs) : 0;
            }
            this.getField('import_processor').val(r.join(', '));
            return this;
        };
        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };

        F.prototype._get_field_items = function () {
            return [];
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="image_list">
        F.prototype.init_image_list = function () {
            var UF = Y.get_loaded_component('media.BlobImageUploader');
            this.uploader = UF();
            this.uploader.set_params("videogroup", null);
            this.uploader.setContainer(this.getRole("imagelistwrapper"));
            this.uploader.set_field_name('video_common_image');
            return this;
        };
        F.prototype.init_property_editor = function () {
            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="items">
        F.prototype.items_def = function () {
            return {
                id: MC,
                filters: false,
                sorter: false,
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "uid",
                css: MC,
                interceptClicks: false,
                allowRowSorting: true,
                columns: [
                    {id: "uid", key: "uid", property: "uid", "text": "uid", filter: false, sort: false, visible: false, hidden: true},
                    {id: "image", key: "image", property: "image", "text": "image", filter: false, sort: false},
                    {id: "name", key: "name", property: "name", "text": "Наименование", filter: false, sort: false},
                    {id: "control", key: "control", property: "control", text: "Контроль", sort: false, filter: false}
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
            this.table.addRenderer('get_image_url', function () {
                if (this.file && !this.image_removed) {
                    return window.URL.createObjectURL(this.file);
                } else if (this.image && !this.image_removed) {
                    var d = (new Date()).getTime();
                    return ["/media/video_item/", [this.id, this.uid].join('_'), '/', this.image, '.SW_250H_250.jpg?ap=qp', d].join('');
                } else {
                    return "";
                }
            });

            this.items_source = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.items_source);
            this.table.appendTo(this.getRole('presets').get(0));
            this.table.TableOptions.LEM.on('ON_ROW_DRAG_SORTED', this, this.on_sorted);
        };
        F.prototype.on_sorted = function (row, after) {
            var row_uid = U.NEString(jQuery(row).data('id'), null);
            var after_uid = U.NEString(jQuery(after).data('id'), null);
            if (row_uid && after_uid && after_uid !== row_uid) {
                var s = this.items_source.source;
                var ns = [];
                var after_item = this.get_video_by_uid(after_uid);
                var row_item = this.get_video_by_uid(row_uid);
                for (var i = 0; i < s.length; i++) {
                    if (row_item === s[i]) {
                        continue;
                    }
                    ns.push(s[i]);
                    if (s[i] === after_item) {
                        ns.push(row_item);
                    }
                }
                for (var i = 0; i < ns.length; i++) {
                    ns[i].sort = (i + 1) * 10;
                }
                this.t_set_items(ns);
            }
            return this;
        };
        F.prototype.init_items = function () {
            this.init_table();
            return this;
        };
        F.prototype.t_set_items = function (x) {
            x = U.safeArray(x);
            x.sort(function (a, b) {
                var r = U.IntOr(a.sort, 0) - U.IntOr(b.sort, 0);
                if (r === 0) {
                    r = U.NEString(a.uid, '') > U.NEString(b.uid, '') ? -1 : (U.NEString(a.uid, '') < U.NEString(b.uid) ? 1 : 0);
                }
                return r;
            });
            this.items_source.setSource(x);
        };
        //</editor-fold>
        F.prototype.onCommandVideo_remove = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                var item = this.get_video_by_uid(uid);
                if (item) {
                    var s = this.items_source.source;
                    var index = s.indexOf(item);
                    if (index >= 0) {
                        var r = s.slice(0, index).concat(s.slice(index + 1));
                        this.t_set_items(r);
                    }
                }
            }
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.uploader ? this.uploader.clear() : false;
            this.t_set_items([]);
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">
        F.prototype.onMonitorCost = function (t) {
            t.val(EFO.Checks.formatPriceNSD(U.FloatMoreEqOr(t.val(), 0, 0)));
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
        F.prototype.onCommandEdit_video = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                this._video_to_edit = uid;
                this.launch_video_editor();
            }
            return this;
        };
        F.prototype.onCommandAdd_video = function () {
            this._video_to_edit = null;
            this.launch_video_editor();
            return this;
        };
        F.prototype.launch_video_editor = function () {
            this.showLoader();
            Y.load('data_editor.video_tutorial_editor')
                    .done(this, this.on_video_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.vid_changed = function (x, y, z) {

            //{id:this.model.id,uid:this.model.uid,video:this.model.video}
            if (U.isObject(x)) {
                if (x.id === U.IntMoreOr(this.getField('id').val(), 0, null)) {
                    var item = this.get_video_by_uid(x.uid);
                    if (item) {
                        item.video = x.video;
                    }
                }
            }
            return this;
        };

        F.prototype.on_video_editor_ready = function (x) {
            if (!this.editor_loaded) {
                this.editor_loaded = true;
                x.LEM.on('VID_LRR', this, this.vid_changed);
            }
            x.show();
            if (this._video_to_edit) {
                x.load(this.get_video_by_uid(this._video_to_edit));
            } else {
                x.clear();
                x.init_empty_model();
            }
            x.setCallback(this, this.on_video_editor_done);
            return this;
        };
        F.prototype.get_video_by_uid = function (uid) {
            uid = U.NEString(uid, null);
            if (uid) {
                for (var i = 0; i < U.safeArray(U.safeObject(this.items_source).source).length; i++) {
                    if (this.items_source.source[i].uid === uid) {
                        return this.items_source.source[i];
                    }
                }
            }
            return null;
        };

        F.prototype.on_video_editor_done = function (x) {
            x = U.safeObject(x);
            var uid = U.NEString(x.uid, null);
            if (uid) {
                var source = this.items_source.source;
                var op = this.get_video_by_uid(uid);
                if (op) {
                    var index = source.indexOf(op);
                    if (index >= 0) {
                        source[index] = x;
                    } else {
                        source.push(x);
                    }
                } else {
                    source.push(x);
                }
                this.t_set_items(source);
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

        F.prototype.prepare_videos_to_upload = function (data) {
            data.items = [];
            var s = [].concat(this.items_source.source);
            for (var i = 0; i < s.length; i++) {
                var cp = EFO.Filter.Filter().applyFiltersToHash(s[i], this.getFilters().getSectionExport('item'));
                EFO.Filter.Filter().throwValuesErrorFirst(cp, true);
                data.items.push(cp);
            }
            return this;
        };

        F.prototype.collect_item_image_data = function (form_data) {
            var fn = "video_item";
            var s = [].concat(this.items_source.source);
            for (var i = 0; i < s.length; i++) {
                if (!s[i].image_removed && s[i].file) {
                    form_data.append([fn, "file_image", s[i].uid].join('_'), s[i].file);
                }
                if (!s[i].video_removed && s[i].video_file) {
                    form_data.append([fn, "v", s[i].uid].join('_'), s[i].video_file); //conversion?!!!
                    if (!U.anyBool(s[i].convert, true)) {
                        form_data.append([fn, "file_video_convert", s[i].uid].join('_'), false); //conversion?
                    }
                }
            }
            debugger;
            return this;
        };

        F.prototype.collect_item_image_data_fields = function (fdo) {

            var fn = "video_item";
            var s = [].concat(this.items_source.source);
            for (var i = 0; i < s.length; i++) {
                if (!s[i].image_removed && s[i].file) {
                    fdo[[fn, "image", s[i].uid].join('_')] = s[i].image_field;
                }
                if (!s[i].video_removed && s[i].video_file) {
                    fdo[[fn, "video", s[i].uid].join('_')] = s[i].video_field;
                    fdo[[fn, "convert", s[i].uid].join('_')] = (U.anyBool(s[i].convert, true) ? 1 : 0);
                }
            }
            return this;
        };

        F.prototype.save = function (keep_open) {
            
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (ee) {
                U.Error([MC,ee.message].join(':'));
            }
            this.prepare_videos_to_upload(data);
            if (data.active && !data.items.length) {
                U.Error("Нельзя публиковать пустую группу!");
            }
            var post_data = {
                action: "put",
                data: JSON.stringify(data)
            };
            this.collect_item_image_data_fields(post_data);
//            if (window.FormData) {
//                var post_data = new FormData();
//                post_data.append('action', "put");
//                post_data.append('data', JSON.stringify(data));
//            } else {
//                U.Error("unsupported browser API");
//                var post_data = {
//                    action: "put",
//                    data: JSON.stringify(data)
//                };
//            }
            this.showLoader();
            var publisher_keys = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('publisher'));
            for(var k in publisher_keys){
                if(publisher_keys.hasOwnProperty(k) && !U.isCallable(publisher_keys[k])){
                    post_data[k]=publisher_keys[k];
                }
            }
            // this.collect_item_image_data(post_data);
            this.uploader.collect_data_fields(post_data, this, function (u, d) {
                var rq = EFO.IFrameTransport('/admin/Video/API', d);
                //var rq = EFO.FormDataRequest('/admin/Video/API', d);
                rq.done(this, this.on_post_responce);
                rq.fail(this, this.on_network_fail);
                rq.always(this, this.hideLoader);
                if (false) {
                    if (window.FormData && (d instanceof window.FormData)) {
                        rq.done(this, this.on_post_responce);
                        rq.fail(this, this.on_network_fail);
                        rq.always(this, this.hideLoader);
                        if (false) {
                            jQuery.ajax('/admin/Video/API', {
                                data: d,
                                type: 'POST',
                                contentType: false,
                                processData: false,
                                dataType: 'json'
                            }).done(this.on_post_responce.bindToObject(this))
                                    .fail(this.on_network_fail.bindToObject(this))
                                    .always(this.hideLoader.bindToObject(this));
                        }
                    } else {
                        jQuery.post('/admin/Video/API', post_data, null, 'json')
                                .done(this.on_post_responce.bindToObject(this))
                                .fail(this.on_network_fail.bindToObject(this))
                                .always(this.hideLoader.bindToObject(this));
                    }
                }
            });
            return this;
        };
        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.video_group));
                    if (U.isArray(d.warnings) && d.warnings.length) {
                        this.show_warning(d.warnings.join("\n"));
                    }
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
            if (U.isObject(x) && x instanceof Error) {
                x = x.message;
            }
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