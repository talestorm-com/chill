(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>', API_KEY = "<?=$this->get_preference('MAP_BOX_KEY')?>";
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
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = window.Eve.ADVTable;
        mapboxgl.accessToken = API_KEY;
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
                this.map.resize();
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
            this.map = new mapboxgl.Map({
                container: this.getRole('map').get(0),
                style: 'mapbox://styles/mapbox/streets-v10',
                center: [57, 37],
                zoom: 10
            });
            var lang = new MapboxLanguage({
                defaultLanguage: 'ru'
            });
            this.map.addControl(lang);
            return this;
        };

        F.prototype.set_delegate = function (x) {
            this.deledate = U.isObject(x) ? x : null;
            return this.on_delegate_changed();
        };
        //</editor-fold>
        F.prototype.on_delegate_changed = function () {
            if (this.deledate) {
                this.showLoader();
                this.deledate.mbsd_load_points(this.on_points_ready.bindToObject(this));
            }
            return this;
        };

        F.prototype.remove_all_markers = function () {
            for (var k in this.markers) {
                if (U.isObject(this.markers[k]) && U.isCallable(this.markers[k].remove)) {
                    try {
                        this.markers[k].remove();
                    } catch (e) {

                    }
                }
            }
            this.markers = {};
        };

        F.prototype.on_points_ready = function (points) {
            points = U.safeArray(points);
            this.remove_all_markers();
            var bounds = new mapboxgl.LngLatBounds();
            if (points && points.length) {
                var delegate = this.deledate;
                for (var i = 0; i < points.length; i++) {
                    var mi = points[i];
                    /*
                     address: "Улица тимирязевская д5"
                     email: "aaa@bbb.cc"                                
                     lat: 37.563635
                     lon: 55.818486
                     name: "Магазин на тимирязевской"
                     phone: "+7 (888) 123 45 67"
                     id:25
                     
                     */

                    var lat = U.FloatOr(mi.lat, null);
                    var lon = U.FloatOr(mi.lon, null);
                    var id = U.IntMoreOr(mi.id, 0, null);
                    if (lat !== null && lon !== null && id) {
                        var marker = new mapboxgl.Marker({
                            draggable: false,
                            color: delegate.mbsd_get_marker_color(mi),
                        }).setLngLat([lat, lon])
                                .addTo(this.map);
                        marker.xob = mi;
                        var alias = ["P", id].join('');
                        this.markers[alias] = marker;
                        jQuery(marker.getElement()).data('alias', alias);
                        jQuery(marker.getElement()).on('click', this.on_marker_click.bindToObjectWParam(this));
                        bounds.extend(marker.getLngLat());
                    }
                }
                this.map.fitBounds(bounds, {padding: 50});

            }
            return this.hideLoader();
        };


        F.prototype.on_marker_click = function (_t, e) {
            var t = jQuery(_t);
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            var alias = U.NEString(t.data('alias'), null);
            if (alias && U.isObject(this.markers[alias]) && U.isObject(this.markers[alias].xob)) {
                this.get_popup().setLngLat(this.markers[alias].getLngLat());
                this.get_popup().setHTML(this.deledate.mbsd_fill_popup(this.markers[alias].xob));
                this.get_popup().addTo(this.map);
            }
        };
        F.prototype.get_popup = function () {
            if (!this.popup) {
                this.popup = new mapboxgl.Popup({
                    closeButton: true, closeOnClick: true,
                    className: "xmxxx_mapbox_gl_pop"
                });
            }
            return this.popup;
        };

        F.prototype.onCommandCustom = function (t, e) {
            var ccn = U.NEString(t.data('customCommand'), null);
            if (ccn) {
                var id = U.IntMoreOr(t.data('id'), 0, null);
                var key = ["P", id].join('');
                var mi = this.markers[key].xob;
                this.deledate.mbsd_on_command(ccn, t, mi);
                this.popup.setHTML(this.deledate.mbsd_fill_popup(mi));
                
                var delegate = this.deledate;
                var om = this.markers[key];
                var new_marker = new mapboxgl.Marker({
                    draggable: false,
                    color: delegate.mbsd_get_marker_color(mi),
                }).setLngLat([om.getLngLat().lng, om.getLngLat().lat])
                        .addTo(this.map);
                new_marker.xob = mi;
                this.markers[key] = new_marker;
                jQuery(new_marker.getElement()).data('alias', key);
                jQuery(new_marker.getElement()).on('click', this.on_marker_click.bindToObjectWParam(this));
                om.remove();
            }
            return this;
        };





        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.set_delegate(null);
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
            if(this.deledate){
                this.deledate.mbsd_ok();
            }            
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