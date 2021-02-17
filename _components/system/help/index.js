(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        // он в любом случае зависимый, так что на счет либы можно не беспокоится
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC);
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Sizeable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
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
            return "Справка";
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Лоадер)">        
        F.prototype.load = function (f, t) {
            this.clear();
            this.showLoader();
            jQuery.getJSON('https://larrohelp.av-d.ru/API/Client/Help/Help', {fqcn: f, title: f})
                    .done(this.onResponce.bindToObject(this))
                    .fail(this.onNetworkFail(this))
                    .always(this.hideLoader(this));
            return this;
        };

        F.prototype.onResponce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    if (U.isObject(d.data)) {
                        return this.onSuccessResponce(d.data);
                    }
                }
                if (d.status === 'error') {
                    return this.onNetworkFail(d.error);
                }
            }
            return this.onNetworkFail("Некорректный ответ сервера");
        };

        F.prototype.onNetworkFail = function (x) {
            var m = "Ошибка сети";
            if (U.isError(x)) {
                m = x.message;
            } else if (U.isObject(x) && U.NEString(x.statusText, null)) {
                m = x.statusText();
            } else if (U.NEString(x, null)) {
                m = x;
            }
            this.error = m;
            this.getRole('content').html(Mustache.render(EFO.TemplateManager().get('error', MC), this));
            return this;
        };

        F.prototype.onSuccessResponce = function (d) {
            if (U.isObject(d.help)) {
                this.setTitle(d.help.name);
                this.getRole('content').html('<iframe></iframe>');
                this.content = d.help.content;
                var html = Mustache.render(EFO.TemplateManager().get('content', MC), this);
                this.content = null;

                var frame = this.getRole('content').find('iframe');
                var fr = frame.get(0);
                try {
                    U.Error('a');
                    fr.contentWindow.document.open('text/htmlreplace');
                    fr.contentWindow.document.write(html);
                    fr.contentWindow.document.close();
                } catch (r) {
                    fr.contentWindow.contents = html;
                    fr.src = 'javascript:window["contents"]';
                }
            } else {
                this.onNetworkFail("Справка по этому элементу не найдена!");
            }
            return this;
        };


        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.setTitle(this.getDefaultTitle());
            this.getRole('content').html('');
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };

        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="misc and report">        
        F.prototype.onRequiredComponentFail = function () {
            U.TError("Ошибка при загрузке компонента!");
        };


        F.prototype.cssLink = function () {
            return [window.location.protocol, "//", window.location.host, "/assets/css/help/main.css"].join('');
        };

        Y.reportSuccess(FQCN, F());
        /*
         * связь оставляем через стандартный проктокол - soap - больше проблем, чем толку
         */
        //</editor-fold>
    }
    //<editor-fold defaultstate="collapsed" desc="dependency loader">
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