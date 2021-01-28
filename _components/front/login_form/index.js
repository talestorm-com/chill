(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        //Y.load
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC);// префикс класса
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            this.getField('login').on('keydown', this.onLoginEnt.bindToObject(this));
            this.getField('password').on('keydown', this.onPasswordEnt.bindToObject(this));
            var fake = this.getRole('acontent').get(0).outerHTML;
            this.fake_handle = jQuery(fake.replace(/data-/ig,'adat-'));
            this.fake_handle.addClass(MC+"fakeform");
            this.getRole('acontent').addClass(MC+'org');
            this.fake_handle.find('[id]').each(function(){
                jQuery(this).attr('id',jQuery(this).attr('id')+"fake");
            });
            this.fake_handle.insertAfter(this.getRole('acontent'));
            return this;
        };

        F.prototype.onMonitorLogin = function (t) {
            if (!EFO.Checks.isEmail(t.val())) {
                this.getRole('login_wrap').addClass('LoginSystemLoginErrorField');
            } else {
                this.getRole('login_wrap').removeClass('LoginSystemLoginErrorField');
            }
            return this;
        };
        F.prototype.onMonitorPassword = function (t) {
            var c = U.NEString(t.val(), null);
            if (c && c.length > 5) {
                this.getRole('password_wrap').removeClass('LoginSystemLoginErrorField');
            } else {
                this.getRole('password_wrap').addClass('LoginSystemLoginErrorField');
            }
            return this;
        };

        F.prototype.onLoginEnt = function (e) {
            if (e.keyCode === 13) {
                this.getField('password').focus();
            }
            return this;
        };

        F.prototype.onPasswordEnt = function (e) {
            if (e.keyCode === 13) {
                var eo = EFO.Checks.isEmail(this.getField('login').val());
                var vp = U.NEString(this.getField('password').val(), null);
                if (eo && vp && vp.length > 5) {
                    this.onCommandDo_login();
                }
            }
            return this;
        };

        F.prototype.onAfterShow = function () {
            this.handle[(U.isMobile() ? 'addClass' : 'removeClass')](MC + 'MobileView');
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            this.load();
            jQuery('body').addClass(MC + 'BodyScrollLock');
            return this;
        };

        F.prototype.onBeforeHide = function () {
            jQuery('body').removeClass(MC + 'BodyScrollLock');
            return PARP.onBeforeHide.apply(this, APS.call(arguments));
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
                {'command': "login", 'text': "Вход"},
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Вход в систему";
        };

        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function () {
            this.clear();
            try {
                this.getField('login').val(U.NEString(window.localStorage.getItem(MC + 'Login'), ''));
                this.getField('login').change();
            } catch (ee) {

            }
            return this;
        };
        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.handle.find('.LoginSystemLoginErrorField').removeClass('LoginSystemLoginErrorField');
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="cancel command">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="login command">
        F.prototype.onCommandDo_login = function () {
            try {
                var d = this.getFields();
                var df = EFO.Filter.Filter().applyFiltersToHash(d, this.getFilters().getSectionExport('auth'));
                if (!df.login) {
                    U.Error("Укажите корректный email адрес!");
                }
                if (!df.password) {
                    U.Error("Не указан пароль");
                }
                if (df.password.length < 6) {
                    U.Error("Пароль должен быть не менее 6 символов!");
                }
                df.url = window.location.href;
                df.md = EFO.MD5().MD5(df.url);
            } catch (ee) {
                return this.showError(ee.message);
            }
            this.showLoader();
            df.action = "auth";
            jQuery.post('/Auth/API', df, null, 'json')
                    .always(this.hideLoader.bindToObject(this))
                    .done(this.onLoginResponce.bindToObject(this))
                    .fail(this.onNetworkFail.bindToObject(this));
            return this;
        };

        F.prototype.onLoginResponce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this.getRole('submit').click();
                    this.getField('password').val('');
                    try {
                        window.localStorage.setItem(MC + 'Login', this.getField('login').val());
                    } catch (ee) {

                    }
                    EFO.Events.GEM().Trigger("SYS_LOGIN_SUCCESS", d);
                    return this.clear().hide();
                }
                if (d.status === 'error') {
                    return this.processError(d);
                }
            }
            return this.onNetworkFail("MailformedResponce");
        };

        F.prototype.processError = function (d) {
            var fn = ["onError", d.error_info.message].join('');
            if (U.isCallable(this[fn])) {
                this[fn](d);
            } else {
                this.showError(d.error_info.message);
            }
            return this;
        };

        F.prototype.onErrorLOCK_USER = function (d) {
            this.showLoader();
            this._root = true;
            jQuery.componentor().LoadComponent('CommonForms.LockedLoginDialog.LockedLoginDialog')
                    .done(this.onLockReady.bindToObject(this))
                    .fail(this.onRequiredComponentFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.onLockReady = function (x) {
            x.show().load(this.getField('login').val(), this._root);//активировать может кто угодно
            return this;
        };

        F.prototype.onNetworkFail = function (x) {
            var msg = "NetworkError";
            if (U.isError(x)) {
                msg = x.message;
            } else if (U.NEString(x, null)) {
                msg = x;
            } else if (U.isObject(x) && U.NEString(x.statusText, null)) {
                msg = x.statusText;
            }
            this.showError(msg);
            return this;
        };

        F.prototype.showError = function (tx) {
            EFO.Alert()
                    .set_style("red")
                    .set_title("Ошибка")
                    .set_text(EFO.Translator().T(tx))
                    .set_icon("!")
                    .set_image("!")
                    .set_timeout(5000)
                    .set_close_btn(true)
                    .show();
            //new EveFlash({cssclass: "red", ICON: "stop", IMAGE: "stop", TO: 5000, CLOSE: false, TITLE: "Ошибка", TEXT: tx});
            return this;
        };

        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="registerCommand">
        F.prototype.onCommandRegister = function () {
            this.showLoader();
            EFO.Com().load('front.register_form')
                    .done(this, this.regform_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.regform_ready = function (x) {
            x.show();
            return this.hideclear();
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="restore command">
        F.prototype.onCommandRestore = function () {
            var df = EFO.Filter.Filter().applyFiltersToHash(this.getFields(), this.getFilters().getSectionExport('auth'));
            if (!df.login) {
                return this.showError("Укажите email");
            }
            df.password = null;
            df.action = "restore";
            this.showLoader();
            jQuery.getJSON('/Auth/API', df)
                    .done(this.onResetResponce.bindToObject(this))
                    .fail(this.onNetworkFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.onResetResponce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.onResetSuccess();
                }
                if (d.status === 'error') {
                    return this.processError(d);
                }
            }
            return this.onNetworkFail("Некорректный ответ сервера");
        };

        F.prototype.onResetSuccess = function () {
            EFO.simple_confirm()
                    .set_style("blue")
                    .set_title("Выполнено")
                    .set_text("Инструкции по сбросу пароля отправлены на Ваш email")
                    .set_close_btn(true)
                    .set_icon("ok")
                    .set_image("ok")
                    .set_buttons(["Ок"])
                    .show();
            //new EveFlash({"cssclass": "ntl", ICON: "ok", IMAGE: "ok", TITLE: "Выполнено!", TEXT: "Инструкции по сбросу пароля отправлены на email.", TO: 5000, CLOSE: false});
            return this;
        };

        //</editor-fold>
        //</editor-fold> 
        F.prototype.showLoader = function () {
            this.getRole('loader_new').show();
            return this;
        };
        F.prototype.hideLoader = function () {
            this.getRole('loader_new').hide();
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            this.showError("Ошибка при загрузке компонента!");
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