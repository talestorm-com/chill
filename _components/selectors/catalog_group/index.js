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
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
//        var SVG = null;
//        /*<?=$this->create_svg('SVG')?>*/
//        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.allow_multi = false;
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
            this._selection = {};
            this.init_tree();
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
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
            return [
                {'command': "cancel", 'text': "Отмена"},
                //{'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Выбрать"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Выбор группы";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function () {
            //keep selection
            this.showLoader();
            jQuery.getJSON('/admin/Info/API', {action: "get_product_groups"})
                    .done(this.on_load_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.tree.set_data(d.tree);
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };
        F.prototype.on_network_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this.hide().clear();
        };


        //</editor-fold>                
        F.prototype.init_tree = function () {
            this.delegate = EFO.Widgets.DataTree.TreeDelegate({
                use_context_menu: function () {
                    return false;
                },
                get_mc: function () {
                    return [MC, "tree"].join('');
                },
                get_toolbar_buttons: function () {
                    return [];
                },
                can_display_node_non_bind: this.can_display_node.bindToObjectWParam(this),
                on_node_click_non_bind: this.on_node_click.bindToObjectWParam(this),
                get_node_custom_css_class_non_bind: this.get_node_custom_css_class.bindToObjectWParam(this),
                allow_drag_nodes: function () {
                    return false;
                },
                create_data_instance: function () {
                    return CTree();
                }
            });
            this.tree = EFO.Widgets.DataTree.TreeView(this.delegate);
            this.tree.set_container(this.getRole('tree'));
            return this;
        };

        F.prototype.get_node_custom_css_class = function (d, t, n) {
            var classes = [];
            var key = n.key;
            if (U.NEString(this._selection[key], null) === key) {
                classes.push(MC + "selected");
            }
            if (!n.visible) {
                classes.push(MC + "NodeDisabled");
            }
            return classes.join(' ');
        };

        F.prototype.node_match_deep = function (rx, node) {
            var result = rx.test(node.name);
            if (!result) {
                for (var i = 0; i < node.childs.length; i++) {
                    if (result) {
                        break;
                    } else {
                        result = result || this.node_match_deep(rx, node.childs[i]);
                    }
                }
            }
            return result;
        };

        F.prototype.can_display_node = function (d, t, n) {
            if (n.id === this._exclude) {
                return false;
            }
            var tx = U.NEString(t.get_search_text(), null);
            var rx = new RegExp(["^.*", tx, ".*$"].join(''), "i");
            if (tx) {
                return this.node_match_deep(rx, n);
            }
            return true;
        };

        F.prototype.on_node_click = function (d, t, nk, nh, e) {//delegate,tree,node key,node handle,event            
            var node = t.data.get_node_by_key(nk);
            if (node) {
                if (this.allow_multi) {
                    if (node.key === this._selection[node.key]) {
                        jQuery("#" + node.node_html_id).removeClass(MC + "selected");
                        delete(this._selection[node.key]);
                    } else {                        
                        jQuery("#" + node.node_html_id).addClass(MC + "selected");
                        this._selection[node.key] = node.key;
                    }
                } else {
                    if (node.key === this._selection[node.key]) {
                        jQuery("#" + node.node_html_id).removeClass(MC + "selected");
                        delete(this._selection[node.key]);
                    } else {
                        this._selection = {};
                        this.handle.find("." + MC + "selected").removeClass(MC + "selected");
                        jQuery("#" + node.node_html_id).addClass(MC + "selected");
                        this._selection[node.key] = node.key;
                    }
                }
            }
            return this;
        };

        F.prototype.exclude_subtree = function (x) {
            this._exclude = U.IntMoreOr(x, 0, null);
            this.tree.render();
            return this;
        };


        F.prototype.set_allow_multi = function (x) {
            this.allow_multi = U.anyBool(x, false);
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this._exclude = null;
            this.allow_multi = false;
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
        //



        //<editor-fold defaultstate="collapsed" desc="save">  

        F.prototype.save = function (keep_open) {
            var selection = [];
            for (var k in this._selection) {
                if (this._selection.hasOwnProperty(k)) {
                    if (this._selection[k] === k) {
                        var node = this.tree.data.get_node_by_key(k);
                        if (node) {
                            selection.push(node);
                        }
                    }
                }
            }
            if (!selection.length) {
                U.TError("nothing selected");
                return this;
            }

            this.runCallback(selection);
            this.hide().clear();
            return this;
        };
        //</editor-fold>        
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="tree overrides">

        function CTree() {
            return (CTree.is(this) ? this.init : CTree.F).apply(this, APS.call(arguments));
        }

        CTree.xInheritE(EFO.Widgets.DataTree.Tree);
        var CTP = CTree.prototype;
        CTP._node_instance = function () {
            return CNode();
        };

        function CNode() {
            return (CNode.is(this) ? this.init : CNode.F).apply(this, APS.call(arguments));
        }
        CNode.xInheritE(EFO.Widgets.DataTree.Node);
        var CNP = CNode.prototype;
        CNP.guid = null;
        CNP.alias = null;
        CNP.visible = null;
        CNP.default_image = null;
        CNP.on_import_descedants = function (data, tree) {
            this.guid = U.NEString(data.guid, null);
            this.alias = U.NEString(data.alias, null);
            this.visible = U.anyBool(data.visible, true);
            this.default_image = U.NEString(data.default_image, null);
            return this;
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