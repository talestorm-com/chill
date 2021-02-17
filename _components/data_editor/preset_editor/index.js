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
            this.init_editor();
            return this;
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
                    this.model.field = t;
                    this._set_field_image(this.model, this.getField('image'));
                }
            }
            t.parent().html('<input type="file" data-monitor="preview" accept=".jpg,.gif,.png,.psd,.tif,.tiff"/>');
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
            x.show().load("preset_item", [this.model.id, this.model.uid].join('_'), this.model.image).setCallback(this, this.on_cropper_done);
        };

        F.prototype.on_cropper_done = function () {
            this._set_field_image(this.model, this.getField('image'));
        };


        F.prototype._set_field_image = function (d, f) {
            if (d.file && !d.image_removed) {
                this.getRole('preview').attr('src', window.URL.createObjectURL(d.file));
            } else if (U.NEString(d.image, null) && !d.image_removed) {
                var nd = (new Date()).getTime();
                var url = ["/media/preset_item/", d.id, '_', d.uid, '/', d.image, '.SW_250H_250.jpg?a=p', nd].join('');
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
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.model = {};
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.editor.setText('', true);

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
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            raw_data.file = this.model.file;
            raw_data.image = this.model.image;
            raw_data.image_removed = this.model.image_removed;
            raw_data.field=this.model.field;
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('preset'));
            try {
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            } catch (e) {
                U.Error([MC, e.message].join(':'));
            }
            debugger;
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