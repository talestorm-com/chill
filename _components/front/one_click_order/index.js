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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            var html = this.getRole('linner').get(0).outerHTML;
            var fhandle=  jQuery(html.replace(/data-/ig,'adat-').replace(/\sid=/ig,' data-node-html-fake-id='));
            fhandle.addClass(MC+'fake');
            this.getRole('linner').addClass(MC+'org');
            fhandle.insertAfter(this.getRole('linner'));            
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
            ];
        };

        F.prototype.getDefaultTitle = function () {
            return "Заказ в один клик";
        };

        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">        
        F.prototype.load = function (_possible_phone, _possible_name) {
            this.clear();
            try {
                this.getField('phone').val(U.NEString(_possible_phone, ''));
                this.getField('name').val(U.NEString(_possible_name, ''));
            } catch (ee) {

            }
            return this;
        };
        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
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
        //<editor-fold defaultstate="collapsed" desc="OcOrder command">
        F.prototype.onCommandOcorder = function () {
            var d = this.getFields();
            d.phone = U.NEString(d.phone, null);
            d.name = U.NEString(d.name, null);
            if (!d.phone) {
                this.showError("Укажите номер телефона");
                return;
            }
            d.phone = U.NEString(EFO.Checks.formatPhone(d.phone), null);
            if (!d.phone) {
                this.showError("Номер телефона указан некорректно");
                return;
            }
            this.runCallback(d);
            return this.hideclear();
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


        //</editor-fold>        
        //</editor-fold> 

        F.prototype.onMonitorPhone = function (t) {
            t.val(EFO.Checks.tryFormatPhone(t.val()));
            return this;
        };
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