(function () {

    var H = null,
            MC = '<?=$this->MC?>',
            MD = '<?=$this->MD?>',
            FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise,
        Y.load('inline.inline_tag_list').promise,
        Y.load('inline.inline_media_personal_list').promise,
        Y.load('inline.iniline_media_trailer_list').promise,
        Y.load('inline.inline_media_season_list').promise,
        Y.js('/assets/js/PathBuilder/PathBuilder.js'),
        Y.js('/assets/vendor/datepicker/js.js'),
        Y.css('/assets/vendor/datepicker/css.css')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO,
                U = EFO.U,
                PAR = EFO.windowController,
                PARP = PAR.prototype,
                APS = Array.prototype.slice;
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
            this.init_trailer_list();
            this.init_season_list();
            this.init_picker();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common", MC + ".TAB_intro", MC + ".TAB_content", MC + ".TAB_meta",
                MC + ".TAB_gallery", MC + ".TAB_frames", MC + ".TAB_props", MC + ".TAB_pers", MC + ".TAB_trailers", MC + ".TAB_seasons"


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
            return "Редактирование сериала";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id) {
            this.clear();
            $(".groo").remove();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/MediaContent/API', {action: "get", id: id, content_type: 'ctSEASON'})
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
            this.frame_list.set_owner_id(U.NEString(U.IntMoreOr(this.getField('id').val(), 0, null), null));
            var IDa = $('*[data-field="id"]').val();
            console.log(IDa);
            if (IDa != '') {
                $(".groo").remove();
                $(".EFOFooter.Data_editorMediacontentType_editorSeason_editorFooter")
                        .append('<a class="groo" href="https://chillvision.ru/Soap/' + IDa + '" target="_blank"><span class="EFOFooterButton Data_editorMediacontentType_editorSeason_editorFooterButton">Предпросмотр</span></a>');
            }
            ;
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
            this.image_list.set_params('media_content_poster', null);
            this.frame_list = cf();
            this.frame_list.setContainer(this.getRole('frames'));
            this.frame_list.set_params('media_content_frame', null);

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

        F.prototype.init_season_list = function () {
            var cf = Y.get_loaded_component('inline.inline_media_season_list');
            this.season_list = cf(MC);
            this.season_list.setContainer(this.getRole('seasons'));
            this.season_list.set_ci(this);
            return this;
        };
        F.prototype.season_list_get_id = function () {
            return U.IntMoreOr(this.getField('id').val(), 0, null);
        };
        F.prototype.season_list_get_path = function () {
            return ['/SOAP/', this.trailer_list_get_id()].join('');
        };
        F.prototype.season_list_get_pathbuilder = function () {
            var p = this.season_list_get_id();
            if (p) {
                return window.Eve.PathBuilder('SOAP', p).set_private(true);
            }
            return null;
        };
        F.prototype._set_field_seasons = function (cf, fi) {
            this.season_list.set_data(U.safeArray(cf.seasons));
            return this;
        };
        F.prototype._get_field_seasons = function () {
            return null;
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
            return ['/SOAP/', this.trailer_list_get_id(), '/trailers'].join('');
        };

        F.prototype.trailer_list_get_pathbuilder = function () {
            var p = this.trailer_list_get_id();
            if (p) {
                return window.Eve.PathBuilder('SOAP', p).set_private(false);
            }
            return null;
        };

        F.prototype._set_field_trailers = function (cf, fi) {
            this.trailer_list.set_data(U.safeArray(cf.trailers));
            return this;
        };
        F.prototype._get_field_trailers = function () {
            return null;
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
            //teags
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
            return "ctSEASON";
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

        //<editor-fold defaultstate="collapsed" desc="add tag">
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


        F.prototype._get_field_default_poster = function () {
            return this.image_list.get_default_image();
        };



        F.prototype.init_picker = function () {
            if (false) {
                this.getField('released').datetimepicker({
                    lang: 'ru',
                    lazyInit: true,
                    format: 'd.m.Y',
                    closeOnDateSelect: false,
                    closeOnTimeSelect: true,
                    closeOnWithoutClick: false,
                    timepicker: false,
                    theme: 'dark',
                    maxDate: 0,
                    todayButton: false,
                    scrollMonth: false,
                    scrollTime: false,
                    scrollInput: false,
                    dayOfWeekStart: 1
                });
            }
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
            this.frame_list.set_owner_id(null);
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

        F.prototype._set_field_released = function (d, fi) {
            var v = U.NEString(U.safeObject(d).released, null);
            if (v) {
                var m = /^\d{1,2}\.\d{1,2}\.(\d{4})/i.exec(v);
                if (m) {
                    fi.val(m[1]);
                    return;
                }
            }
            fi.val('');
        };

        F.prototype._get_field_released = function () {
            var c = U.IntMoreOr(this.getField('released').val(), 0, null);
            if (c) {
                return ['01.01.', U.padLeft(c, 4, '0')].join('');
            }
            return null;
        };

        F.prototype.onMonitorReleased = function (t) {
            var i = U.IntMoreOr(t.val(), 0, null);
            if (i) {
                t.val(U.padLeft(i, 4, '0'));
            } else {
                t.val('');
            }
            return this;
        };

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


        F.prototype.onCommandSelect_preplay = function () {
            this.showLoader();
            Y.load('selectors.preplay_selector')
                    .done(this, this.on_preplay_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_preplay_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_preplay_selector_done);
        };
        F.prototype.on_preplay_selector_done = function (d) {
            this.getField('preplay').val(U.IntMoreOr(d[0].id, 0, null));
            this.getField('preplay_name').val(U.NEString(d[0].name, null));
            return this;
        };



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

        F.prototype.onMonitorMcsort = function (t) {
            t.val(U.IntOr(t.val(), 0));
            return this;
        };

        F.prototype.onMonitorLent_mode = function (t) {
            this.getRole('lent_param_video').hide();
            this.getRole('lent_param_gif').hide();
            if (t.val() === 'video') {
                this.getRole('lent_param_video').show();
            } else if (t.val() === 'gif') {
                this.getRole('lent_param_gif').show();
            }
            return this;
        };

        F.prototype._set_field_lent_mode = function (d, fi) {
            var mode = U.NEString(d.lent_mode, 'poster');
            fi.val(mode);
            fi.change();
            return this;
        };

        F.prototype.onMonitorPoster_image = function (t) {
            var reader = new FileReader();
            reader.onloadend = this.poser_image_load_end.bindToObject(this);
            reader.readAsDataURL(t.get(0).files[0]);
            return this;
        };

        F.prototype.onMonitorGif_image = function (t) {
            var reader = new FileReader();
            reader.onloadend = this.gif_image_load_end.bindToObject(this);
            reader.readAsDataURL(t.get(0).files[0]);
            return this;
        };
        F.prototype.gif_image_load_end = function (e) {
            this.getRole('gif_upload').get(0).src = e.target.result;
            return this;
        };


        F.prototype.poser_image_load_end = function (e) {
            this.getRole('poster_image').get(0).src = e.target.result;
            return this;
        };

        F.prototype.onCommandSelect_lent_video = function () {
            if (!U.IntMoreOr(this.getField('id').val(), 0, null)) {
                U.TError('save first!');
                return this;
            }
            this.showLoader();
            Y.load('cdn.FileManager').done(this, this.on_lent_fm_ready).fail(this, this.onRequiredComponentFail).always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_lent_fm_ready = function (x) {
            var pathbuilder = window.Eve.PathBuilder('SOAP', this.getField('id').val(), 'lent');
            pathbuilder.set_private(false);
            x.show().load(pathbuilder).setCallback(this, this.lent_video_select_done);
            return this;
        };
        F.prototype.lent_video_select_done = function (e) {
            if (U.NEString(e, null)) {
                this.getField('video_cdn_id').val(e);
                this.showLoader();
                jQuery.getJSON('/admin/CDNAPI/API', {action: "info", id: e})
                        .done(this.on_video_url_ready.bindToObject(this))
                        .fail(this.on_video_url_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.getField('video_cdn_id').val('');
                this.getField('video_cdn_url').val('');
            }
            return this;
        };
        F.prototype.on_video_url_fail = function (x) {
            this.getField('video_cdn_url').val('');
            this.getField('video_cdn_id').val('');
            U.TError(U.NEString(x, 'network error'));
            return this;
        };

        F.prototype.on_video_url_ready = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_video_url_success(d);
                }
                if (d.status === "error") {
                    return this.on_url_fail(d.error_info.message);
                }
            }
            return this.on_url_fail("invalid server response");
        };

        F.prototype.on_video_url_success = function (d) {
            var url = U.NEString(U.safeObject(U.safeObject(U.safeObject(d).cdnapi).info).cdn_url, null);
            if (url) {
                this.getField('video_cdn_url').val(url);
            } else {
                this.getField('video_cdn_url').val('');
                this.getField('video_cdn_id').val('');
            }
        };

        F.prototype._set_field_poster_image = function (d, fi) {
            fi.val('');
            if (U.NEString(d.lent_image_name, null) && U.IntMoreOr(d.id, 0, null)) {
                this.getRole('poster_image').get(0).src = ['/media/lent_poster/', d.id, '/', d.lent_image_name, '.SW_250H_250.jpg?apfs=', (new Date()).getTime()].join('');
            } else {
                this.getRole('poster_image').get(0).src = '';
            }
            return this;
        };
        F.prototype._get_field_poster_image = function () {
            return null;
        };

        F.prototype._set_field_gif_cdn_id = function (d, fi) {
            var cdn_id = U.NEString(d.gif_cdn_id, null);
            var cdn_url = U.NEString(d.gif_cdn_url, null);
            this.getField('gif_cdn_id').val(cdn_id);
            this.getField('gif_cdn_url').val(cdn_url);
            if (cdn_id && cdn_url) {
                this.getRole('gif_upload').get(0).src = '//' + cdn_url;
            } else {
                this.getRole('gif_upload').get(0).src = '';
            }
        };
        F.prototype._get_field_gif_cdn_id = function () {
            return null;
        };
        F.prototype._set_field_gif_cdn_url = function (d, fi) {
            return this;
        };
        F.prototype._get_field_gif_cdn_url = function () {
            return null;
        };
        F.prototype._get_field_gif_image = function () {
            return null;
        };
        F.prototype._set_field_gif_image = function (d, fi) {
            fi.val('');
            return this;
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
            raw_data.common_name = raw_data.name;
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            var post_data = new FormData();
            post_data.append('action', "put");
            post_data.append('data', JSON.stringify(data));
            if (this.getField('poster_image').get(0).files.length) {
                post_data.append('poster_image', this.getField('poster_image').get(0).files[0]);
            }
            if (this.getField('gif_image').get(0).files.length) {
                post_data.append('gif_image', this.getField('gif_image').get(0).files[0]);
            }
//            var post_data = {
//                action: "put",
//                data: JSON.stringify(data)
//            };
            this.showLoader();
            var request = new XMLHttpRequest();
            request.onload = this.fd_request_done.bindToObjectWParam(this);
            request.onerror = this.fd_request_error.bindToObjectWParam(this);
            request.open("POST", '/admin/MediaContent/API');
            this.showLoader();
            request.send(post_data);
//            jQuery.post('/admin/MediaContent/API', post_data, null, 'json')
//                    .done(this.on_post_responce.bindToObject(this))
//                    .fail(this.on_network_fail.bindToObject(this))
//                    .always(this.hideLoader.bindToObject(this));
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