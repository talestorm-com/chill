(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js'),
        Y.js('/assets/js/ET/ADVTable/extended_filters/boolean/boolean.js'),
        Y.js('/assets/vendor/OpenLayers/ol.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = window.Eve.ADVTable;
        var mapbox_key = '<?=$this->get_preference("MAP_BOX_KEY")?>';
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
            this.init_map();
            this.LEM.On('NEED_POSITE', this, this.resize_map);
            return this;
        };

        F.prototype.resize_map = function () {
            try {
                this.map.handleResize_();
            } catch (e) {

            }
            return this;
        };


        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            this.resize_map();
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
                {'command': "save", 'text': "Выбрать"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Выбор по карте";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">        
        F.prototype.load = function (coordinates, address) {
            this.showLoader();
            if (U.isArray(coordinates) && coordinates.length === 2) {
                if (U.FloatOr(coordinates[0], null) !== null) {
                    if (U.FloatOr(coordinates[1], null) !== null) {
                        return this.load_coordinates(coordinates);
                    }
                }
            }
            if (U.NEString(address, null)) {
                return this.load_address(address);
            }
            this.hideLoader();
            return this;
        };

        F.prototype.load_coordinates = function (c) {
            var point = ol.proj.transform(c, 'EPSG:4326', 'EPSG:3857');
            this.geoMarker.setGeometry(new ol.geom.Point(point));
            this.map.getView().setCenter(point);
            this.map.getView().setZoom(16);
            this.hideLoader();
            return this;
        };

        F.prototype.load_address = function (address) {
            if (U.NEString(address, null)) {
                this.showLoader();
                var url = ["https://api.mapbox.com/geocoding/v5/mapbox.places/",
                    encodeURIComponent(address), ".json?types=address",
                    "&access_token=", mapbox_key
                ].join('');
                jQuery.getJSON(url)
                        .done(this.on_geocoder.bindToObject(this))
                        .fail(this.on_geocoder_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.hideLoader();
            }
            return this;
        };

        F.prototype.on_geocoder = function (d) {
            this.hideLoader();
            d = U.safeObject(d);
            var features = U.safeArray(d.features);
            if (features.length) {
                features.sort(function (a, b) {
                    return b.relevance - a.relevance;
                });
                var feature = features[0];
                var p = U.safeArray(U.safeObject(feature.geometry).coordinates);
                if (p.length === 2) {
                    var point = ol.proj.transform(p, 'EPSG:4326', 'EPSG:3857');
                    this.geoMarker.setGeometry(new ol.geom.Point(point));
                    this.map.getView().setCenter(point);
                    this.map.getView().setZoom(16);
                }
            }
            return this;
        };
        F.prototype.on_geocoder_fail = function () {
            this.hideLoader();
            return this;
        };





        //</editor-fold>  

        //<editor-fold defaultstate="collapsed" desc="map">
        F.prototype.init_map = function () {
            window.TTT = this;
            this.geoMarker = new ol.Feature({
                type: 'icon',
                geometry: new ol.geom.Point([0, 0])
            });
            this.styles = {
                'icon': new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        src: 'data:image/png;base64,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'
                    })
                })
            };
            var self = this;
            this.vectorLayer = new ol.layer.Vector({
                source: new ol.source.Vector({
                    features: [this.geoMarker]
                }),
                style: function (feature) {
                    // hide geoMarker if animation is active
                    if (false && feature.get('type') === 'geoMarker') {
                        return null;
                    }
                    return self.styles[feature.get('type')];
                }
            });

            this.map = new ol.Map({
                target: this.getRole('map').get(0), //["a", MD, "map"].join(''),
                layers: [
                    new ol.layer.Tile({
                        source: new ol.source.OSM()
                    }),
                    this.vectorLayer
                ],
                view: new ol.View({
                    center: ol.proj.fromLonLat([37.41, 8.82]),
                    zoom: 4
                })
            });

            this.map.on('click', function (event) {
                var lat_lon = ol.proj.toLonLat(event.coordinate);
                self.geoMarker.setGeometry(new ol.geom.Point(ol.proj.transform(lat_lon, 'EPSG:4326', 'EPSG:3857')));
            });
            return this;
            //TTT.geoMarker.setGeometry(new ol.geom.Point(ol.proj.transform([37, 57], 'EPSG:4326','EPSG:3857')))
        };
        //</editor-fold>





        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
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
        F.prototype.onCommandSave = function () {
            this.save(true);
            return this;
        };
        //</editor-fold>

        //</editor-fold>        

        //<editor-fold defaultstate="collapsed" desc="save">  

        F.prototype.save = function (keep_open) {
            var lot_lan = ol.proj.toLonLat(this.geoMarker.getGeometry().getCoordinates());
            this.runCallback(lot_lan);
            this.hide().clear();
            return this;
        };
        //</editor-fold>        
        //</editor-fold>


        //<editor-fold defaultstate="collapsed" desc="selection">
        function selection() {
            return (selection.is(this) ? this.init : selection.F).apply(this, APS.call(arguments));
        }
        var SP = U.FixCon(selection).prototype;
        SP.items = null;
        SP.index = null;
        SP.LEM = null;
        SP.ss = null;
        SP.init = function (source) {
            this.ss = source;
            this.reset_silent();
            this.LEM = EFO.Events.LEM();

            return this;
        };

        SP.reset_silent = function () {
            this.items = [];
            this.index = {};
            return this;
        };

        SP.reset = function () {
            this.reset_silent();
            this.LEM.run("CHANGE", this);
            return this;
        };


        SP.get_length = function () {
            return this.items.length;
        };


        SP.add = function (id) {
            if (this.add_silent(id)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.get_item_cloned = function (x) {
            return this.ss.get_item_cloned(x);
        };
        SP.add_silent = function (id) {
            id = U.IntMoreOr(id, 0, null);
            if (id) {
                var key = ["P", id].join('');
                if (!U.isObject(this.index[key])) {
                    var item = this.get_item_cloned(id);
                    if (item) {
                        this.items.push(item);
                        this.index[key] = item;
                        return true;
                    }
                }
            }
            return false;
        };

        SP.add_array = function (ids) {
            if (this.add_array_silent(ids)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.add_array_silent = function (ids) {
            var ids = U.safeArray(ids);
            var ca = 0;
            for (var i = 0; i < ids.length; i++) {
                if (this.add_silent(ids[i])) {
                    ca++;
                }
            }
            return ca ? true : false;
        };

        SP.remove = function (id) {
            if (this.remove_silent(id)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.remove_array = function (ids) {
            if (this.remove_array_silent(ids)) {
                this.LEM.run("CHANGE", this);
            }
            return this;
        };

        SP.remove_silent = function (id) {
            id = U.IntMoreOr(id, 0, null);
            if (id) {
                var key = ["P", id].join('');
                if (U.isObject(this.index[key])) {
                    var item = this.index[key];
                    delete(this.index[key]);
                    var ix = this.items.indexOf(item);
                    if (ix >= 0) {
                        this.items = [].concat(this.items.slice(0, ix), this.items.slice(ix + 1));
                    }
                    return true;
                }
            }
            return false;
        };

        SP.remove_array_silent = function (ids) {
            ids = U.safeArray(ids);
            var cc = 0;
            for (var i = 0; i < ids.length; i++) {
                if (this.remove_silent(ids[i])) {
                    cc++;
                }
            }
            return cc ? true : false;
        };

        SP.exists = function (x) {
            var key = ["P", U.IntMoreOr(x, 0, null)].join('');
            return U.isObject(this.index[key]);
        };

        //</editor-fold>

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