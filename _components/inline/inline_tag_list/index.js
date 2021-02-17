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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.command_interface = null;
        F.prototype.ci_prefix = null;
        F.prototype.items = null;
        F.prototype.custom_css = null;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">   '
        F.prototype.callback_method = function (method_name) {
            var cf_name = this.ci_prefix ? ["inline_taglist", method_name, this.ci_prefix].join('_') : method_name;
            if (U.isCallable(this.command_interface[cf_name])) {
                return this.command_interface[cf_name].bindToObject(this.command_interface);
            }
            return function () {};
        };

        F.prototype.onBeforeInit = function (command_interface, ci_prefix) {
            this.command_interface = U.isObject(command_interface) ? command_interface : null;
            this.command_interface ? 0 : U.Error("inline tag editor editor requires command_interface as valid object");
            this.ci_prefix = U.NEString(ci_prefix, null);
            this.instance_id = [MC, U.UUID().replace(/-/g, '')].join('');
            this.get_instance_id = this._get_instance_id.bindToObject(this);
            this.custom_css = U.NEString(this.callback_method("get_custom_css_class")(), '');
            return PARP.onBeforeInit.apply(this, APS.call(arguments));
        };
        F.prototype.onInit = function () {
            this.items = [];
            this.init_dnd();
            return PARP.onInit.apply(this, APS.call(arguments));
        };

        F.prototype.init_dnd = function () {
            EFO.DnDManager().LEM.on(["ON_DRAG_STARTS_TAGLISTTAG_", this.instance_id].join(''), this, this.on_drag_began);
            EFO.DnDManager().LEM.on(["ABORT_DRAG_TAGLISTTAG_", this.instance_id].join(''), this, this.on_drag_stop);
            EFO.DnDManager().LEM.on(["ON_DROPPED_TAGLISTTAG_", this.instance_id].join(''), this, this.on_drag_dropped);
            return this;
        };
        F.prototype.on_drag_began = function (a, b, c, d) {
            this.handle.addClass(MC + 'DraggableDragging');
            return this;
        };

        F.prototype.on_drag_stop = function (a, b, c, d) {
            this.handle.removeClass(MC + 'DraggableDragging');
            this.handle.find('.EFODragOver').removeClass('EFODragOver');
            return this;
        };

        F.prototype.on_drag_dropped = function (a, b, c, d) {
            a._dragging.insertAfter(b.closest('.EFODraggable'));
            this.update_order_data();
            return this;
        };

        F.prototype.update_order_data = function () {
            var keys = {};
            var c = 0;
            this.getRole('taglist-items').find('.' + MC + 'TaglistTagWrapper').each(function () {
                var p = jQuery(this);
                var id = U.NEString(p.data('id'), null);
                if (id) {
                    var key = ["P", id].join('');
                    keys[key] = c;
                    c++;
                }
            });
            for (var i = 0; i < this.items.length; i++) {
                var key = ["P", this.items[i].id].join('');
                this.items[i].sort = U.IntMoreOr(keys[key], -1, 0);
            }
            this.sort_items();
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
        F.prototype.set_title = function () {
            this.getRole('taglist-title-text').html(U.NEString(this.callback_method("get_title")(), "список"));
        };

        F.prototype.create_item = function (xo) {
            xo = U.safeObject(xo);
            var pn = U.NEString(xo.id, null);
            var pv = U.NEString(xo.text, null);
            var ps = U.IntOr(xo.sort, 0);
            if (pn && pv) {
                return {id: pn, text: pv, sort: ps};
            } else {
                var xx = this.callback_method("on_item_convert")(xo);
                return xx ? xx : null;
            }
            return null;
        };
        F.prototype.set_data = function (x) {
            this.set_title();
            x = U.safeArray(x);
            var tags = [];
            for (var i = 0; i < x.length; i++) {
                if (U.isObject(x[i])) {
                    var tx = this.create_item(x[i]);
                    if (tx) {
                        tags.push(tx);
                    }
                }
            }

            this.items = tags;
            this.sort_items();
            this.render_items();
            return this;
        };
        F.prototype.sort_items = function () {
            this.items.sort(function (a, b) {
                var r = a.sort - b.sort;
                if (r === 0) {
                    r = a.text < b.text ? -1 : (a.text > b.text ? 1 : 0);
                }
                return r;
            });
            return this;
        };

        F.prototype.get_item_by_id = function (x) {
            x = U.NEString(x, null);
            if (x) {
                var sa = U.safeArray(this.items);
                for (var i = 0; i < sa.length; i++) {
                    if (U.NEString(sa[i].id, null) === x) {
                        return sa[i];
                    }
                }
            }
            return null;
        };

        F.prototype.add_item = function (x) {
            var xi = this.create_item(x);
            if (xi) {
                var xe = this.get_item_by_id(xi.id);
                if (!xe) {
                    this.items.push(xi);
                }
            }
            return this;
        };

        F.prototype.add_items = function (ita) {
            ita = U.safeArray(ita);
            for (var i = 0; i < ita.length; i++) {
                if (U.isObject(ita[i])) {
                    this.add_item(ita[i]);
                }
            }
            this.sort_items();
            return this;
        };

        F.prototype.render_items = function () {
            this.getRole('taglist-items').html(Mustache.render(EFO.TemplateManager().get('tag', MC), this));
            return this;
        };

        F.prototype.get_data = function () {            
            this.update_order_data();
            return [].concat(this.items);
        };

        F.prototype.onCommandTaglist_add = function () {
            this.callback_method("add")();
            return this;
        };
        F.prototype.onCommandTaglist_remove_tag = function (t) {
            var uid = U.NEString(t.data('id'), null);
            if (uid) {
                var s = [].concat(this.items);
                var ns = [];
                for (var i = 0; i < s.length; i++) {
                    if (s[i].id === uid) {
                        continue;
                    }
                    ns.push(s[i]);
                }
            }
            this.items = ns;
            this.render_items();
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.set_data([]);
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