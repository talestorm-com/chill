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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.path_builder = null;
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
                {'command': "cancel", 'text': "Закрыть"}
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Загрузка файла на CDN";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (path_builder) {
            this.clear();
            this.path_builder = window.Eve.PathBuilder.is(path_builder) ? path_builder : null;
            if (!this.path_builder) {
                U.TError("uploader setup requires ParhBuilder object");
                this.hideclear();
            }
            this.setAccept(path_builder.accept);
            return this;
        };

        F.prototype.get_file_name = function () {
            var n = U.NEString(this.getRole('file').val(), null);
            if (n === null) {
                return U.UUID();
            }
            n = n.replace(/\\/g, '/');
            var na = n.split('/');
            return na[na.length - 1];
        };

        F.prototype.onMonitorFile = function () {
            if (U.NEString(this.getRole('file').val(), null)) {
                this.getRole('cover').addClass(MC + 'active');
                this.showLoader();
                var path = this.path_builder.by_appending(this.get_file_name()).build();
                jQuery.getJSON("/admin/CDNAPI/API", {action: "subscribe_request", path: path})
                        .done(this.on_subscribe.bindToObject(this))
                        .fail(this.on_subscribe_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));

            }
            return this;
        };

        F.prototype.request_done = function (request) {
            if (request.status === 200) {
                var jso = JSON.parse(request.response);
                if (jso.code === 200) {
                    var idd = U.NEString(U.safeObject(jso.object).id, null);
                    if (idd) {
                        jQuery.getJSON('/admin/CDNAPI/API', {action: "encoding_try_v2", id: idd});
                    }
                    this.runCallback();
                    if (this.getRole('autoclose').prop('checked')) {
                        this.hideclear();
                    } else {
                        this.cv();
                    }
                    return;
                }
            }
            this.cv();
            this.show_error("Ошибка при загрузке файла");
        };
        F.prototype.request_error = function (request) {
            this.cv();
            this.show_error("Ошибка сети");
        };

        F.prototype.request_progress = function (r, e) {
            var total = U.IntMoreOr(e.total, 0, 0);
            var loaded = U.IntMoreOr(e.loaded, 0, 0);
            var pc1 = U.IntMoreOr(total / 100, 0, 1);
            var pc_result = (loaded / pc1).toFixed(5);
            this.getRole('progress').css({"width": pc_result + "%"});
            return this;
        };

        F.prototype.run_upload = function (url, name) {
            var formdata = new FormData();
            formdata.append("name", name);
            formdata.append("file", this.getRole('file').get(0).files[0]);
            formdata.append("private", this.path_builder.get_private() ? "true" : "false");
            //var presets = "5bf3c7f2ef3db57e9877a2ae,571e1a30702b930774ff22ec";
            var presets = "5e1c6cdaef3db50e0f8efe67,5e1c6cdaef3db50e0f8efe68,5e1ba97c0e47cf2f7dfdbd18";
            //                      720p                 480p                       1080p
            //formdata.append("presets", presets);
            formdata.append("autoencoding", "false");
            formdata.append("del_original", "false");
            url = url + "&autoencoding=false";
            var request = new XMLHttpRequest();
            request.onload = this.request_done.bindToObjectWParam(this);
            request.onerror = this.request_error.bindToObjectWParam(this);
            request.onprogress = this.request_progress.bindToObjectWParam(this);
            request.upload.onprogress = this.request_progress.bindToObjectWParam(this);
            request.open("POST", url);
            request.send(formdata);
            return this;
        };

        F.prototype.on_subscribe = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.run_upload(d.url, d.path);
                }
                if (d.status === 'error') {
                    return this.show_error(d.error_info.message);
                }
            }
            return this.show_error("invalid server response");
        };
        F.prototype.on_subscribe_fail = function () {
            return this.show_error("network error");
        };
        F.prototype.show_error = function (x) {
            this.getRole('error').html(U.NEString(x, 'Ошибка!'));
            this.getRole('error_wrap').show();
        };

        F.prototype.clear_error = function () {
            this.getRole('error').html('');
            this.getRole('error_wrap').hide();
            return this;
        };

        //</editor-fold>                        

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.cv = function () {
            this.getRole('file').val('');
            this.getRole('cover').removeClass(MC + 'active');
            this.getRole('progress').css("width", "0");
            this.clear_error();
        };
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.cv();
            this.path_builder = null;
            this.setAccept(null);
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
        //</editor-fold>       
        //</editor-fold>



        F.prototype.setAccept = function (x) {
            this.getRole('file').attr('accept', U.NEString(x, 'video/*'));
            return this;

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