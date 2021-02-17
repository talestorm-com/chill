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
        Y.load('inline.iniline_media_trailer_list').promise,
        Y.load('inline.inline_media_series_list').promise,
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
            this.init_editor();
            this.init_image_list();
            this.init_trailer_list();
            this.init_series_list();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_intro"
                        , MC + ".TAB_content"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_trailers"
                        , MC + ".TAB_series"

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
            return "Редактирование сезона";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id, season_id, pathbuilder) {
            this.clear();
            this.pathbuilder = window.Eve.PathBuilder.is(pathbuilder) ? pathbuilder : null;
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/MediaContent/API', {action: "get", id: id, content_type: 'ctSEASONSEASON'})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.getField('season_id').val(season_id);
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
            return this;
        };



        F.prototype.init_series_list = function () {
            var cf = Y.get_loaded_component('inline.inline_media_series_list');
            this.series_list = cf(MC);
            this.series_list.setContainer(this.getRole('series'));
            this.series_list.set_ci(this);
            return this;
        };
        F.prototype.series_list_get_id = function () {
            return U.IntMoreOr(this.getField('id').val(), 0, null);
        };
        F.prototype.series_list_get_path = function () {
            return ['/SEASON/', this.getField('season_id').val(), '/SEASON_', this.series_list_get_id()].join('');
        };
        F.prototype.series_list_get_pathbuilder = function () {
            var p = this.trailer_list_get_id();
            if (p) {
                if (this.pathbuilder) {
                    return this.pathbuilder.by_appending(["SEASON_", p].join('')).set_private(true);
                }
            }
            return null;
        };
        F.prototype._set_field_series = function (cf, fi) {
            this.series_list.set_data(U.safeArray(cf.seasons));
            return this;
        };
        F.prototype._get_field_series = function () {
            return null;
        };
        
        F.prototype._get_field_common_name = function(){
            var c = U.NEString(this.getField('common_name').val(),null);
            if(!c){
                c = ['Season',this.getField('num').val()].join(' ');
            }
            return c;
        };
        F.prototype._get_field_name = function(){
            var c = U.NEString(this.getField('name').val(),null);
            if(!c){
                c = ['Сезон',this.getField('num').val()].join(' ');
            }
            return c;
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
            return [this.series_list_get_path(), '/trailers'].join('');
        };
        F.prototype.trailer_list_get_pathbuilder = function () {
            var p = this.trailer_list_get_id();
            if (p) {
                if (this.pathbuilder) {
                    return this.pathbuilder.by_appending(["SEASON_", p].join('')).set_private(false);
                }
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


        F.prototype._get_field_content_type = function () {
            return "ctSEASONSEASON";
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

        F.prototype._set_field_default_poster = function (d, fi) {

        };


        F.prototype._get_field_default_poster = function () {
            return this.image_list.get_default_image();
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.editor.setText('', true);
            this.intro_editor.setText('', true);
            this.image_list.set_owner_id(null);
            this.pathbuilder = null;
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