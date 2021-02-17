(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [];
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
            var nhtml = this.getRole('l-inner').get(0).outerHTML;
            var nnode = jQuery(nhtml.replace(/data-/ig, 'atad-'));
            nnode.find('input[type=password]').attr("type", "text");
            nnode.find('label').html('fake');
            nnode.find('label').attr("for", "");
            nnode.find('svg').remove();
            var c = 0;
            nnode.find('[id]').each(function () {
                jQuery(this).attr('id', ["a", MD, "fake", c].join(''));
                c++;
            });
            nnode.addClass(MC + "fake");
            nnode.appendTo(this.getRole('l-inner').parent());
            this.getRole('l-inner').addClass(MC + "org");
            return this;
        };



        F.prototype.onAfterShow = function () {
            this.handle[(U.isMobile() ? 'addClass' : 'removeClass')](MC + 'MobileView');
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
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
            return [];
        };

        F.prototype.getDefaultTitle = function () {
            return "Регистрация";
        };

        //</editor-fold>                          

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clear_errors();
            return this;
        };
        F.prototype.clear_errors = function () {
            this.handle.find('[data-error]').html('');
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        F.prototype.onMonitorPhone = function (t) {
            t.val(EFO.Checks.tryFormatPhone(t.val()));
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="cancel command">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="register command">
        F.prototype.onCommandDo_register = function () {
            this.clear_errors();
            var d = this.getFields();
            var df = EFO.Filter.Filter().applyFiltersToHash(d, this.getFilters().getSectionExport('register'));
            var errors = [];
            for (var k in df) {
                if (df.hasOwnProperty(k)) {
                    if (EFO.Filter.Values().InvalidValue.is(df[k])) {
                        errors.push({'f': k, 'm': df[k].err});
                    }
                }
            }
            if (!EFO.Filter.Values().InvalidValue.is(df.password)) {
                if (df.password.length < 6) {
                    errors.push({f: 'password', m: "MinPassLength"});
                } else if (!EFO.Filter.Values().InvalidValue.is(df.repassword) && df.password !== df.repassword) {
                    errors.push({f: 'repassword', m: "NotMatch"});
                }
            }
            if (!df.apd) {
                errors.push({f: 'apd', m: "APDIsRequired"});
            }
            if (errors.length) {
                this.display_errors(errors);
                return;
            }

            this.showLoader();
            var data = {action: 'register', data: JSON.stringify(df)};
            jQuery.post('/Auth/API', data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_post_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_post_responce = function (d) {
            d = U.safeObject(d);
            if (d.status === "ok") {
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
                var message = d.error_info.message;
                if (message === 'login_exists') {
                    this.display_errors([{f: 'login', 'm': 'exists'}]);
                    return this;
                }
                U.TError(message);
                return this;
            }
            U.TError("Некорректный ответ сервера");
            return this;
        };

        F.prototype.on_post_fail = function () {
            this.showError("Ошибка связи с сервером");
            return this;
        };


        F.prototype.getError = function (f) {
            return this.handle.find('[data-error=' + f + ']');
        };

        F.prototype.translate_error = function (x, y) {
            var key = [MC, x, y].join(':');
            return EFO.Translator().T(key);
        };


        F.prototype.display_errors = function (errors) {
            var nodes = [];
            for (var i = 0; i < errors.length; i++) {
                var node = this.getField(errors[i].f);
                var e_node = this.getError(errors[i].f);
                var message = this.translate_error(errors[i].f, errors[i].m);
                nodes.push(node);
                e_node.html(message);
            }
            var max_y = 99999999;
            var max_n = null;
            var scroller_top = U.IntOr(this.getRole('scroll').get(0).getBoundingClientRect().top, 0);
            var scroller_offset = -1 * U.IntOr(this.getRole('scroll').scrollTop(), 0);
            var scroll_delta = -1 * (scroller_top + scroller_offset);
            for (var i = 0; i < nodes.length; i++) {
                var cy = U.IntOr(nodes[i].get(0).getBoundingClientRect().top, 1000) + scroll_delta;
                if (cy < max_y) {
                    max_y = cy;
                    max_n = nodes[i];
                }
            }
            if (max_n) {
                this.getRole('scroll').scrollTop(max_y);
            }
            return this;
        };

        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
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