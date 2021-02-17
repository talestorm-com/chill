(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
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
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable'];
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
        F.prototype.sizeable_defaultWidth = function () {
            return 100;
        };
        F.prototype.sizeable_defaultHeight = function () {
            return 100;
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
            ];// футер перестилить - оставить только ресайзер
        };
        F.prototype.getDefaultTitle = function () {
            return "Редактор изображений";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">              
        F.prototype.load = function (context, owner_id, image) {
            this.clear();
            if (!this._callback_name) {
                this._callback_name = [MD, 'callback'].join('');
                window[this._callback_name] = this.on_frame_callback.bindToObject(this);
            }
            this.getRole('frame').get(0).removeEventListener('error', this.on_frame_error.bindToObject(this));
            this.getRole('frame').get(0).src = '';
            this.getRole('frame').get(0).addEventListener('error', this.on_frame_error.bindToObject(this), true);
            var url = ["/MediaAPI/ImageFly/get_editor?context=", context, '&owner_id=', owner_id, '&image=', image, '&naq=', (new Date()).getTime(), '&callback=', encodeURIComponent(this._callback_name)].join('');
            this.showLoader();
            this.getRole('frame').get(0).src = url;
            return this;
        };


        F.prototype.on_frame_error = function () {
            //debugger;
        };
        F.prototype.on_frame_callback = function (error_info) {
            this.hideLoader();
            if (error_info) {
                //debugger;
            }
            return this;
        };



        //</editor-fold>          
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.getRole('frame').get(0).removeEventListener('error', this.on_frame_error.bindToObject(this));
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.getRole('frame').attr('src', '');
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>


        F.prototype.onCommandCancel = function () {
            this.runCallback();
            return this.hideclear();
        };

        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
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