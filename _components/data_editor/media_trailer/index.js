(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('media.image_uploader').promise,
        Y.load('inline.inline_media_cdn_file_list').promise,
        Y.js('/assets/js/PathBuilder/PathBuilder.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
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
        F.prototype.pathbuilder = null;
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
            this.init_file_list();
            this.init_table();
            this.init_taglist();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_files"
                        , MC + ".TAB_names"
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
            return "Редактирование Трейлера";
        };
        //</editor-fold>   
        F.prototype.init_taglist = function () {
            var cf = Y.get_loaded_component('inline.inline_tag_list');
            this.genre_list = cf(this, "genres");
            this.genre_list.setContainer(this.getRole('genres'));
            this.country_list = cf(this, "countries");
            this.country_list.setContainer(this.getRole('countries'));
            return this;
        };
        F.prototype._get_field_genres = function () {
            return this.genre_list.get_data();
        };
        F.prototype._get_field_countries = function () {
            return this.country_list.get_data();
        };
        F.prototype._set_field_genres = function (d, fi) {
            this.genre_list.set_data(U.safeArray(d.genres));
            return this;
        };
        F.prototype._set_field_countries = function (d, fi) {
            this.country_list.set_data(U.safeArray(d.countries));
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="genre list delegate">
        F.prototype.inline_taglist_get_title_genres = function () {
            return "Жанры";
        };

        F.prototype.inline_taglist_get_title_countries = function () {
            return "Страна производства";
        };
        F.prototype.inline_taglist_add_countries = function () {
            this.showLoader();
            Y.load('selectors.media.country_selector')
                    .done(this, this.on_country_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
        };
        F.prototype.on_country_selector_ready = function (x) {
            x.show().load().set_allow_multi(true).setCallback(this, this.on_country_selector_done);
            return this;
        };
        F.prototype.on_country_selector_done = function (d) {
            d = U.safeArray(d);
            var ita = [];
            for (var i = 0; i < d.length; i++) {
                ita.push({id: d[i].id, text: d[i].name});
            }
            this.country_list.add_items(ita);
            this.country_list.render_items();
            return this;
        };
        F.prototype.inline_taglist_add_genres = function () {
            this.showLoader();
            Y.load('selectors.media.genre_selector')
                    .done(this, this.on_genre_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
        };

        F.prototype.on_genre_selector_ready = function (x) {
            x.show().load().set_allow_multi(true).setCallback(this, this.on_genre_selector_done);
            return this;
        };
        F.prototype.on_genre_selector_done = function (d) {
            d = U.safeArray(d);
            var ita = [];
            for (var i = 0; i < d.length; i++) {
                ita.push({id: d[i].id, text: d[i].name});
            }
            this.genre_list.add_items(ita);
            this.genre_list.render_items();
            return this;
        };
        //</editor-fold>


        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id, parent_id, pathbuilder) {
            this.clear();
            this.pathbuilder = window.Eve.PathBuilder.is(pathbuilder) ? pathbuilder : null;
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                this._parent_id = parent_id;

                jQuery.getJSON('/admin/MediaContent/API', {action: "get_trailer", id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.getField('content_id').val(parent_id);
                this._parent_id = parent_id;
                this.showLoader();
                jQuery.getJSON('/admin/MediaContent/API', {action: "language_list"})
                        .done(this.on_load_responce_lang.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };
        F.prototype.on_load_responce_lang = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_meta_data_success(d.metadata);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_meta_data_success = function (d) {
            this.language_list = U.safeArray(d.language_list);
            this._set_field_name({}, this.getField('name'));
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_meta_data_success(d.metadata);
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
            this.getField('path').val(this._parent_path);
            this.image_list.set_owner_id(U.NEString(U.IntMoreOr(this.getField('id').val(), 0, null), null));
            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">
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
                    {id: "name", key: "name", property: "name", "text": "Наименование", filter: false, sort: false}
                ]
            };
        };

        F.prototype.init_table = function () {
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplates(__DIR__,"{$this->MC}","TPLS")?>*/
            this.table = window.Eve.ADVTable.Table(this.items_def());
            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });
            this.datasource = window.Eve.ADVTable.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.datasource);
            this.table.appendTo(this.getRole('name').get(0));
        };
        //</editor-fold>


        F.prototype.init_image_list = function () {
            var cf = Y.get_loaded_component('media.image_uploader');
            this.image_list = cf();
            this.image_list.setContainer(this.getRole('gallery'));
            this.image_list.set_params('media_content_trailer', null);
            return this;
        };

        F.prototype.init_file_list = function () {
            var cf = Y.get_loaded_component('inline.inline_media_cdn_file_list');
            this.file_list = cf(MC);
            this.file_list.setContainer(this.getRole('files'));
            this.file_list.set_ci(this);
            return this;
        };

        F.prototype.file_list_get_id = function () {
            return U.IntMoreOr(this.getField('id').val(), 0, null);
        };
        F.prototype.file_list_get_path = function () {
            return this.getField('path').val();
        };

        F.prototype.file_list_get_pathbuilder = function () {
            var p = this.file_list_get_id();
            if (p) {
                if (this.pathbuilder) {
                    return this.pathbuilder.by_appending(p).set_private(false);
                }
            }
            return null;
        };

        /**
         * @deprecated 
         * @returns {indexL#1.initPlugin.F.prototype.filelist_get_uploader_params.indexAnonym$13}
         */
        F.prototype.filelist_get_uploader_params = function () {
            var p = this.file_list_get_id();
            return {
                private: false,
                path: p ? [this.file_list_get_path(), "/", p].join('') : null
            };
        };

        F.prototype._get_field_files = function () {
            return this.file_list.get_data();
        };
        F.prototype._set_field_files = function (d, fi) {
            this.file_list.set_data(U.safeArray(d.files));
            return this;
        };
        F.prototype._set_field_name = function (a, fi) {
            var values = [];
            var sa = U.safeArray(this.language_list);
            for (var i = 0; i < sa.length; i++) {
                var id = sa[i].id;
                var name = U.NEString(U.safeObject(a.name)[id], '');
                values.push({language_id: id, name: name});
            }
            this.datasource.setSource(values);
        };

        F.prototype._get_field_name = function () {
            return [].concat(this.datasource.source);
        };



        F.prototype._set_field_default_image = function (d, fi) {

        };

        F.prototype._get_field_default_image = function () {
            return this.image_list.get_default_image();
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.image_list.set_owner_id(null);
            this.pathbuilder = null;
            this.genre_list?this.genre_list.set_data([]):0;
            this.country_list?this.country_list.set_data([]):0;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>        

        //<editor-fold defaultstate="collapsed" desc="monitors">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">    
        F.prototype.onCommandSelect_tracklang = function () {
            this.showLoader();
            Y.load('selectors.media.tracklang_selector')
                    .done(this, this.on_tracklang_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_tracklang_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_tracklang_selector_done);
        };
        F.prototype.on_tracklang_selector_done = function (d) {
            this.getField('track_language').val(U.IntMoreOr(d[0].id, 0, null));
            this.getField('track_language_name').val(U.NEString(d[0].name, null));
            return this;
        };

        F.prototype.onMonitorPositive_int = function (t) {
            t.val(U.IntMoreOr(t.val(), 0, null));
            return this;
        };

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


        F.prototype.onMonitorSort = function (t) {
            t.val(U.IntOr(t.val(), 0));
            return this;
        };
        F.prototype.onMonitorValue = function (t) {
            var lang_id = U.NEString(t.data('id'), null);
            if (lang_id) {
                var item = this.get_table_item(lang_id);
                if (item) {
                    item.name = U.NEString(t.val(), null);
                    t.val(item.name);
                }
            }
            return this;
        };
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
                action: "put_trailer",
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