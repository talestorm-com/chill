(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
        Y.js('/assets/js/PathBuilder/PathBuilder.js')
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable', ];
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
            return this;
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
            return "Редактирование преплея";
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
                jQuery.getJSON('/admin/Preplay/API', {action: "get", id: id})
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
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
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
        //</editor-fold>

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
            var post_data = {
                action: "put",
                data: JSON.stringify(data)
            };
            this.showLoader();
            jQuery.post('/admin/Preplay/API', post_data, null, 'json')
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


        F.prototype.onCommandRun_file_selector = function () {
            if (!U.IntMoreOr(this.getField('id').val(), 0, null)) {
                U.TError("save first");
                return this;
            }
            this.showLoader();
            Y.load('cdn.FileManager').done(this, this.file_manager_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.file_manager_ready = function (x) {
            var pathbuilder = window.Eve.PathBuilder('preplay_videos', U.IntMoreOr(this.getField('id').val(), 0, null));
            pathbuilder.set_private(false);
            x.show().load(pathbuilder).setCallback(this, this.file_select_done);
        };

        F.prototype.file_select_done = function (e) {
            var file_id = U.NEString(e, null);
            this.getField('cdn_id').val(file_id);
            if (file_id) {
                return this.recover_file_url(file_id);
            }
            return this;
        };

        F.prototype.recover_file_url = function (file_id) {
            this.showLoader();
            jQuery.getJSON('/admin/CDNAPI/API', {action: "info", id: file_id})
                    .done(this.on_url_ready.bindToObject(this))
                    .fail(this.on_url_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_url_fail = function (x) {
            this.getField('cdn_url').val('');
            this.getField('cdn_id').val('');
            U.TError(U.NEString(x, 'network error'));
            return this;
        };

        F.prototype.on_url_ready = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_url_success(d);
                }
                if (d.status === "error") {
                    return this.on_url_fail(d.error_info.message);
                }
            }
            return this.on_url_fail("invalid server response");
        };

        F.prototype.on_url_success = function (d) {
            var url = U.NEString(U.safeObject(U.safeObject(U.safeObject(d).cdnapi).info).cdn_url, null);
            if(url){
                this.getField('cdn_url').val(url);
            }else{
                this.getField('cdn_url').val('');
                this.getField('cdn_id').val('');
            }            
        };


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