(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.js('/assets/js/types/menu_node.js')
    ];
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
        var SVG = null;
        /*<?=$this->create_svg('SVG')?>*/
        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable'];
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
            this.init_tree();
            var fs = U.FloatMoreOr(localStorage.getItem(MC + "zf_size"), 0.3, 1.0);
            this.getRole('menu_container').find('.EFODataTreeViewTreeContentInner').css("font-size", fs.toFixed(2) + "em");
            return this;
        };
        F.prototype.init_tree = function () {
            this.tree_delegate = EFO.Widgets.DataTree.TreeDelegate({
                create_data_instance: function () {
                    return EFO.Widgets.DataTree.MenuTree();
                },
                get_mc: function () {
                    return MC + "Tree";
                },
                get_toolbar_buttons: function () {
                    return [
                        {
                            title: "-",
                            icon: MC + "_icon_minus_r",
                            command: "zoom_out"
                        },
                        {
                            title: "+",
                            icon: MC + "_icon_plus_r",
                            command: "zoom_in"
                        },
                        {
                            title: "Добавить узел",
                            icon: "EFODataTreeView_icon_plus",
                            tree_command: "add_root_node"
                        }
                    ];
                },
                can_display_node_non_bind: this.on_node_filter.bindToObject(this),
                on_node_click_non_bind: this.on_tree_node_click.bindToObject(this),
                on_new_node_non_bind: this.on_node_add.bindToObject(this)
            });
            this.tree = EFO.Widgets.DataTree.TreeView(this.tree_delegate);
            this.tree.set_container(this.getRole('tree'));
            this.tree.set_data([]);
            return this;
        };

        F.prototype.on_node_add = function (t, n) {
            this.on_tree_node_click(null, n.key, null);
            return this;
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

        F.prototype.on_node_filter = function (t, n) {
            var tx = U.NEString(t.get_search_text(), null);
            var rx = new RegExp(["^.*", tx, ".*$"].join(''), "i");
            if (tx) {
                return this.node_match_deep(rx, n);
            }
            return true;
        };

        F.prototype.on_tree_node_click = function (t, n, e) {
            this._node_to_edit = n;
            this.showLoader();
            Y.load('data_editor.menu_item')
                    .done(this, this.on_editor_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_editor_ready = function (x) {
            x.show().load(this.tree.data.get_node_by_key(this._node_to_edit)).setCallback(this, this.on_editor_finished);
            return this;
        };
        F.prototype.on_editor_finished = function () {
            return this.tree.render();
        };

        F.prototype.onCommandZoom_out = function () {
            var fs = U.FloatMoreOr(localStorage.getItem(MC + "zf_size"), 0.3, 1.0);
            fs = U.FloatMoreOr(fs - .1, 0.3, fs);
            localStorage.setItem(MC + "zf_size", fs);
            this.getRole('menu_container').find('.EFODataTreeViewTreeContentInner').css("font-size", fs.toFixed(2) + "em");
        };
        F.prototype.onCommandZoom_in = function () {
            var fs = U.FloatMoreOr(localStorage.getItem(MC + "zf_size"), 0.3, 1.0);
            fs = U.FloatMoreOr(fs + .1, 0.3, fs);
            localStorage.setItem(MC + "zf_size", fs);
            this.getRole('menu_container').find('.EFODataTreeViewTreeContentInner').css("font-size", fs.toFixed(2) + "em");
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
                {'command': "apply", 'text': "Применить"},
                {'command': "save", 'text': "Сохранить и закрыть"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Редактирование меню";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">
        /**
         *          
         * @returns {F}
         */
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/Navigation/API', {action: "get_menu", id: id})
                        .done(this.on_data_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };
        F.prototype.on_data_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_data_success(U.safeObject(d.data));
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };
        F.prototype.on_network_fail_fatal = function () {
            return this.on_network_fail.apply(this, APS.call(arguments)).hide().clear();
        };
        F.prototype.on_network_fail = function (m) {
            U.TError(m);
            return this;
        };
        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            return this;
        };
        //</editor-fold>                
        //

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
        //<editor-fold defaultstate="collapsed" desc="monitors">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">
        //<editor-fold defaultstate="collapsed" desc="footer commands">

        F.prototype.onCommandCancel = function () {
            return this.hide().clear();
        };
        F.prototype.onCommandApply = function () {
            this.save(true);
            return this;
        };
        F.prototype.onCommandSave = function () {
            this.save(false);
            return this;
        };
        //</editor-fold>
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };

        F.prototype._get_field_tree = function () {
            return this.tree.get_data();
        };

        F.prototype._set_field_tree = function (c) {
            var data = U.safeArray(c.tree);
            this.tree.set_data(data);
            return this;
        };
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open, true);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('user'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
            this.showLoader();
            jQuery.post('/admin/Navigation/API', {action: 'post_menu', data: JSON.stringify(data)})
                    .done(this.on_post_result.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.on_post_result = function (d) {

            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.data));
                    this.runCallback();
                    if (!this._keep_open) {
                        this.hide().clear();
                    }
                    return this;
                }
                if (d.status === "error") {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };
        //</editor-fold>        
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