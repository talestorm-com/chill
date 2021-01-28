(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
    ];
    //</editor-fold>    
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.flatController, PARP = PAR.prototype, APS = Array.prototype.slice;
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
            return  (F.is(this) ? this.init : F.F).apply(this, APS.call(arguments));
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">   '
        F.prototype.onInit = function (e_instance_id) {

            e_instance_id = U.NEString(e_instance_id, [MC, U.UUID()].join(''));
            e_instance_id ? 0 : U.Error("ribbon lent requires instance id as parameter");
            this.instance_id = [MC, e_instance_id].join('_');
            PARP.onInit.apply(this, APS.call(arguments));
            this.get_instance_id = this._get_instance_id.bindToObject(this);
            this.init_renderers();
            this.init_drag();
            this.reload();
            return this;
        };

        F.prototype._get_instance_id = function () {
            return this.instance_id;
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

        //</editor-fold>  

        F.prototype.init_drag = function () {
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_ctVIDEO', this, this.drag_starts);
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_ctSEASON', this, this.drag_starts);
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_ctCOLLECTION', this, this.drag_starts);
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_ctGIF', this, this.drag_starts);
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_ctTEXT', this, this.drag_starts);
            EFO.DnDManager().LEM.on('ON_DROPPED_ctVIDEO', this, this.drop);
            EFO.DnDManager().LEM.on('ON_DROPPED_ctSEASON', this, this.drop);
            EFO.DnDManager().LEM.on('ON_DROPPED_ctCOLLECTION', this, this.drop);
            EFO.DnDManager().LEM.on('ON_DROPPED_ctGIF', this, this.drop);
            EFO.DnDManager().LEM.on('ON_DROPPED_ctTEXT', this, this.drop);
            EFO.DnDManager().LEM.on('ABORT_DRAG_ctVIDEO', this, this.drag_abort);
            EFO.DnDManager().LEM.on('ABORT_DRAG_ctSEASON', this, this.drag_abort);
            EFO.DnDManager().LEM.on('ABORT_DRAG_ctCOLLECTION', this, this.drag_abort);
            EFO.DnDManager().LEM.on('ABORT_DRAG_ctGIF', this, this.drag_abort);
            EFO.DnDManager().LEM.on('ABORT_DRAG_ctTEXT', this, this.drag_abort);
            return this;
        };

        F.prototype.drag_starts = function () {
            this.handle.addClass(MC + "DRAGACTIVE");
            return this;
        };

        F.prototype.drag_abort = function () {
            this.handle.removeClass(MC + "DRAGACTIVE");
            return this;
        };

        F.prototype.drop = function (x, y, z) {
            if (y.is(this.getRole('droptarget'))) {
                var ctype = U.NEString(x._dragging.data('type'), null);
                var id = U.IntMoreOr(x._dragging.data('id'), 0, null);
                if (id && ctype) {
                    var fn = ["on_drop_", ctype].join('');
                    if (U.isCallable(this[fn])) {
                        this[fn](id);
                    }
                }
            }
            return this;
        };

        F.prototype.on_drop_ctVIDEO = function (id) {
            this._id_dropped = id;
            this._type_dropped = 'ctVIDEO';
            this.launch_linker();
        };
        F.prototype.on_drop_ctCOLLECTION = function (id) {
            this.add_content_id([id]);
            return this;
        };
        F.prototype.on_drop_ctGIF = function (id) {
            this.add_content_id([id]);
            return this;
        };
        F.prototype.on_drop_ctTEXT = function (id) {
            this.add_content_id([id]);
            return this;
        };

        F.prototype.on_drop_ctSEASON = function (id) {
            this._id_dropped = id;
            this._type_dropped = 'ctSEASON';
            this.launch_linker();
        };

        F.prototype.launch_linker = function () {
            this.showLoader();
            Y.load('selectors.ribbon_type_selector')
                    .done(this, this.type_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.type_selector_ready = function (x) {
            var types = this._type_dropped === 'ctVIDEO' ? [{n: "Видео", v: "ctVIDEO"}, {n: "Трейлер", v: "ctTRAILER"}] : [
                {n: "Сериал", v: "ctSOAP"},
                {n: "Сезон", v: "ctSEASON"},
                {n: "Серия", v: "ctSERIES"},
                {n: "Трейлер сериала", v: "ctSOAPTRAILER"},
                {n: "Трейлер сезона", v: "ctSEASONTRAILER"}
            ];
            x.show().load(types).setCallback(this, this.on_type_selector_done);
            return this;
        };

        F.prototype.on_type_selector_done = function (sel) {
            if (this._type_dropped === 'ctVIDEO') {
                if (sel === 'ctVIDEO') {
                    this.add_content_id(this._id_dropped);
                } else if (sel === 'ctTRAILER') {
                    this._select_content_trailers();
                }
            } else if (this._type_dropped === 'ctSEASON') {
                if (sel === 'ctSOAP') {
                    this.add_content_id(this._id_dropped);
                } else if (sel === 'ctSEASON') {
                    this._select_season();
                } else if (sel === 'ctSERIES') {
                    this._select_series();
                } else if (sel === 'ctSOAPTRAILER') {
                    this._select_content_trailers();
                } else if (sel === 'ctSEASONTRAILER') {
                    this._select_season_trailer();
                }
            }
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="Селектор серии">
        F.prototype._select_series = function () {
            this.showLoader();
            Y.load('selectors.soap_series_selector')
                    .done(this, this.series_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.series_selector_ready = function (x) {
            x.show().set_allow_multi(true).load(this._id_dropped).setCallback(this, this.on_content_ids_selected);
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="селектор сезона">
        F.prototype._select_season = function () {
            this.showLoader();
            Y.load('selectors.soap_season_selector')
                    .done(this, this.season_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        F.prototype.season_selector_ready = function (x) {
            x.show().set_allow_multi(true).load(this._id_dropped).setCallback(this, this.on_content_ids_selected);
            return this;
        };
        //</editor-fold>





        F.prototype.add_content_id = function (id) {
            this.showLoader();
            id = U.isArray(id) ? id : [id];
            jQuery.getJSON('/admin/MediaContent/API', {action: "lent_add_content", id: id})
                    .done(this.on_response.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="trailer selector">        
        F.prototype._select_content_trailers = function () {
            this.showLoader();
            Y.load('selectors.content_trailer_selector')
                    .done(this, this.on_trailer_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_trailer_selector_ready = function (x) {
            x.show().set_allow_multi(true).load(this._id_dropped).setCallback(this, this.on_content_ids_selected);
            return this;
        };
        F.prototype.on_trailer_selector_ready2 = function (x) {
            x.show().set_allow_multi(true).load(this._id_dropped, 'loader_season').setCallback(this, this.on_content_ids_selected);
            return this;
        };
        F.prototype.on_content_ids_selected = function (sel) {
            sel = U.safeArray(sel);
            var ids = [];
            for (var i = 0; i < sel.length; i++) {
                var id = U.IntMoreOr(U.safeObject(sel[i]).id, 0, null);
                id ? ids.push(id) : 0;
            }
            if (ids.length) {
                this.add_content_id(ids);
            }
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="season trailer selector">
        F.prototype._select_season_trailer = function () {
            this.showLoader();
            Y.load('selectors.season_trailer_selector')
                    .done(this, this.on_trailer_selector_ready2)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };
        //</editor-fold>



        //<editor-fold defaultstate="collapsed" desc="loader">
        F.prototype.load = function () {
            this.showLoader();
            jQuery.getJSON('/admin/MediaContent/API', {action: "ribbon_list"})
                    .done(this.on_response.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.reload = F.prototype.load;
        F.prototype.on_response = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.render(d);
                }
                if (d.status === 'error') {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server response");
        };

        F.prototype.on_network_fail = function (x) {
            this.render_error(U.NEString(x, "network error"));
            return this;
        };

        F.prototype.render_error = function (msg) {
            this.error_message = U.NEString(msg, 'error');
            this.getRole('content').html(Mustache.render(EFO.TemplateManager().get('error', MC), this));
            return this;
        };

        F.prototype.render = function (xo) {
            this.tindex = void(0);
            this.items = U.safeArray(U.safeObject(U.safeObject(xo).ribbon).items);
            this.getRole('content').html(Mustache.render(EFO.TemplateManager().get('items', MC), this, {
                'ctSEASON': EFO.TemplateManager().get('ctSEASON', MC),
                'ctSERIES': EFO.TemplateManager().get('ctSERIES', MC),
                'ctSOAP': EFO.TemplateManager().get('ctSOAP', MC),
                'ctTRAILER': EFO.TemplateManager().get('ctTRAILER', MC),
                'ctTRAILERSEASON': EFO.TemplateManager().get('ctTRAILERSEASON', MC),
                'ctTRAILERSOAP': EFO.TemplateManager().get('ctTRAILERSOAP', MC),
                'ctTRAILERVIDEO': EFO.TemplateManager().get('ctTRAILERVIDEO', MC),
                'ctVIDEO': EFO.TemplateManager().get('ctVIDEO', MC),
                'ctBANNER': EFO.TemplateManager().get('ctBANNER', MC),
                'ctCOLLECTION': EFO.TemplateManager().get('ctCOLLECTION', MC),
                'ctGIF': EFO.TemplateManager().get('ctGIF', MC),
                'ctTEXT': EFO.TemplateManager().get('ctTEXT', MC),
                'item': EFO.TemplateManager().get('item', MC)
            }));
            return this;
        };


        F.prototype.init_renderers = function () {
            this.is_trailer = (function (x) {
                return 'ctTRAILER' === x.content_type;
            }).bindToObjectWParam(this);

            this.is_video = (function (x) {
                return 'ctVIDEO' === x.content_type;
            }).bindToObjectWParam(this);
            this.is_soap = (function (x) {
                return 'ctSEASON' === x.content_type;
            }).bindToObjectWParam(this);
            this.is_season = (function (x) {
                return 'ctSEASONSEASON' === x.content_type;
            }).bindToObjectWParam(this);
            this.is_series = (function (x) {
                return 'ctSEASONSERIES' === x.content_type;
            }).bindToObjectWParam(this);
            this.is_banner = (function (x) {
                return 'ctBANNER' === x.content_type;
            }).bindToObjectWParam(this);
            this.is_collection = (function (x) {
                return 'ctCOLLECTION' === x.content_type;
            }).bindToObjectWParam(this);
             this.is_gif = (function (x) {
                return 'ctGIF' === x.content_type;
            }).bindToObjectWParam(this);
            this.is_text = (function (x) {
                return 'ctTEXT' === x.content_type;
            }).bindToObjectWParam(this);

            this.is_target_ctVIDEO = (function (x) {
                return 'ctVIDEO' === x.trailed_content_type;
            }).bindToObjectWParam(this);
            this.is_target_ctSOAP = (function (x) {
                return 'ctSEASON' === x.trailed_content_type;
            }).bindToObjectWParam(this);
            this.is_target_ctSEASON = (function (x) {
                return 'ctSEASONSEASON' === x.trailed_content_type;
            }).bindToObjectWParam(this);
            this.set_index = (function () {
                this.tindex = void(0) === this.tindex ? -1 : this.tindex;
                this.tindex++;
                this.tindex > 6 ? this.tindex = 0 : 0;
            }).bindToObject(this);

            this.get_index = (function () {
                return this.tindex;
            }).bindToObject(this);

            this.get_item_image_url = this._get_item_image_url.bindToObjectWParam(this);
            this.has_image = this._has_image.bindToObjectWParam(this);
        };
        F.prototype._has_image = function (x) {
            var context = U.NEString(x.image_context, null);
            var owner_id = U.NEString(x.image_owner, null);
            var image = U.NEString(x.image, null);
            return context && owner_id && image;
        };
        F.prototype._get_item_image_url = function (x) {
            var context = U.NEString(x.image_context, null);
            var owner_id = U.NEString(x.image_owner, null);
            var image = U.NEString(x.image, null);

            if (!(context && owner_id && image)) {
                context = "fallback";
                owner_id = "1";
                image = "media_lent";
            }
            var spec = 'SW_400CF_1B_ffffff';//PR_sq';
            if (this.tindex === 0 || this.tindex === 5 || this.tindex === 6) {
                spec += 'PR_hposter';
            } else {
                spec += 'PR_sq';
            }
            var image_url = [
                ///media/media_content_poster/33/7ae332ca1f01572c30090cb4e5f52235.SW_250H_250.jpg?acfp=a1586554210076
                "/media", context, owner_id, [image, spec, 'jpg'].join('.')].join('/');
            return image_url;

        };



        //</editor-fold>

        F.prototype.onCommandRemove = function (t) {
            var id = U.IntMoreOr(t.data('id'), 0, null);
            if (id) {
                this.showLoader();
                jQuery.getJSON('/admin/MediaContent/API', {action: "ribbon_remove", id_to_remove: id})
                        .done(this.on_response.bindToObject(this))
                        .fail(this.on_network_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };


        F.prototype.onCommandReload = function () {
            this.reload();
            return this;
        };



        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {

            return this;
        };

        //</editor-fold>        

        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
        };
        Y.reportSuccess(FQCN, F);// конструктор, не инстанс
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