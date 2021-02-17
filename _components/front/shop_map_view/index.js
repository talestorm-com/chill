(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.js("https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.js"),
        Y.css("https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.css"),
        Y.js("https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-language/v0.10.1/mapbox-gl-language.js")
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            this.handle.on('click', (function (e) {
                if (this.handle.is(e.target) || this.getRole('window').is(e.target)) {
                    this.hideclear();
                }
            }).bindToObject(this));
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
            return "map";
        };

        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер (композит)">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id) {
            this.clear();
            this.showLoader();
            jQuery.getJSON('/Map/API', {action: "get_partner", id: id})
                    .done(this.on_responce.bindToObject(this))
                    .fail(this.on_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.init_map = function (ak) {
            mapboxgl.accessToken = ak;
            this.map = new mapboxgl.Map({
                container: ['map_', MD].join(''),
                style: 'mapbox://styles/mapbox/streets-v10',
                center: [57.37, 37.57],
                zoom: 14
            });
            this.lang = new MapboxLanguage({
                defaultLanguage: 'ru'
            });
            this.map.addControl(this.lang);
        };

        F.prototype.on_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    var dp = U.isObject(d.partner) ? d.partner : null;
                    if (dp) {
                        if (!this.map) {
                            this.init_map(d.api_key);
                        }
                        this.set_pointer(dp);
                        return this;
                    }
                }
            }
            return this.on_fail();
        };
        F.prototype.on_fail = function () {
            return this.hideclear();
        };

        F.prototype.set_pointer = function (d) {
            if (!this.marker) {
                this.marker = new mapboxgl.Marker({
                    draggable: false,
                    color: "#ff0000"
                });
                //jQuery(this.marker.getElement()).on('click', this.on_marker_click.bindToObject(this));
            }
            if (!this.popup) {
                this.popup = new mapboxgl.Popup({
                    closeButton: true, closeOnClick: true,
                    className: "xmxxx_mapbox_gl_pop"
                });
            }
            this.marker.setLngLat([d.lat, d.lon]).addTo(this.map);
            this.map.setCenter([d.lat, d.lon]);
            this.map.setZoom(16);
            this.popup.setLngLat(this.marker.getLngLat());
            this.d = d;
            this.popup.setHTML(Mustache.render(EFO.TemplateManager().get('pop', MC), this));
            this.d = null;
            this.popup.addTo(this.map);

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