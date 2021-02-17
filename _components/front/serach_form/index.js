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
            //this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            //  this.iys();
            this.handle.find('input[type=text]').on('keydown', this.on_keydown.bindToObject(this));
            this.handle.find('form').on('submit', this.on_submit.bindToObject(this));
            this.handle.on('click',function(e){e.stopPropagation();});
            return this;
        };
        
        F.prototype.do_posite_at = function(x){
            return this;
            this.handle.addClass(MC+"Positioned");
            var bcr = x.get(0).getBoundingClientRect();
            var t = bcr.top+bcr.height;
            var r = bcr.left+bcr.width;            
            this.handle.css({
                top:t+"px",
                right:(window.innerWidth-r)+"px"
            });
            return this;
        };

        F.prototype.on_submit = function (e) {
            if (!this.allow_submit) {
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                e.stopPropagation;
            }
        };

        F.prototype.on_keydown = function (e) {
            if (e.keyCode === 13) {
                this.do_search();
            }
        };

        F.prototype.do_search = function () {
            var tx = U.NEString(this.handle.find('input[type=text]').val(), null);
            if (tx) {
                this.allow_submit = true;
                this.handle.find('form').submit();
                this.allow_submit = false;
            }
        };

        F.prototype.onCommandSubmit = function () {
            this.do_search();
        };

        F.prototype.iys = function () {
            (function (w, d, c) {
                var s = d.createElement('script'), h = d.getElementsByTagName('script')[0], e = d.documentElement;
                if ((' ' + e.className + ' ').indexOf(' ya-page_js_yes ') === -1) {
                    e.className += ' ya-page_js_yes';
                }
                s.type = 'text/javascript';
                s.async = true;
                s.charset = 'utf-8';
                s.src = (d.location.protocol === 'https:' ? 'https:' : 'http:') + '//site.yandex.net/v2.0/js/all.js';
                h.parentNode.insertBefore(s, h);
                (w[c] || (w[c] = [])).push(function () {
                    Ya.Site.Form.init()
                });
            })(window, document, 'yandex_site_callbacks');
            return this;
        };



        F.prototype.onAfterShow = function () {
            this.handle[(U.isMobile() ? 'addClass' : 'removeClass')](MC + 'MobileView');
            PARP.onAfterShow.apply(this, APS.call(arguments));
            //this.placeAtCenter();
            jQuery('body').addClass(MC + 'BodyScrollLock');
            jQuery(document).on('click',this.on_doc_click.bindToObject(this));
            this.allow_submit = false;
            return this;
        };

        F.prototype.onBeforeHide = function () {
            jQuery('body').removeClass(MC + 'BodyScrollLock');
            this.allow_submit = false;
            jQuery(document).off('click',this.on_doc_click.bindToObject(this));
            return PARP.onBeforeHide.apply(this, APS.call(arguments));
        };
        
        F.prototype.on_doc_click = function(){
            this.hide();
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
            return "search";
        };

        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function () {
            this.clear();

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