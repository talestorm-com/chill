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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable'];
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
            this.is_selected = this._is_selected.bindToObjectWParam(this);
            return this;
        };

        F.prototype._is_selected = function (x) {
            return this._selected_id === x.id;
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
                {'command': "apply", 'text': "Выбрать"}
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Выбор файла с CDN";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (path_builder) {
            this.path_builder = window.Eve.PathBuilder.is(path_builder) ? path_builder : null;
            var path = this.path_builder ? this.path_builder.build() : "/";
            this.getRole('upload_button')[this.path_builder ? 'show' : 'hide']();
            return this.navigate(path);
        };

        F.prototype.navigate = function (path) {
            this.showLoader();
            jQuery.getJSON('/admin/CDNAPI/API', {action: "list", path: path})
                    .done(this.on_load_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));

            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(d.cdnapi);
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            var files = [];
            var a = U.safeArray(d.files);
            for (var i = 0; i < a.length; i++) {
                var file = {
                    id: U.NEString(a[i].id, null),
                    name: U.NEString(a[i].name, null),
                    dir: U.anyBool(a[i].is_dir, false),
                    command: U.anyBool(a[i].is_dir, false) ? "navigate" : "select",
                    nav: false
                };
                if (file.id && file.name) {
                    files.push(file);
                }
            }
            files.sort(function (a, b) {
                if (a.dir && !b.dir) {
                    return -1;
                }
                if (!a.dir && b.dir) {
                    return 1;
                }
                return a.name < b.name ? 1 : (a.name > b.name ? -1 : 0);
            });
            this.getRole('path').val(d.path);
            if (d.path !== "/") {
                files = [{name: "..", dir: true, id: null, nav: true, command: "up"}].concat(files);
            }
            this.files = files;
            this.render();
            return this;
        };

        F.prototype.render = function () {
            this.getRole('list').html(Mustache.render(EFO.TemplateManager().get('files', MC), this));
            return this;
        };

        //</editor-fold>                        

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this._id_to_info = null;
            this._id_to_remove = null;
            this._selected_id = null;
            this.path_builder = null;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //
        F.prototype.onCommandNavigate = function (t) {
            var n = U.NEString(t.data('name'), null);
            if (n) {
                var path = this.getRole('path').val() + "/" + n;
                this.navigate(path);
            }
        };
        F.prototype.onCommandUp = function () {
            var path = this.getRole('path').val() + "/..";
            this.navigate(path);
        };
        F.prototype.onCommandTo_root = function () {
            this.navigate("/");
            return this;
        };
        F.prototype.onCommandTo_up = function () {
            var path = this.getRole('path').val() + "/..";
            this.navigate(path);
        };
        //
        F.prototype.onCommandRefresh = function () {
            this.navigate(this.getRole('path').val());
            return this;
        };
        F.prototype.onCommandFile_info = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                this._id_to_info = id;
                this.showLoader();
                Y.load('cdn.fileinfo')
                        .done(this, this.file_info_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            }
            return this;
        };
        F.prototype.file_info_ready = function (x) {
            x.show().load(this._id_to_info);
            return this;
        };

        F.prototype.onCommandFile_remove = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                this._id_to_remove = id;
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text("Удалить это?<br><b style=\"color:crimson;font-size:.9em\">Это действие нельзя отменить<b>")
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.do_remove();
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не удалять", "Удалить"])
                        .show();
            }
            return this;
        };
        F.prototype.do_remove = function () {
            this.showLoader();
            jQuery.getJSON('/admin/CDNAPI/API', {action: "remove", id: this._id_to_remove, path: this.getRole('path').val()})
                    .done(this.on_load_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.onCommandNew_dir = function () {
            var n = U.NEString(window.prompt("Имя для новой папки:", ""), null);
            if (n) {
                jQuery.getJSON('/admin/CDNAPI/API', {action: "mkdir", path: this.getRole('path').val(), name: n})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.onCommandSelect = function (t) {
            var id = U.NEString(t.data('id'), null);
            if (id) {
                this._selected_id = id;
                this.handle.find('.' + MC + "selected").removeClass(MC + 'selected');
                t.addClass(MC + "selected");
            }
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

        //<editor-fold defaultstate="collapsed" desc="save">         
        F.prototype.save = function (keep_open) {
            if (this._selected_id) {
                this.runCallback(this._selected_id);
                this.clear().hide();
            } else {
                U.TError("Ничего не выбрано");
            }

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
        //</editor-fold>        
        //</editor-fold>

        F.prototype.onCommandUpload = function () {
            if (this.path_builder) {
                this.showLoader();
                Y.load('cdn.cdnuploader').done(this, this.uploader_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            }
            return this;
        };
        F.prototype.uploader_ready = function (x) {
            x.show().load(this.path_builder)
                    .setCallback(this, this.uploader_done);
            return this;
        };
        F.prototype.uploader_done = function () {
            this.load(this.path_builder);
            return this;
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