(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(efo_ready);
    function efo_ready() {
        var EFO = window.Eve.EFO, U = EFO.U, MAINURL = '/admin/Catalog/API', Y = null, SI = null;
        var MC = '<?=$this->MC?>';
        function F() {
            return F.is(SI) ? SI : F.is(this) ? this.init() : F.F();
        }
        F.xInheritE(EFO.Widgets.TreeView);

        F.prototype.onBeforeInit = function () {
            SI = this;
            var r = EFO.Widgets.TreeView.prototype.onBeforeInit.apply(this, Array.prototype.slice.call(arguments));
            this.get_directory_droptype = this._get_directory_droptype.bindToObject(this);
            this.get_product_dragtype = this._get_product_dragtype.bindToObject(this);
            return r;
        };

        F.prototype.getCommandButtons = function () {
            return [
                {command: "newGroup", title: "Новая группа", image: MC + "Plus"}
            ];
        };
        //<editor-fold defaultstate="collapsed" desc="new group && edit">
        F.prototype.onCommandNewGroup = function () {
            this._group_id_to_edit = null;
            this.set_child_of = null;
            return this.loadEditor();
        };

        F.prototype.on_editor_ready = function (x) {
            x.show().load(this._group_id_to_edit).setCallback(this, this.reload);
            (this._group_id_to_edit === null && this.set_child_of) ? x.set_predefined_parent(this.set_child_of.id, this.get_name_path(this.set_child_of.key)) : false;
            return this;

        };
        F.prototype.onRequiredComponentFail = function (x) {
            U.TError('component load error');
            return this;
        };
        F.prototype.loadEditor = function () {
            this.showLoader();
            var Y = EFO.Com();
            Y.load('data_editor.catalog_group')
                    .done(this.on_editor_ready.bindToObject(this))
                    .fail(this.onRequiredComponentFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.onMenuCommandEdit = function (x, y, z) {
            if (!U.IntMoreOr(z._contextData.id)) {
                U.TError(MC + ":virtual group");
                return this;
            }
            this._group_id_to_edit = z._contextData.id;
            this.set_child_of = null;
            this.loadEditor();
            return this;
        };
        F.prototype.onMenuCommandAddChild = function (x, y, z) {
            this.set_child_of = z._contextData.source;
            this._group_id_to_edit = null;
            if (!U.IntMoreOr(this.set_child_of.id)) {
                U.TError(MC + ":virtual group");
                this.set_child_of = null;
                return this;
            }
            this.loadEditor();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Ремовер">
        F.prototype.onMenuCommandRemove = function (x, y, z) {
            if (!U.IntMoreOr(z._contextData.id)) {
                U.TError(MC + ":virtual group");
                return this;
            }
            this._group_to_remove = z._contextData.id;
            this.set_child_of = null;
            this._group_id_to_edit = null;
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text("Удалить этот раздел ?<br><b style=\"color:crimson;font-size:.9em\">Все дочерние группы также будут удалены<b><br><b style=\"color:gray;font-size:.9em\">Товары затронуты не будут<b>")
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            this.doRemove();
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не удалять", "Удалить"])
                    .show();
            return this;
        };
        F.prototype.doRemove = function () {
            var id = U.IntMoreOr(this._group_to_remove);
            if (!id) {
                U.TError(MC + ":virtual group");
                return this;
            }
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'remove_catalog', id: id})
                    .done(this.onRemoveResponce.bindToObject(this))
                    .fail(this.onRemoveFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.onRemoveResponce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.setData(d.catalog_tree);
                }
                if (d.status === 'error') {
                    U.TError(d.error_info.message);
                    return this;
                }
            }
            U.TError('invalid server responce');
            return this;
        };

        F.prototype.onRemoveFail = function () {
            U.TError('network error');
            return this;
        };
        //</editor-fold>
        F.prototype.getVirtualBefore = function () {
            return [                
                {id: -606, name: "Все товары", priveleged: 1}
            ];
        };
        F.prototype.getVirtualAfter = function () {
            return [
                {id: -1, name: "Вне групп", priveleged: 1}                
            ];
        };





        F.prototype.getStorageName = function () {
            return 'catalog_page_catalog_tree';
        };




        //<editor-fold defaultstate="collapsed" desc="drag">    
        F.prototype.initDnD = function () {
            if (this.getIsDraggable()) {
                EFO.DnDManager().LEM.On('ON_DRAG_STARTS_CatalogGroup', this, this.on_drag_begin_catalog_group.bindToObject(this));
                EFO.DnDManager().LEM.On('ON_DROPPED_Product', this, this.on_drop_product.bindToObject(this));
                EFO.DnDManager().LEM.On('ABORT_DRAG_Product', this, this.doClearDrag.bindToObject(this));
                EFO.DnDManager().LEM.On('ABORT_DRAG_CatalogGroup', this, this.doClearDrag.bindToObject(this));
                EFO.DnDManager().LEM.On('ABORT_DRAG_Product', this, this.doClearDrag.bindToObject(this));
                EFO.DnDManager().LEM.On('ABORT_DRAG_CatalogGroup', this, this.on_drag_end_catalog_group.bindToObject(this));
                EFO.DnDManager().LEM.On('ON_DRAG_OVER_' + this.get_product_dragtype(), this, this.onDragOverUg.bindToObject(this));
            }
            return EFO.Widgets.TreeView.prototype.initDnD.apply(this, Array.prototype.slice.call(arguments));
        };

        F.prototype.on_drag_begin_catalog_group = function () {
            this.handle.addClass(MC + "DragCatalogOnly");
        };

        F.prototype.on_drag_end_catalog_group = function () {
            this.handle.removeClass(MC + "DragCatalogOnly");
        };

        F.prototype.doClearDrag = function () {
            this.handle.find('.EFODragOver').removeClass('EFODragOver');
            return this;
        };
        F.prototype.getDragType = function () {
            return 'CatalogGroup';
        };
        F.prototype.getDragTarget = function () {
            return 'CatalogGroup,Product';
        };
        F.prototype._get_product_dragtype = function () {
            return 'Product';
        };
        F.prototype._get_directory_droptype = function () {
            return 'CatalogGroup';
        };

        F.prototype.onInternalDrop = function (DM, T) {
            try {
                var target_key = U.NEString(T.data('targetNode'), null);
                var target_mode = U.NEString(T.data('mode'), null);
                target_mode = (target_mode === 'after' || target_mode === 'inside') ? target_mode : null;
                var Target = this.getNodeByKey(target_key);
                var Darggable = this.getNodeByKey(DM._dragging.closest(this.cmClass('NodeElement')).data('key'));
                this.LEM.Run('INTERNAL_DRAG', Darggable, Target);
                this.onDropTreeItem(Darggable, Target, target_mode);
            } catch (e) {
                U.THREAD_ERRO(e);
            }
            return this;
        };
        F.prototype.get_tree_nodes_order_up = function (parent /*{Int}*/, set_x /*{Int}*/, after_y /*{int}*/) {
            var po = this.tree.getNodeByKey("N" + parent);
            if (EFO.Widgets.TreeView.Tree.Node.is(po)) {
                var nodes = po.childs;
                var so = [];
                for (var i = 0; i < nodes.length; i++) {
                    if (nodes[i].id === set_x) {
                        continue;
                    }
                    so.push(nodes[i].id);
                    if (nodes[i].id === after_y) {
                        so.push(set_x);
                    }
                }
                return so;
            } else {

                var nodes = this.tree.root;
                var so = [];
                for (var i = 0; i < nodes.length; i++) {
                    if (nodes[i].id === set_x) {
                        continue;
                    }
                    so.push(nodes[i].id);
                    if (nodes[i].id === after_y) {
                        so.push(set_x);
                    }
                }
                return so;
            }
            return null;
        };
        F.prototype.update_catalog_orders = function (parent, set_x, after_y) {
            var new_order = this.get_tree_nodes_order_up(parent, set_x, after_y);
            this.showLoader();
            jQuery.post('/admin/Catalog/API', {action: "change_nodes_order", new_order: JSON.stringify(new_order)})
                    .done(this.on_load_responce.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this))
                    .fail(this.reload.bindToObject(this));
            return this;
        };

        F.prototype.onDropTreeItem = function (moving_group, target_group, mode) {
            if (!moving_group || !target_group) {
                return;
            }
            if (moving_group.id < 1) {
                U.TError(MC + ':virtual group');
                return;
            }
            var orig_target_group = target_group;//sort!
            if (target_group.id < 1) {
                return this.on_move_group_to_virtual(moving_group, target_group, mode);
            }
            if (mode === 'after') {
                target_group = this.tree.getNodeByKey("N" + target_group.source.parent_id);
            }
            // если группа назначения совпадает с текущим родителем - просто пересортировать
            if (U.IntMoreOr(moving_group.source.parent_id, 0, null) === U.IntMoreOr(target_group ? target_group.source.id : null, 0, null) && mode === "after") {
                return this.update_catalog_orders(target_group ? target_group.id : null, moving_group.id, orig_target_group.id);
            }
            if (target_group) {
                this.do_action_move_group(moving_group, target_group, mode === "after" ? orig_target_group : null);
            } else {
                this.do_action_move_group_to_root(moving_group, mode === "after" ? orig_target_group : null);
            }
            return this;
        };

        F.prototype.do_action_move_group_to_root = function (moving, set_after) {
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text(["Переместить группу \n<b style=\"font-size:.9em;color:gray\">\"",
                        this.get_name_path(moving.source.key),
                        "\"</b>\nв корень?"].join(''))
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            var data = {
                                group: moving.id,
                                apply_sort: set_after ? 1 : 0,
                                new_order: set_after ? this.get_tree_nodes_order_up(null, moving.id, set_after.id) : null
                            };
                            var encoded_data = {action: "move_group_to_root", data: JSON.stringify(data)};
                            jQuery.post('/admin/Catalog/API', encoded_data, null, 'json')
                                    .done(this.on_load_responce.bindToObject(this))
                                    .always(this.hideLoader.bindToObject(this))
                                    .fail(this.reload.bindToObject(this));
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не перемещать", "Переместить"])
                    .show();
            return this;
        };

        F.prototype.do_action_move_group = function (moving, target, set_after) {
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text(["Переместить группу \n<b style=\"font-size:.9em;color:gray\">\"",
                        this.get_name_path(moving.source.key),
                        "\"</b>\nв группу \n<b style=\"font-size:.9em;color:gray\">\"",
                        this.get_name_path(target.source.key),
                        "\"</b>?"].join(''))
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            var data = {
                                group: moving.id,
                                to: target.id,
                                apply_sort: set_after ? 1 : 0,
                                new_order: set_after ? this.get_tree_nodes_order_up(target.id, moving.id, set_after.id) : null
                            };
                            var encoded_data = {action: "move_group_to", data: JSON.stringify(data)};
                            jQuery.post('/admin/Catalog/API', encoded_data, null, 'json')
                                    .done(this.on_load_responce.bindToObject(this))
                                    .always(this.hideLoader.bindToObject(this))
                                    .fail(this.reload.bindToObject(this));
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не перемещать", "Переместить"])
                    .show();
            return this;
        };

        F.prototype.on_move_group_to_virtual = function (moving, target, mode) {
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text(["Переместить группу \n\"<b style=\"font-size:.9em;color:gray\">",
                        this.get_name_path(moving.source.key),
                        "\"</b>\nв корень?"].join(''))
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            jQuery.getJSON('/admin/Catalog/API', {action: "move_group_root", group: moving.id})
                                    .done(this.on_load_responce.bindToObject(this))
                                    .always(this.hideLoader.bindToObject(this))
                                    .fail(this.reload.bindToObject(this));
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Не перемещать", "Переместить"])
                    .show();
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="deprecated">
        F.prototype.onDropTreeItemOld = function (move, target, mode) {
            if (!move || !target) {
                return;
            }
            if (mode === 'after') { // если первый блок, то смещаем на родителя
                target = this.tree.getNodeByKey("N" + target.source.parent_id);
            }
            this._moving_group = U.IntMoreOr(move.source.id, 0, null);
            this._move_group_target = U.IntMoreOr(target ? target.source.id : null, 0, null);

            this._group_to_move_parent = U.IntMoreOr(move.source.parent_id, 0, null);
            this._group_move_to_parent = U.IntMoreOr(_to.source.parent_id, 0, null);

            if (this._group_to_move && this._group_move_to && this._group_move_to_parent === this._group_to_move_parent && mode === 'after') {
                return this.update_catalog_orders(this._group_move_to_parent, this._group_to_move, this._group_move_to);
            }

            if (this._group_move_to && this._group_to_move) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text(["Переместить группу \n<b style=\"font-size:.9em;color:gray\">\"",
                            this.get_name_path(move.source.key),
                            "\"</b>\nв группу \n<b style=\"font-size:.9em;color:gray\">\"",
                            this.get_name_path(_to.source.key),
                            "\"</b>?"].join(''))
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.doMove();
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не перемещать", "Переместить"])
                        .show();
            }
            if (this._group_to_move && !this._group_move_to) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text(["Переместить группу \n\"<b style=\"font-size:.9em;color:gray\">",
                            this.get_name_path(move.source.key),
                            "\"</b>\nв корень?"].join(''))
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                this.doMove_a();
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не перемещать", "Переместить"])
                        .show();
            }
            return this;
        };

        //<editor-fold defaultstate="collapsed" desc="groups dnd ops">
        F.prototype.doMove_a = function () {
            if (!this._group_to_move) {
                U.TError(MC + ":virtual group");
                return this;
            }
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'move_group_root', group: this._group_to_move})
                    .done(this.onMoveResponce.bindToObject(this))
                    .fail(this.onMoveFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.doMove = function () {
            if (!this._group_to_move || !this._group_move_to) {
                U.TError(MC + ":virtual group");
                return this;
            }
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'move_group_to', group: this._group_to_move, to: this._group_move_to})
                    .done(this.onMoveResponce.bindToObject(this))
                    .fail(this.onMoveFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.onMoveResponce = function (x) {
            if (U.isObject(x)) {
                if (x.status === 'ok') {
                    return this.setData(x.catalog_tree);
                }
                if (x.status === 'error') {
                    U.TError(x.error_info.message);
                    return this;
                }
            }
            U.TError("invalid server responce");
            return this;
        };
        F.prototype.onMoveFail = function () {
            U.TError("network error");
            return this;
        };
        //</editor-fold>
        ////</editor-fold>
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Линкеры">
        F.prototype.on_drop_product = function (DM, T, evt) {
            try {
                var Target = this.getNodeByKey(T.closest(this.cmClass('NodeElement')).data('key'));
                var D = U.safeArray(DM._customData.product_ids);
                var from = this.getSelection();
                from = U.isArray(from) && from.length ? U.IntMoreOr(from[0].id, 0, null) : null;
                if (U.isArray(D) && D.length) {
                    if (U.IntMoreOr(Target.id, 0, null)) {
                        evt && evt.shiftKey && from ? this.move_products(D, Target.id, from) : this.link_products(D, Target.id);
                    } else if (U.IntOr(Target.id, null) === -1) {
                        this.unlink_products_all(D);
                    } else if (U.IntOr(Target.id, null) === -606) {
                        this.unlink_products_from(D, from);
                    }
                }
            } catch (e) {
                U.TError(e);
            }
            return this;
        };

        F.prototype.move_products = function (ids, target_id, from_id) {
            var target = this.tree.getNodeByKey("N" + target_id);
            var from = this.tree.getNodeByKey("N" + from_id);
            if (target && from) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text(["Переместить ", ids.length < 2 ? "товар" : ["товары (", ids.length, ")"].join(''),
                            "из группы <br>\"<b style=\"font-size:.9em;color:gray\">",
                            this.get_name_path(from.source.key),
                            "</b>\"<br>", "в группу <br>",
                            "<b style=\"font-size:.9em;color:gray\">\"",
                            this.get_name_path(target.source.key),
                            "\"</b>?"].join(''))
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                var data = {
                                    target: target_id,
                                    from: from_id,
                                    products: ids
                                };
                                var encoded_data = {action: "move_products", data: JSON.stringify(data)};
                                jQuery.post('/admin/Catalog/API', encoded_data, null, 'json')
                                        .done(this.on_products_action_complete.bindToObject(this))
                                        .fail(this.on_products_action_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Не перемещать", "Переместить"])
                        .show();
            }
            return this;
        };

        F.prototype.link_products = function (ids, target_id) {
            var target = this.tree.getNodeByKey("N" + target_id);
            if (target) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text(["Привязать ", ids.length < 2 ? "товар" : ["товары (", ids.length, ")"].join(''), " к группе <br>",
                            "<b style=\"font-size:.9em;color:gray\">\"",
                            this.get_name_path(target.source.key),
                            "\"</b>?"].join(''))
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                var data = {
                                    target: target_id,
                                    products: ids
                                };
                                var encoded_data = {action: "link_products", data: JSON.stringify(data)};
                                jQuery.post('/admin/Catalog/API', encoded_data, null, 'json')
                                        .done(this.on_products_action_complete.bindToObject(this))
                                        .fail(this.on_products_action_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Отмена", "Привязать"])
                        .show();
            }
            return this;
        };

        F.prototype.unlink_products_all = function (ids) {
            EFO.simple_confirm()
                    .set_title("Подтверждение")
                    .set_text(["Отвязать ", ids.length < 2 ? "товар" : ["товары (", ids.length, ")"].join(''), " от всех групп?"].join(''))
                    .set_callback(this, function (confirm, index) {
                        if (U.IntMoreOr(index, 0, 0) === 2) {
                            var data = {
                                products: ids
                            };
                            var encoded_data = {action: "unlink_products", data: JSON.stringify(data)};
                            jQuery.post('/admin/Catalog/API', encoded_data, null, 'json')
                                    .done(this.on_products_action_complete.bindToObject(this))
                                    .fail(this.on_products_action_fail.bindToObject(this))
                                    .always(this.hideLoader.bindToObject(this));
                        }
                    })
                    .set_style("blue")
                    .set_icon("baloon?")
                    .set_buttons(["Отмена", "Отвязать"])
                    .show();
            return this;
        };
        F.prototype.unlink_products_from = function (ids, from_id) {
            var from = this.tree.getNodeByKey("N" + from_id);
            if (from) {
                EFO.simple_confirm()
                        .set_title("Подтверждение")
                        .set_text(["Отвязать ", ids.length < 2 ? "товар" : ["товары (", ids.length, ")"].join(''), " от группы <br>",
                    "\"<b style=\"color:gray;font-size:.9em\">",
                    this.get_name_path(from.source.key),
                    ,"</b>\" ?"].join(''))
                        .set_callback(this, function (confirm, index) {
                            if (U.IntMoreOr(index, 0, 0) === 2) {
                                var data = {
                                    from: from_id,
                                    products: ids
                                };
                                var encoded_data = {action: "unlink_products", data: JSON.stringify(data)};
                                jQuery.post('/admin/Catalog/API', encoded_data, null, 'json')
                                        .done(this.on_products_action_complete.bindToObject(this))
                                        .fail(this.on_products_action_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
                            }
                        })
                        .set_style("blue")
                        .set_icon("baloon?")
                        .set_buttons(["Отмена", "Отвязать"])
                        .show();
            }
            return this;
        };

        F.prototype.linkUsers = function (dt, tg) {
            var self = this;
            jQuery.pi_confirm(T.AHTUNG, [T.YOU_SHURE, T.ACTION_LINK, (dt.length > 1 ? T.MANY : T.ONE), T.LINK_TO, tg.source.path, T.MOVE_END].join(''), "Привязать", "Не привязывать")
                    .done(function () {
                        self._linkUsers(dt, tg);
                    });
            return this;
        };

        F.prototype._linkUsers = function (dt, tg) {

            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'linkToGroup', users: dt, to: tg.id})
                    .done(this.onLinkActionComplete.bindToObject(this))
                    .fail(this.onLinkActionFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.moveUsers = function (dt, tg) {
            var self = this;
            jQuery.pi_confirm(T.AHTUNG, [T.YOU_SHURE, T.ACTION_MOVE, (dt.length > 1 ? T.MANY : T.ONE), T.MOVE_TO, tg.source.path, T.MOVE_END].join(''), "Переместить", "Не перемещать")
                    .done(function () {
                        self._moveUsers(dt, tg);
                    });
            return this;
        };
        F.prototype._moveUsers = function (dt, tg) {
            var from = this.getSelection();
            from = U.isArray(from) && from.length ? U.IntMoreOr(from[0].id) : null;
            if (!from) {
                U.THREAD_ERR(T.ERR_VIRT_GROUP);
                return this;
            }
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'moveToGroup', users: dt, to: tg.id, from: from})
                    .done(this.onLinkActionComplete.bindToObject(this))
                    .fail(this.onLinkActionFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.unlinkUsersCurrent = function (dt) {
            var self = this;
            jQuery.pi_confirm(T.AHTUNG, [T.YOU_SHURE, T.ACTION_UNLINK, (dt.length > 1 ? T.MANY : T.ONE), T.UNLINK_FROM].join(''), "Отвязать", "Не отвязывать")
                    .done(function () {
                        self._unlinkUsersCurrent(dt);
                    });
            return this;
        };
        F.prototype._unlinkUsersCurrent = function (dt) {
            var from = this.getSelection();
            from = U.isArray(from) && from.length ? U.IntMoreOr(from[0].id) : null;
            if (!from) {
                U.THREAD_ERR(T.ERR_VIRT_GROUP);
                return this;
            }
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'unlinkFromGroup', users: dt, from: from})
                    .done(this.onLinkActionComplete.bindToObject(this))
                    .fail(this.onLinkActionFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.unlinkUsersAll = function (dt) {
            var self = this;
            jQuery.pi_confirm(T.AHTUNG, [T.YOU_SHURE, T.ACTION_UNLINK, (dt.length > 1 ? T.MANY : T.ONE), T.UNLINK_FROM_ALL].join(''), "Отвязать", "Не отвязывать")
                    .done(function () {
                        self._unlinkUsersAll(dt);
                    });
            return this;
        };
        F.prototype._unlinkUsersAll = function (dt) {
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'unlinkFromAllGroups', users: dt})
                    .done(this.onLinkActionComplete.bindToObject(this))
                    .fail(this.onLinkActionFail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_products_action_complete = function (x) {
            if (U.isObject(x)) {
                if (x.status === 'ok') {
                    EFO.id_selection_collection().get_selection(MC).reset();
                    return this.reselect();
                }
                if (x.status === 'error') {
                    U.TError(x.error_info.message);
                    return  this;
                }
            }
            U.TError("invalid server responce");
            return  this;
        };

        F.prototype.on_products_action_fail = function () {
            U.TError("network error");
            return  this;
        };

        F.prototype.reselect = function () {
            var from = this.getSelection();
            from = U.isArray(from) && from.length ? from[0] : null;
            if (from) {
                this.setSelection(from.key);
                this.LEM.Run('NODE_SELECTED', this, from);
            }
            return this;
        };
//</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Reloader">
        F.prototype.reload = function () {
            this.showLoader();
            jQuery.getJSON(MAINURL, {action: 'get_tree'})
                    .done(this.on_load_responce.bindToObject(this))
                    .fail(this.on_load_fail.bindToObject(this))
                    .always(this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.setData(d.catalog_tree);
                }
                if (d.status === 'error') {
                    U.TError(U.NEString(U.safeObject(d.error_info).message, 'unknown error'));
                    return this.setData([]);
                }
            }
            U.TError('invalid server responce');
            return this.setData([]);
        };

        F.prototype.on_load_fail = function () {
            U.TError('server communication error');
            return this.setData([]);
        };
        //</editor-fold>

        F.prototype.getElementIconName = function () {
            return MC + 'Folder';
        };

        F.prototype.getElementCss = function (a, b, c, d) {
            if (U.IntMoreOr(this.id, 0, null) < 1 || U.anyBool(this.priveleged, false)) {
                return [MC, "RedIconColor"].join('');
            }
            if (!U.anyBool(this.visible, true)) {
                return [MC, "GrayIconColor"].join('');
            }
        };

        F.prototype.get_template_set = function () {
            return {element: EFO.TemplateManager().get('treenode', MC)};
        };

        F.prototype.get_mc = function () {
            return MC;
        };


        window.Eve[MC + "tree"] = F;

    }
})();
      