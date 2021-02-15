(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise,
        Y.css('/assets/vendor/datepicker/css.css'),
        Y.js('/assets/vendor/datepicker/js.js')
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
        F.prototype.model = null;
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
            this.init_picker();
            return this;
        };

        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_content"
                        , MC + ".TAB_intro"

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
            this.editor.destroy_editor();
            this.intro_editor.destroy_editor();
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
            return "Редактирование новости";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/Ribbon/API', {action: "get", id: id})
                        .done(this.on_load_response.bindToObject(this))
                        .fail(this.on_load_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
                return this;
            } else {
                this.init_empty_model();
            }
            return this;
        };

        F.prototype.init_empty_model = function () {
            this.model = {};
            this.setFields(this.model);
            return this;
        };
        F.prototype.on_load_response = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    try {
                        return this.on_load_success(d);
                    } catch (e) {
                        return this.on_load_fail(e.message);
                    }
                }
                if (d.status === 'error') {
                    return this.on_load_fail(d.error_info.message);
                }
            }
            return this.on_load_fail("invalid server responce");
        };

        F.prototype.on_load_fail = function (x) {
            return this.on_network_fail_fatal(x);
        };


        F.prototype.on_load_success = function (d) {
            var o = U.safeObject(d.item);
            var data = EFO.Filter.Filter().applyFiltersToHash(o, this.getFilters().getSectionImport('node'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (e) {
                U.Error([MC, e.message].join(':'));

            }
            this.model = data;
            this.setFields(this.model);
            return this;
        };









        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">

        F.prototype.init_picker = function () {
            this.getField('published').datetimepicker({
                lang: "ru",
                format: "d.m.Y",
                closeOnDateSelect: true,
                timepicker: false,
                scrollMonth: false,
                scrollInput: false,
                dayOfWeekStart: 1
            });
            return this;
        };

        F.prototype.init_editor = function () {
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.editor = cf();
            this.editor.setContainer(this.getRole('info'));
            this.intro_editor = cf();
            this.intro_editor.setContainer(this.getRole('intro'));
            return this;
        };

        F.prototype.onMonitorPreview = function (t) {
            if (t.get(0).files && t.get(0).files.length) {
                var file = t.get(0).files[0];
                if (/^image/i.test(file.type)) {
                    this.model.image_removed = false;
                    this.model.image_field = t;
                    this.model.image_file = t.get(0).files[0];
                    this._set_field_image(this.model, this.getField('image'));
                }
            }
            t.parent().html(' <input type="file" data-monitor="preview" accept=".jpg,.gif,.png,.psd,.tif,.tiff"/>');
            return this;
        };

        F.prototype.onCommandImage_remove = function () {
            this.model.image_removed = true;
            this.model.image_field = null;
            this.model.image_file = null;
            this._set_field_image(this.model, this.image_removed);
            return this;
        };

        F.prototype.onCommandImage_crop = function () {
            if (!this.model.image_removed) {
                if (this.model.image_field) {
                    U.Error("Это изображение еще не было загружено на сервер.\nКроппер работает только с изображениями на стороне сервера");
                }
                if (this.model.image) {
                    this.start_cropper();
                }
            }
            return this;
        };

        F.prototype.start_cropper = function () {
            this.showLoader();
            Y.load('media.image_cropper')
                    .done(this, this.on_cropper_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
        };
        F.prototype.on_cropper_ready = function (x) {
            x.show().load(this.model.image_context, this.model.image_owner_id, this.model.image).setCallback(this, this.on_cropper_done);
        };

        F.prototype.on_cropper_done = function () {
            this._set_field_image(this.model, this.getField('image'));
        };

        F.prototype.format_date = function (x) {
            if (x && U.isObject(x) && (x instanceof Date)) {
                return [
                    U.padLeft(x.getDate(), 2, "0"),
                    U.padLeft(x.getMonth() + 1, 2, "0"),
                    x.getFullYear()
                ].join('.');
            }
            return null;
        };

        F.prototype._set_field_published = function (x, fi) {

            if (U.isObject(x)) {
                if (U.isObject(x.published) && (x.published instanceof Date)) {
                    fi.val(this.format_date(x.published));
                    return this;
                }
            }
            fi.val('');
            return this;
        };

        F.prototype._set_field_linked = function (x, fi) {
            if (U.isObject(x)) {
                var t = U.NEString(x.link_type, null);
                if (t) {
                    var i = U.NEString(x.link_id, U.NEString(x.link_uid, null));
                    if (i) {
                        fi.val([t, i].join(''));
                        return this;
                    }
                }
            }


            fi.val('');
            return null;
        };
        F.prototype._get_field_linked = function () {
            return null;
        };


        F.prototype._set_field_image = function (d, f) {
            if (d.image_file && !d.image_removed) {
                this.getRole('preview').attr('src', window.URL.createObjectURL(d.image_file));
            } else if (U.NEString(d.image, null) && !d.image_removed) {
                var nd = (new Date()).getTime();
                var url = ["/media/", d.image_context, "/", d.image_owner_id, '/', d.image, '.SW_250H_250.jpg?a=p', nd].join('');
                this.getRole('preview').attr('src', url);
            } else {
                this.getRole('preview').attr('src', '');
            }
            return this;
        };

        F.prototype._set_field_info = function (a) {
            this.editor.setText(U.NEString(a.info, ''), U.anyBool(a.html_mode, true));
            return this;
        };
        F.prototype._set_field_intro = function (a) {
            this.intro_editor.setText(U.NEString(a.intro, ''), U.anyBool(a.html_mode_c, true));
            return this;
        };
        F.prototype._set_field_html_mode = function () {
            return this;
        };
        F.prototype._set_field_html_mode_c = function () {
            return this;
        };
        F.prototype._get_field_html_mode = function () {
            return this.editor.get_check_state();
        };
        F.prototype._get_field_html_mode_c = function () {
            return this.intro_editor.get_check_state();
        };

        F.prototype._get_field_info = function () {
            return this.editor.getText();
        };
        F.prototype._get_field_intro = function () {
            return this.intro_editor.getText();
        };


        //</editor-fold>



        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            try {
                this.model.image_field.remove();
            } catch (e) {

            }
            this.model = {};
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.editor.setText('', true);
            this.intro_editor.setText('', true);
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tabs">

        F.prototype.onTabSelectedContent = function () {
            this.editor.refresh();
            return this;
        };
        F.prototype.onTabSelectedIntro = function () {
            this.intro_editor.refresh();
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
        F.prototype.save = function (keep_open, _skip_warn) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            raw_data.image_removed = this.image_removed;
            raw_data.image_field = this.model.image_field;
            //raw_data.image_file = this.model.image_file;
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (e) {
                U.Error([MC, e.message].join(':'));
            }
            data.published = data.published ? this.format_date(data.published) : null;
            this.showLoader();
            data.action = 'post';
            EFO.IFrameTransport('/admin/Ribbon/API', data)
                    .done(this, this.on_save_response)
                    .fail(this, this.on_save_fail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_save_response = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    try {
                        this.on_load_success(d);
                        this.runCallback();
                        if (!this._keep_open) {
                            this.hide().clear();
                        }
                        return this;
                    } catch (e) {
                        return this.on_post_fail(e.message);
                    }
                }
                if (d.status === 'error') {
                    return this.on_post_fal(d.error_info.message);
                }
            }
            return this.on_save_fail("invalid server response");
        };

        F.prototype.on_save_fail = function (x) {
            return this.on_network_fail(x);
        };

        F.prototype.on_network_fail = function (x) {
            if (U.isObject(x) && (x instanceof Error)) {
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