(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.css('/assets/vendor//datepicker/css.css'),
        Y.js('/assets/vendor//datepicker/js_async.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var ADVT = window.Eve.ADVTable;
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable', 'Fieldable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.init_pickers();
            return this;
        };


        F.prototype.onAfterShow = function () {
            this.clear();
            return PARP.onAfterShow.apply(this, APS.call(arguments));
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
            return [];
        };

        F.prototype.getDefaultTitle = function () {
            return "";
        };

        //</editor-fold>                 
        F.prototype.init_pickers = function () {
            window.xxpicker = window.xxpicker || [];
            window.xxpicker.push(this._init_pickers.bindToObject(this));
            return this;
        };

        F.prototype._init_pickers = function () {
            var picker_params = {
                lang: 'ru',
                lazyInit: true,
                format: 'd.m.Y',
                closeOnDateSelect: true,
                closeOnTimeSelect: true,
                closeOnWithoutClick: false,
                timepicker: false,
                theme: 'dark',                
                todayButton: false,
                scrollMonth: false,
                scrollTime: false,
                scrollInput: false,
                dayOfWeekStart: 1
            };
            this.getField('date_start').datetimepicker(picker_params);
            this.getField('date_end').datetimepicker(picker_params);
            return this;
        };



        F.prototype.onMonitorMode = function (d) {
            if (d.val() === 'mailto') {
                this.getRole('mailblock').show();
            } else {
                this.getRole('mailblock').hide();
            }
        };

        F.prototype._set_field_mode = function (d, fi) {
            this.getField('mode').val(U.NEString(d.mode, 'mailto'));
            this.onMonitorMode(this.getField('mode'));
            return this;
        };

        F.prototype.install = function (x) {
            var node = document.getElementById(x);
            if (node) {
                this.container_node = node;
                this.handle.appendTo(node);
                this.show();
                this.clear();
            }
            return this;
        };

        F.prototype.getContainer = function () {
            if (this.container_node) {
                return this.container_node;
            }
            if (!this.safe_node) {
                this.safe_node = document.createElement('div');
            }
            return this.safe_node;
        };

        F.prototype.onCommandRegen = function () {
            jQuery.getJSON('/admin/T/API/?action=regen');
            return this;
        };

        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            return this;
        };


        F.prototype.onCommandGenerate = function () {
            var data = this.getFields();
            if (data.mode === "mailto") {
                this.showLoader();
                data.action = "pending_report";
                jQuery.getJSON("/admin/PaymentReport/API", data, null, 'json')
                        .done(this.on_report_respose.bindToObject(this))
                        .fail(this.on_report_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.send_form(data);
            }
        };

        F.prototype.on_report_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this;
        };

        F.prototype.on_report_respose = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_report_success();
                }
                if (d.status === 'error') {
                    return this.on_report_fail(d.error_info.message);
                }
            }
            this.on_report_fail("invalid server response");
        };

        F.prototype.on_report_success = function () {
            EFO.Alert().set_style('green').set_icon('ok').set_image('ok')
                    .set_title('Готово').set_timeout(5000).set_close_btn(true)
                    .set_text("Отчет добавлен в планировщик.\nКогда отчет будет готов, Вы получите его на указанную почту")
                    .show();
        };


        F.prototype.mk_url = function (d) {
            var p = [];
            d = U.safeObject(d);
            for (var k in d) {
                if (d.hasOwnProperty(k) && !U.isCallable(d[k])) {
                    p.push([window.encodeURIComponent(k), window.encodeURIComponent(d[k])].join('='));
                }
            }
            return p.join('&');
        };

        F.prototype.send_form = function (data) {
            var url = "/admin/PaymentReport/Download?" + this.mk_url(data);
            location.href = url;
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
                    Y.report_fail(FQCN, "Ошибка при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();