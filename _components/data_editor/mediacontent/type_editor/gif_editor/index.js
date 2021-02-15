(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
        Y.load('media.image_uploader').promise,
        Y.load('inline.inline_tag_list').promise,
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
            this.init_taglist();
            this.init_table();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_meta"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_props"
                        , MC + ".TAB_tags"

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
            return "Редактирование GIF-контента";
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
                jQuery.getJSON('/admin/MediaContent/API', {action: "get", id: id, content_type: 'ctGIF'})
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
        //<editor-fold defaultstate="collapsed" desc="taglist">
        F.prototype.init_taglist = function () {
            var cf = Y.get_loaded_component('inline.inline_tag_list');
            this.tag_list = cf(this, "tags");
            this.tag_list.setContainer(this.getRole('tags'));
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
        F.prototype._get_field_tags = function () {
            return this.tag_list.get_data();
        };
        F.prototype._set_field_tags = function (d, fi) {
            this.tag_list.set_data(U.safeArray(d.tags));
            return this;
        };
        F.prototype.inline_taglist_get_title_tags = function () {
            return "Теги";
        };
        //<editor-fold defaultstate="collapsed" desc="add tags">
        F.prototype.inline_taglist_add_tags = function () {
            this.showLoader();
            Y.load('selectors.media.tag_selector')
                    .done(this, this.on_tag_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
        };
        F.prototype.on_tag_selector_ready = function (x) {
            x.show().load().set_allow_multi(true).setCallback(this, this.on_tag_selector_done);
            return this;
        };
        F.prototype.on_tag_selector_done = function (d) {
            d = U.safeArray(d);
            var ita = [];
            for (var i = 0; i < d.length; i++) {
                ita.push({id: d[i].id, text: d[i].name});
            }
            this.tag_list.add_items(ita);
            this.tag_list.render_items();
            return this;
        };
        //</editor-fold>
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
                    {id: "name", key: "name", property: "name", "text": "Текст", filter: false, sort: false}
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
        F.prototype._set_field_strings = function (a, fi) {
            var values = [];
            var sa = U.safeArray(this.language_list);
            for (var i = 0; i < sa.length; i++) {
                var id = sa[i].id;
                var text = U.NEString(U.safeObject(U.safeObject(a.strings)[id]).text, '');
                values.push({language_id: id, text: text});
            }
            this.datasource.setSource(values);
        };

        F.prototype._get_field_strings = function () {
            return [].concat(this.datasource.source);
        };
        F.prototype.onMonitorLanguage = function (t) {
            var lang_id = U.NEString(t.data('id'), null);
            if (lang_id) {
                var item = this.get_table_item(lang_id);
                if (item) {
                    item.text = U.NEString(t.val(), null);
                    t.val(item.text);
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
        //</editor-fold>



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
            return "ctGIF";
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

        F.prototype._set_field_price = function (d, fi) {
            this.getField('price').val(EFO.Checks.formatPriceNSD(U.FloatMoreEqOr(d.price, 0, 0), 2));
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="file ops">
        F.prototype._set_field_file = function () {
            this.getField('file').val('');
            this.getField('file').change();
            return this;
        };

        F.prototype._get_field_file = function () {
            return '';
        };
        F.prototype.onMonitorFile = function () {
            if (U.NEString(this.getField('file').val(), null)) {
                this.getRole('file_display').html(this.getField('file').get(0).files[0].name);
            } else {
                this.getRole('file_display').html("Нажать или перетащить");
            }
            return this;
        };
        //</editor-fold>


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.image_list.set_owner_id(null);
            this.datasource ? this.datasource.setSource([]) : 0;
            this.tag_list?this.tag_list.set_data([]):0;
            this.genre_list?this.genre_list.set_data([]):0;
            this.country_list?this.country_list.set_data([]):0;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tabs">

        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="monitors">

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



        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        F.prototype.post_formdata = function (clear_data) {
            if (!window.FormData) {
                U.Error('Требуется браузер с поддержкой FormData');
            }
            var fd = new FormData();
            fd.append("data", JSON.stringify(clear_data));
            fd.append('action', "put");
            fd.append('gif_file', this.getField('file').get(0).files[0]);
            var request = new XMLHttpRequest();
            request.onload = this.fd_request_done.bindToObjectWParam(this);
            request.onerror = this.fd_request_error.bindToObjectWParam(this);
            request.open("POST", '/admin/MediaContent/API');
            this.showLoader();
            request.send(fd);
            return this;
        };

        F.prototype.fd_request_done = function (rq) {
            this.hideLoader();
            if (rq.status === 200) {
                var rt = null;
                try {
                    rt = JSON.parse(rq.responseText);
                } catch (e) {
                    rt = null;
                }
                if (rt) {
                    return this.on_post_responce(rt);
                }
                return this.on_network_fail("invalid server response");
            }
            return this.on_network_fail("network error");
        };

        F.prototype.fd_request_error = function (rq) {
            return this.on_network_fail("network error").hideLoader();
        };


        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            //form dta!
            if (U.NEString(this.getField('file').val(), null)) {
                return this.post_formdata(data);
            }
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