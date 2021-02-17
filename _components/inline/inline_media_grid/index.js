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
            e_instance_id ? 0 : U.Error("grid requires instance id as parameter");
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
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_ctSEASON', this, this.drag_starts);
            EFO.DnDManager().LEM.on('ABORT_DRAG_ctSEASON', this, this.drag_abort);
            EFO.DnDManager().LEM.on('ON_DROPPED_ctSEASON', this, this.drop);
            return this;
        };

        F.prototype.drag_starts = function () {
            this.handle.addClass(MC + "DRAGACTIVE");
            return this;
        };

        F.prototype.drag_abort = function () {
            this.handle.removeClass(MC + "DRAGACTIVE");
            this.handle.find('.EFODragOver').removeClass('EFODragOver');
            return this;
        };


        F.prototype.make_empty_position = function (id) {
            var sa = U.safeArray(this.items);
            for (var i = 0; i < sa.length; i++) {
                if (!sa[i].empty) {
                    if (U.IntMoreOr(sa[i].video_id, 0, null) === id) {
                        sa[i].video_id = null;
                        sa[i].video_data = null;
                        sa[i].empty = true;
                        this.update_element(sa[i]);
                    }
                }
            }
            return this;
        };

        F.prototype.clean_cell = function (pos) {
            var sa = U.safeArray(this.items);
            if (sa[pos]) {
                if (!sa[pos].empty) {

                    sa[pos].video_id = null;
                    sa[pos].video_data = null;
                    sa[pos].empty = true;
                    this.update_element(sa[pos]);

                }
            }
            return this;
        }

        F.prototype.onCommandClean_cell = function (x) {
            var pos = U.IntMoreOr(x.data('position'), -1, null);
            if (pos !== null) {
                this.clean_cell(pos);
            }
        }

        F.prototype.update_element = function (render_data) {
            this._data_to_render = U.safeObject(render_data);
            var html = Mustache.render(EFO.TemplateManager().get('one', MC), this);
            var node = jQuery(html);
            var rp_node = this.getRole('content').find('[data-position=' + [render_data.index].join('') + ']');
            rp_node.html(node.html());
            return this;
        };

        F.prototype.drop = function (x, y, z) {
            var id = U.IntMoreOr(x._dragging.data('id'), 0, null);
            var drop_position = U.IntMoreOr(y.data('position'), -1, null);
            if (id && drop_position !== null) {
                this.make_empty_position(id);
                this.items[drop_position].video_id = id;
                this.load_season_info(drop_position);
            }
            return this;
        };

        F.prototype.load_season_info = function (pos) {
            var video_id = this.items[pos].video_id;
            this.getRole('content').find('[data-position=' + [pos].join('') + ']').addClass(MC + "loading");
            var self = this;
            jQuery.getJSON('/admin/MediaGrid/API', {action: "grid_season_info", id: video_id})
                    .done(function (d) {
                        if (d.status === 'ok') {
                            var data = d.data;
                            self.items[pos].video_data = data;
                            self.items[pos].video_id = U.IntMoreOr(data.id, 0, null);
                            self.items[pos].empty = false;
                            self.update_element(self.items[pos]);
                            self.getRole('content').find('[data-position=' + [pos].join('') + ']').removeClass(MC + "loading");
                        }
                    });
            return this;
        };



        //<editor-fold defaultstate="collapsed" desc="loader">
        F.prototype.load = function () {
            this.showLoader();
            jQuery.getJSON('/admin/MediaGrid/API', {action: "grid"})
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

        F.prototype.render = function (data) {
            var total = Math.ceil(U.IntMoreOr(data.total, 4, 4) / 4) * 4;
            var avoc = {};
            var sa = U.safeArray(data.grid);
            for (var i = 0; i < sa.length; i++) {
                var pos = ['A', sa[i].position].join('');
                avoc[pos] = sa[i];
            }
            var dr = [];
            for (var i = 0; i < total; i++) {
                var pos = ['A', i].join('');
                if (avoc[pos]) {
                    dr.push({index: i, empty: false, video_id: avoc[pos].id, video_data: avoc[pos]});
                } else {
                    dr.push({index: i, empty: true});
                }
            }
            this.items = dr;
            this.getRole('content').html(Mustache.render(EFO.TemplateManager().get('items', MC), this));
            return this;
        };


        F.prototype.init_renderers = function () {
            this.display_grid_index = function () {
                return this.index + 1;
            };
            this.is_soap = (function (x) {
                return 'ctSEASON' === x.content_type;
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
            var context = 'media_content_poster';
            var owner_id = U.NEString(x.id, null);
            var image = U.NEString(x.default_poster, null);

            if (!(context && owner_id && image)) {
                context = "fallback";
                owner_id = "1";
                image = "media_lent";
            }
            var spec = 'SW_400CF_1B_ffffff';//PR_sq';           
            var image_url = [
                ///media/media_content_poster/33/7ae332ca1f01572c30090cb4e5f52235.SW_250H_250.jpg?acfp=a1586554210076
                "/media", context, owner_id, [image, spec, 'jpg'].join('.')].join('/');
            return image_url;

        };

        F.prototype.save_data = function (cbx) {
            var sa = [].concat(this.items);
            //проверяем что нет дыр
            var emptyblock = false;
            var ss = [];
            for (var i = 0; i < sa.length; i++) {
                if (!sa[i].empty && emptyblock) {
                    U.TError("Дырявая сетка!");
                    return;
                }
                if (sa[i].empty) {
                    emptyblock = true;
                } else {
                    ss.push({pos: i, id: sa[i].video_id});
                }
            }
            this.showLoader();
            var self = this;
            jQuery.post('/admin/MediaGrid/API', {action: "post_grid", posx: JSON.stringify(ss)}, null, 'json')
                    .done(function (d) {
                        self.on_response(d);
                    })
                    .always(function () {
                        self.hideLoader();
                        cbx();
                    });
            return this;
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