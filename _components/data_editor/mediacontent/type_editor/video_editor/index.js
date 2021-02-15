(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise,
        Y.load('inline.inline_tag_list').promise,
        Y.load('inline.inline_media_personal_list').promise,
        Y.load('inline.inline_media_cdn_file_list').promise,
        Y.load('inline.iniline_media_trailer_list').promise,
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
            this.init_taglist();
            this.init_personal_list();
            this.init_file_list();
            this.init_trailer_list();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_intro"
                        , MC + ".TAB_content"
                        , MC + ".TAB_meta"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_posters"
                        , MC + ".TAB_props"
                        , MC + ".TAB_pers"
                        , MC + ".TAB_files"
                        , MC + ".TAB_trailers"

            ]);
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.editor.init_editor();
            this.intro_editor.init_editor();
            this.placeAtCenter();
            return this;
        };
        F.prototype.onAfterHide = function () {
            this.intro_editor.destroy_editor();
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
                {'command': "cancel", 'text': "Отмена"},
                {'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Редактирование контента";
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
                jQuery.getJSON('/admin/MediaContent/API', {action: "get", id: id, content_type: 'ctVIDEO'})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
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
            this.poster_list.set_owner_id(U.NEString(U.IntMoreOr(this.getField('id').val(), 0, null), null));
            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">
        F.prototype.init_editor = function () {
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.editor = cf();
            this.editor.setContainer(this.getRole('content'));
            this.intro_editor = cf();
            this.intro_editor.setContainer(this.getRole('intro'));
            return this;
        };
        F.prototype.init_image_list = function () {
            var cf = Y.get_loaded_component('media.image_uploader');
            this.image_list = cf();
            this.image_list.setContainer(this.getRole('gallery'));
            this.image_list.set_params('media_content_frame', null);
            this.poster_list = cf();
            this.poster_list.setContainer(this.getRole('posters'));
            this.poster_list.set_params('media_content_poster', null);
            return this;
        };

        F.prototype.init_property_editor = function () {
            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };

        F.prototype.init_personal_list = function () {
            var cf = Y.get_loaded_component('inline.inline_media_personal_list');
            this.personal_list = cf(MC);
            this.personal_list.setContainer(this.getRole('personal'));
            return this;
        };

        F.prototype.init_trailer_list = function () {
            var cf = Y.get_loaded_component('inline.iniline_media_trailer_list');
            this.trailer_list = cf(MC);
            this.trailer_list.setContainer(this.getRole('trailers'));
            this.trailer_list.set_ci(this);
            return this;
        };
        F.prototype.trailer_list_get_id = function () {
            return U.IntMoreOr(this.getField('id').val(), 0, null);
        };
        F.prototype.trailer_list_get_path = function () {
            return ['/VIDEO/', this.trailer_list_get_id(), '/trailers'].join('');
        };
        F.prototype.trailer_list_get_pathbuilder = function () {
            return this.file_list_get_pathbuilder();
        };
        F.prototype._set_field_trailers = function (cf, fi) {
            this.trailer_list.set_data(U.safeArray(cf.trailers));
            return this;
        };
        F.prototype._get_field_trailers = function () {
            return null;
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
            return 'VIDEO';
        };

        F.prototype.file_list_get_pathbuilder = function () {
            var p = this.file_list_get_id();
            if (p) {
                return window.Eve.PathBuilder('VIDEO', p).set_private(true);
            }
            return this;
        };

        F.prototype.filelist_get_uploader_params = function () {
            var p = this.file_list_get_id();
            return {
                private: true,
                path: p ? ['/VIDEO/', p].join('') : null
            };
        };

        F.prototype.init_taglist = function () {
            var cf = Y.get_loaded_component('inline.inline_tag_list');
            this.genre_list = cf(this, "genres");
            this.genre_list.setContainer(this.getRole('genres'));
            this.country_list = cf(this, "countries");
            this.country_list.setContainer(this.getRole('countries'));
            this.studio_list = cf(this, "studios");
            this.studio_list.setContainer(this.getRole('studios'));
            this.tag_list = cf(this, "tags");
            this.tag_list.setContainer(this.getRole('tags'));
            return this;
        };

        F.prototype._get_field_files = function () {
            return this.file_list.get_data();
        };
        F.prototype._set_field_files = function (d, fi) {
            this.file_list.set_data(U.safeArray(d.files));
            return this;
        };

        F.prototype._get_field_genres = function () {
            return this.genre_list.get_data();
        };
        F.prototype._get_field_tags = function () {
            return this.tag_list.get_data();
        };
        F.prototype._get_field_countries = function () {
            return this.country_list.get_data();
        };
        F.prototype._get_field_studios = function () {
            return this.studio_list.get_data();
        };
        F.prototype._get_field_content_type = function () {
            return "ctVIDEO";
        };
        F.prototype._set_field_genres = function (d, fi) {
            this.genre_list.set_data(U.safeArray(d.genres));
            return this;
        };
        F.prototype._set_field_tags = function (d, fi) {
            this.tag_list.set_data(U.safeArray(d.tags));
            return this;
        };
        F.prototype._set_field_countries = function (d, fi) {
            this.country_list.set_data(U.safeArray(d.countries));
            return this;
        };
        F.prototype._set_field_studios = function (d, fi) {
            this.studio_list.set_data(U.safeArray(d.studios));
            return this;
        };

        F.prototype.inline_taglist_get_title_genres = function () {
            return "Жанры";
        };
        F.prototype.inline_taglist_get_title_tags = function () {
            return "Теги";
        };

        F.prototype.inline_taglist_get_title_countries = function () {
            return "Страна производства";
        };
        F.prototype.inline_taglist_get_title_studios = function () {
            return "Студия-производитель";
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
        //
        F.prototype.inline_taglist_add_studios = function () {
            this.showLoader();
            Y.load('selectors.media.studio_selector')
                    .done(this, this.on_studio_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
        };

        F.prototype.on_studio_selector_ready = function (x) {
            x.show().load().set_allow_multi(true).setCallback(this, this.on_studio_selector_done);
            return this;
        };
        F.prototype.on_studio_selector_done = function (d) {
            d = U.safeArray(d);
            var ita = [];
            for (var i = 0; i < d.length; i++) {
                ita.push({id: d[i].id, text: d[i].name});
            }
            this.studio_list.add_items(ita);
            this.studio_list.render_items();
            return this;
        };


        F.prototype.onCommandSelect_emoji = function () {
            this.showLoader();
            Y.load('selectors.media.emoji_selector').done(this, this.on_emoji_selector_ready)
                    .fail(this, this.onRequiredComponentFail).always(this, this.hideLoader);
        };
        F.prototype.on_emoji_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_emoji_selected);
        };
        F.prototype.on_emoji_selected = function (x) {
            this.getField('emoji').val(U.IntMoreOr(x[0].id, null));
            this.getField('emoji_name').val(U.NEString(x[0].tag, null));
            return this;
        };

        F.prototype.onCommandClear_emoji = function () {
            this.getField('emoji').val('');
            this.getField('emoji_name').val('');
        };

        F.prototype.onCommandSelect_age_restriction = function () {
            this.showLoader();
            Y.load('selectors.media.age_selector').done(this, this.on_age_selector_ready)
                    .fail(this, this.onRequiredComponentFail).always(this, this.hideLoader);
        };
        F.prototype.on_age_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_age_selected);
        };
        F.prototype.on_age_selected = function (x) {
            this.getField('age_restriction').val(U.IntMoreOr(x[0].id, null));
            this.getField('age_restriction_name').val(U.NEString(x[0].international_name, null));
            return this;
        };

        F.prototype.onCommandClear_age_restriction = function () {
            this.getField('age_restriction').val('');
            this.getField('age_restriction_name').val('');
        };

        F.prototype._set_field_info = function (a) {
            var mode = !!(U.IntMoreOr(a.html_mode, 0, 0) & 1);
            this.editor.setText(U.NEString(a.info, ''), mode);
            return this;
        };
        F.prototype._set_field_html_mode = function () {
            return this;
        };
        F.prototype._get_field_html_mode = function () {
            return ((this.intro_editor.get_check_state() ? 1 : 0) << 1) + (this.editor.get_check_state() ? 1 : 0);
        };

        F.prototype._get_field_info = function () {
            return this.editor.getText();
        };

        F.prototype._set_field_intro = function (a) {
            var mode = !!(U.IntMoreOr(a.html_mode, 0, 0) & 2);
            this.intro_editor.setText(U.NEString(a.intro, ''), mode);
            return this;
        };


        F.prototype._get_field_intro = function () {
            return this.intro_editor.getText();
        };

        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._set_field_personal = function (x) {
            this.personal_list.set_data(U.safeArray(x.personal));
            return this;
        };

        F.prototype._get_field_personal = function () {
            return this.personal_list.get_data();
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
        };

        F.prototype._set_field_default_poster = function (d, fi) {

        };

        F.prototype._set_field_default_frame = function (d, fi) {

        };

        F.prototype._get_field_default_poster = function () {
            return this.poster_list.get_default_image();
        };
        F.prototype._get_field_default_frame = function () {
            return this.image_list.get_default_image();
        };

        F.prototype._set_field_price = function (d, fi) {
            this.getField('price').val(EFO.Checks.formatPriceNSD(U.FloatMoreEqOr(d.price, 0, 0), 2));
            return this;
        };
        F.prototype._get_field_price = function () {
            return U.FloatMoreEqOr(this.getField('price').val(), 0, null);
        };
        F.prototype.onMonitorPrice = function (t) {
            t.val(EFO.Checks.formatPriceNSD(U.FloatMoreEqOr(t.val(), 0, 0), 2));
            return this;
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.editor.setText('', true);
            this.intro_editor.setText('', true);
            this.image_list.set_owner_id(null);
            this.poster_list.set_owner_id(null);
            this.genre_list.set_data([]);
            this.studio_list.set_data([]);
            this.onCommandClear_emoji();
            this.onCommandClear_age_restriction();
            this.country_list.set_data([]);
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tabs">
        F.prototype.onTabSelectedIntro = function () {
            this.intro_editor.refresh();
            return this;
        };
        F.prototype.onTabSelectedContent = function () {
            this.editor.refresh();
            return this;
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="monitors">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        F.prototype.onCommandSeek = function () {
            this.showLoader();
            Y.load('cdn.FileManager')
                    .done(this, this.fm_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.fm_ready = function (x) {
            x.show().load().setCallback(this, this.fm_file_selected);
            return this;
        };
        F.prototype.fm_file_selected = function (x) {
            this.getField('cdn_id').val(U.NEString(x, ''));
            return this;
        };

        F.prototype.onCommandInfo = function () {
            var id = U.NEString(this.getField('cdn_id').val(), null);
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

        F.prototype.onCommandUpload = function () {
            var id = U.IntMoreOr(this.getField('id').val(), 0, null);
            id ? 0 : U.TError("Сначала нужно сохранить!");
            this.showLoader();
            jQuery.getJSON('/admin/CDNAPI/API', {action: "get_uploader", "context": "ctVIDEO", id: id})
                    .done(this.on_uploader_ready.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_uploader_ready = function (d) {
            if (U.isObject(d) && d.status === "ok") {
                var url = U.NEString(d.url, null);
                if (d.url) {
                    var w = U.IntMoreOr((screen.width - 600) / 2, 0, 0);
                    var h = U.IntMoreOr((screen.height - 600) / 2, 0, 0);
                    var r = window.open(url, 'uploader' + U.IntMoreOr(this.getField('id').val(), 0, 0), "width=600,height=600,top=" + h + ",left=" + w + ",menubar=no,toolbar=no,location=no,resizable=no,status=no");
                    if (!r) {
                        U.TError("Приложению требуется разрешение на создание всплывающих окон!");
                    }
                    return;
                }
            } else if (U.isObject(d) && d.status === 'error') {
                return this.on_network_fail(d.error_info.message);
            }
            return this.on_network_fail("invalid server response");
        }
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