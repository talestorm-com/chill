(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise
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
            this.get_temporary_video_display = this._get_temporary_video_display.bindToObject(this);
            this.get_video_link = this._get_video_link.bindToObject(this);
            this.init_editor();
            return this;
        };

        F.prototype._get_temporary_video_display = function () {
            if (this.model.video_file && U.isObject(this.model.video_file) && (this.model.video_file instanceof File)) {
                return window.URL.createObjectURL(this.model.video_file);
            }
            return '';
        };

        F.prototype._get_video_link = function () {
            var id = U.IntMoreOr(this.model.id, 0, null);
            var uid = U.NEString(this.model.uid, null);
            var file = U.NEString(this.model.video, null);
            if (id && uid && file) {
                var t = (new Date()).getTime();
                return ["/media/protected/tutorial/", id, "/", file, '?afp=z', t].join('');
            }
            return '';
        };

        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_content"

            ]);
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.editor.init_editor();
            this.placeAtCenter();
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
                {'command': "cancel", 'text': "Отмена"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Редактирование пресета";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (data) {
            this.clear();
            try {
                if (U.isObject(data)) {
                    var cdata = EFO.Filter.Filter().applyFiltersToHash(data, this.getFilters().getSectionImport('preset'));
                    EFO.Filter.Filter().throwValuesErrorFirst(cdata, true);
                    this.model = cdata;
                    this.model.file = data.file;
                    this.setFields(this.model);
                    if(this.model.video==="pending"){
                        this.on_pending_timeout();
                    }
                    return this;
                }
                U.Error('ee');
            } catch (ee) {
                this.init_empty_model();
            }
            return this;
        };

        F.prototype.init_empty_model = function () {
            this.model = {uid: U.UUID()};
            this.setFields(this.model);
            return this;
        };



        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">
        F.prototype.init_editor = function () {
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.editor = cf();
            this.editor.setContainer(this.getRole('info'));
            return this;
        };

        F.prototype.onMonitorSort = function (t) {
            t.val(U.IntOr(t.val(), 0));
            return this;
        };

        F.prototype.onMonitorPreview = function (t) {
            if (t.get(0).files && t.get(0).files.length) {
                var file = t.get(0).files[0];
                if (/^image/i.test(file.type)) {
                    this.model.file = file;
                    this.model.image_removed = false;
                    this.model.image_field = t;
                    this._set_field_image(this.model, this.getField('image'));
                }
            }
            t.parent().html(' <input type="file" data-monitor="preview" accept=".jpg,.gif,.png,.psd,.tif,.tiff"/>');
            return this;
        };

        F.prototype.onMonitorVideo_file = function (t) {
            try {
                if (t.get(0).files && t.get(0).files.length) {
                    if (t.get(0).files.length === 1) {
                        if (/^video/i.test(t.get(0).files[0].type)) {
                            this.model.video_removed = false;
                            this.model.video_file = t.get(0).files[0];
                            this.model.video_field = t;
                            this._set_field_video(this.model, this.getField('video'));
                        } else {
                            U.Error("Неизвестный тип видео");
                        }
                    } else {
                        U.Error('Требуется 1 видеофайл');
                    }
                }
            } catch (e) {
                U.TError(e);
            }
            t.parent().html('<input type="file"  data-monitor="video_file" accept=".mov, .webm, .mpg, .mp4, .avi, .mpeg, .mkv" />');
            return this;
        };


        F.prototype.onMonitorPreset_file = function (t) {
            if (t.get(0).files && t.get(0).files.length) {
                if (t.get(0).files.length === 1) {
                    var reader = new FileReader();
                    reader.onloadend = this.preset_ready.bindToObject(this);
                    this.showLoader();
                    reader.readAsText(t.get(0).files[0]);
                } else {
                    U.Error("Требуется 1 файл пресета");
                }
            }
            t.val('');
            return this;
        };
        F.prototype.preset_ready = function (a) {
            this.hideLoader();
            var str = a.target.result;
            var ss = str;
            var p = null;
            if (/^\s{0,}s\s{0,}=\s{0,1}{/i.test(str)) {
                this.getField('preset').val(ss);
                return this;
            }
            U.Error("Это не файл пресета");
        };

        F.prototype.onCommandImage_remove = function () {
            this.model.image_removed = true;
            this.model.file = null;
            this._set_field_image(this.model, this.image_removed);
            return this;
        };

        F.prototype.onCommandImage_crop = function () {
            if (!this.model.image_removed) {
                if (this.model.file) {
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
            x.show().load("video_item", [this.model.id, this.model.uid].join('_'), this.model.image).setCallback(this, this.on_cropper_done);
        };

        F.prototype.on_cropper_done = function () {
            this._set_field_image(this.model, this.getField('image'));
        };


        F.prototype._set_field_image = function (d, f) {
            if (d.file && !d.image_removed) {
                this.getRole('preview').attr('src', window.URL.createObjectURL(d.file));
            } else if (U.NEString(d.image, null) && !d.image_removed) {
                var nd = (new Date()).getTime();
                var url = ["/media/video_item/", d.id, '_', d.uid, '/', d.image, '.SW_250H_250.jpg?a=p', nd].join('');
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
        F.prototype._set_field_html_mode = function () {
            return this;
        };
        F.prototype._get_field_html_mode = function () {
            return this.editor.get_check_state();
        };

        F.prototype._get_field_info = function () {
            return this.editor.getText();
        };
        
        F.prototype._get_field_video = function(){
            return this.model.video;
        };

        F.prototype._set_field_video = function (d, f) {          
            this.getRole('disabler').hide();
            this.stop_pending_monitor();
            if (d.video_file && !d.video_removed) {
                f.html(Mustache.render(EFO.TemplateManager().get('video_file', MC), this));
            } else if (!d.video_file && !d.video_removed && U.NEString(d.video, null)) {
                if (d.video === 'pending') {
                    f.html(Mustache.render(EFO.TemplateManager().get('video_pending', MC), this));
                    this.getRole('disabler').show();
                    this.start_pending_monitor();
                } else if (d.video === 'error') {
                    f.html(Mustache.render(EFO.TemplateManager().get('video_error', MC), this));
                } else {
                    f.html(Mustache.render(EFO.TemplateManager().get('video_link', MC), this));
                }
            } else {
                f.html(Mustache.render(EFO.TemplateManager().get('video_uploader', MC), this));
            }
            return this;
        };
        //</editor-fold>

        F.prototype.start_pending_monitor = function () {
            this.stop_pending_monitor();
            this.pending_to = window.setTimeout(this.on_pending_timeout.bindToObject(this), 10000);
            return this;
        };

        F.prototype.on_pending_timeout = function () {
            this.stop_pending_monitor();
            jQuery.getJSON('/admin/Video/API', {action: "check_pending_state", id: this.model.id, uid: this.model.uid})
                    .done(this.on_pending_response.bindToObject(this))
                    .fail(this.on_pending_fail.bindToObject(this));
            return this;
        };
        F.prototype.on_pending_response = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    if (this.model.id === d.result.id && this.model.uid === d.result.uid) {
                        this.model.video = d.result.video;
                        this._set_field_video(this.model, this.getField('video'));
                        this.LEM.run('VID_LRR',{id:this.model.id,uid:this.model.uid,video:this.model.video});
                        return this;
                    }

                }
            }
            return this.on_pending_fail();
        };
        F.prototype.on_pending_fail = function () {
            this.start_pending_monitor();
            return this;
        };

        F.prototype.stop_pending_monitor = function () {
            if (this.pending_to) {
                window.clearTimeout(this.pending_to);
                this.pending_to = false;
            }
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.model = {};
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.editor.setText('', true);
            this.stop_pending_monitor();

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
            _skip_warn = U.anyBool(_skip_warn, false);
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            raw_data.file = this.model.file;
            raw_data.image = this.model.image;
            raw_data.image_removed = this.model.image_removed;
            raw_data.video_file = this.model.video_file;
            raw_data.image_field = this.model.image_field;
            raw_data.video_field = this.model.video_field;
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('preset'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (e) {
                U.Error([MC, e.message].join(':'));
            }
            if (data.video_file && U.anyBool(data.convert, false) === true && !_skip_warn) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text("Конвертация видео занимает много времени и вычислительных ресурсов сервера<br>Рекомендуется загружать заранее подготовленные файлы")
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.save(this._keep_open, true);
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Отмена", "Продолжить"])
                        .show();
                return this;
            }
            this.runCallback(data);
            return this.hide().clear();
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