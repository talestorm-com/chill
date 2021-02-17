(function () {
    (function () {
        /*<?=$this->include_lib('color_list')?>*/
    })();
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
            return  (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        F.prototype.owner_id = null;
        F.prototype.color_list = null;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">        
        F.prototype.onInit = function () {
            PARP.onInit.apply(this, APS.call(arguments));
            this.get_color_url = this._get_color_url.bindToObjectWParam(this);
            this.reset_row_index = this._reset_row_index.bindToObject(this);
            this.update_row_index = this._update_row_index.bindToObject(this);
            this.row_index = this._row_index.bindToObject(this);
            this.instance_id = U.UID();
            var self = this;
            this.handle.on('input', 'input[type=color]', function (e) {
                self.onMonitorColor_select(jQuery(this), e);
            });
            this.handle.get(0).addEventListener('error', this.on_image_load_error.bindToObjectWParam(this), true);
            this.restore_size();
            this.init_dnd();
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

        F.prototype.on_image_load_error = function (cx, e) {
            var img = e.target;
            if (img && U.NEString(img.tagName, null) === 'IMG') {
                img.style.display = 'none';
                jQuery(img).closest('div').find('.' + MC + 'ImageFallback').show();
            }
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="dnd">
        F.prototype.init_dnd = function () {
            EFO.DnDManager().LEM.on('ON_DRAG_STARTS_color_' + this.instance_id, this, this.on_drag_starts);
            EFO.DnDManager().LEM.on('ABORT_DRAG_color_' + this.instance_id, this, this.on_abort_drag);
            EFO.DnDManager().LEM.on('ON_DROPPED_color_' + this.instance_id, this, this.on_drop);
            return this;
        };
        F.prototype.on_drag_starts = function () {
            this.handle.addClass(MC + 'DRAGGING');
            return this;
        };

        F.prototype.on_abort_drag = function () {
            this.handle.removeClass(MC + 'DRAGGING');
            this.handle.find('.EFODragOver').removeClass('.EFODragOver');
            return this;
        };

        F.prototype.on_drop = function (DM, T, E) {
            try {
                var draggable = DM._dragging;
                if (draggable) {
                    var draggable_id = U.NEString(draggable.data('uid'), null);
                    if (draggable_id) {
                        var drop_id = U.NEString(T.parent().data('uid'), null);
                        if (drop_id) {
                            if (drop_id !== draggable_id) {
                                draggable.insertAfter(T.parent());
                                var sorts = [];
                                var images = this.handle.find(['.', MC, 'OneColorItem'].join(''));
                                images.each(function () {
                                    var i = U.NEString(jQuery(this).data('uid'), null);
                                    i ? sorts.push(i) : i;
                                });
                                this.color_list.set_sorting(sorts);
                                this.reload();
                            }
                        }
                    }
                }
            } catch (eee) {
                U.TError(eee);
            }
            return this;
        };
        //</editor-fold>
        //</editor-fold>   
        F.prototype._get_color_url = function (x) {
            var t = (new Date()).getTime();
            return ["/media/_color/", x.guid, '.SW_250H_125CF_1', '.jpg?acfp=a', t].join('');
        };

        //<editor-fold defaultstate="collapsed" desc="deprecated">
        F.prototype.on_file_changed = function (n, e) {
            var jn = jQuery(n);
            var val = U.NEString(jn.val(), null);
            if (val) {
                var t = new Date();
                this._callback_name = [MC, t.getTime(), 'callback'].join('_');
                this._target_id = [MC, t.getTime(), 'target'].join('');
                window[this._callback_name] = this.on_loader_callback.bindToObject(this);
                var upload_form = jQuery(Mustache.render(EFO.TemplateManager().get('upload_form', MC), this));
                this.showLoader();
                upload_form.find('form').append(jn);
                upload_form.find('frame').onerror = this.on_loader_error.bindToObject(this);
                upload_form.appendTo(this.getRole('ops'));
                this.upload_form = upload_form;
                upload_form.find('form').submit();
            }
            return this;
        };

        F.prototype.restore_upload_controls = function () {
            if (this._callback_name) {
                if (U.isCallable(window[this._callback_name])) {
                    delete(window[this._callback_name]);
                }
            }
            this._target_id = null;
            if (this.upload_form) {
                this.upload_form.find('input[type=file]').val('');
                this.upload_form.find('input[type=file]').appendTo(this.getRole('upload_holder'));
                this.upload_form.remove();
                this.upload_form = null;
            }
            return this;
        };

        F.prototype.on_loader_callback = function (log, error, list) {
            this.hideLoader();
            this.restore_upload_controls();
            log = U.safeArray(JSON.parse(log));
            error = U.safeObject(JSON.parse(error));
            list = U.safeArray(JSON.parse(list));
            if (U.NEString(error.message, null)) {
                console.log(error);
                U.TError(error.message);
                return;
            }
            if (list.length) {
                this.render_images(U.safeArray(list)).enable_controls();
            }
            if (log.length) {
                var text = [];
                for (var i = 0; i < log.length; i++) {
                    var t = U.isObject(log[i]) ? log[i] : null;
                    if (t && U.NEString(t.t, null) && U.NEString(t.n, null)) {
                        var message = U.NEString(EFO.Translator().T(t.t).replace(/%s/ig, t.n), null);
                        if (message) {
                            text.push(message);
                        }
                    }
                }
                if (text.length) {
                    this._err_tx = EFO.Translator().T('errors while uploading');
                    this._err_list = text;
                    var html = Mustache.render(EFO.TemplateManager().get('warning', MC), this);
                    window.Eve.EFO.simple_confirm().set_icon("!").set_close_btn(true)
                            .set_text(html.replace(/\n/g, '')).set_style("blue").set_buttons(["Ok"])
                            .set_title(EFO.Translator().T("warning_title"))
                            .show();
                }

            }
        };

        F.prototype.on_loader_error = function () {
            this.restore_upload_controls();
            this.hideLoader();
            U.TError('image upload error');
        };
        //</editor-fold>

        //установка параметров и перезагрузка - вручную с хоста 
        F.prototype.set_colors = function (owner_id, color_array) {
            this.import_color_array(color_array);
            this.owner_id = U.NEString(owner_id, null);
            this.reload();
            return this;
        };

        F.prototype.import_color_array = function (x) {
            this.color_list = E.color_editor_color_list();
            this.color_list.import(x);
            return this;
        };


        F.prototype.trigger_change = function () {
            this.LEM.run('CHANGE', this);
            return this;
        };

        F.prototype.reload = function () {
            if (this.color_list && this.color_list.is_empty()) {
                this.render_empty();
            } else {
                this.render_colors();
            }
            this.trigger_change();
            return this;
        };

        F.prototype.render_empty = function () {
            this.getRole('list').html(Mustache.render(EFO.TemplateManager().get('empty', MC), this));
            return this;
        };

        F.prototype.render_colors = function () {
            this.getRole('list').html(Mustache.render(EFO.TemplateManager().get('list', MC), this, {
                one_color: EFO.TemplateManager().get('one_color', MC),
                'color_tool_panel': EFO.TemplateManager().get('control', MC)
            }));
            return this;
        };

        F.prototype.on_network_fail = function () {
            this.show_error("network error");
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {

                    return this.render_images(U.safeArray(d.list)).enable_controls();
                }
                if (d.status === 'error') {
                    return this.show_error(d.error_info.message);
                }
            }
            this.show_error("invalid server responce");
        };

        F.prototype.get_translated_empty_text = function () {
            return EFO.Translator().T(MC + ":empty");
        };
        F.prototype.get_translated_link_text = function () {
            return EFO.Translator().T(MC + ":empty_link_text");
        };

        F.prototype.onCommandAdd_color = function () {
            this.color_list.add_color(100);
            this.reload();
            return this;
        };

        F.prototype._reset_row_index = function () {
            this.row__index = 0;
        };
        F.prototype._update_row_index = function () {
            this.row__index++;
        };
        F.prototype._row_index = function () {
            return this.row__index;
        };

        //<editor-fold defaultstate="collapsed" desc="color monitors">
        F.prototype.onMonitorColor_name = function (t) {
            var uid = U.NEString(t.data('color'), null);
            if (uid) {
                var new_value = U.NEString(t.val(), "новый цвет");
                var color = this.color_list.get_by_uid(uid);
                if (color) {
                    color.name = new_value;
                    t.val(color.name);
                }
            }
            return this.trigger_change();
        };
        F.prototype.onMonitorColor_exchange = function (t) {
            var uid = U.NEString(t.data('color'), null);
            if (uid) {
                var new_value = U.NEString(t.val(), "");
                var color = this.color_list.get_by_uid(uid);
                if (color) {
                    color.exchange_uid = new_value;
                    t.val(color.exchange_uid);
                }
            }
            return this;
        };

        F.prototype.onMonitorColor_select = function (t) {
            var uid = U.NEString(t.data('color'), null);
            if (uid) {
                var color = this.color_list.get_by_uid(uid);
                if (color) {
                    color.html_color = t.val();
                    t.closest('.' + MC + 'OneColorColorDisplay').css('background', color.html_color);
                }
            }
            return this;
        };

        F.prototype.onMonitorColor_sort = function (t) {
            var uid = U.NEString(t.data('color'), null);
            if (uid) {
                var new_value = U.IntOr(t.val(), 0);
                var color = this.color_list.get_by_uid(uid);
                if (color) {
                    color.sort = new_value;
                    t.val(color.sort);
                }
            }
            return this;
        };

        F.prototype.onMonitorFile = function (t) {
            if (U.NEString(t.val(), null)) {
                var uid = U.NEString(t.data('color'), null);
                if (uid) {
                    this.uploading_color = uid;
                    this.do_upload_color(t);
                }
            }
            return this;
        };
        //<editor-fold defaultstate="collapsed" desc="upload">
        F.prototype.do_upload_color = function (uce) {
            //сохранять его не надо - потом ве равно ререндеринг?
            this.showLoader();
            var FD = new FormData();
            FD.append('color_id', this.uploading_color);
            FD.append('file', uce.get(0).files[0]);
            FD.append('action', 'upload_color');

            jQuery.ajax({
                url: '/MediaAPI/ImageFly/API',
                data: FD,
                processData: false,
                type: 'POST',
                contentType: false, // 'multipart/form-data; charset=utf-8; boundary='+Math.random().toString().substr(2),                
                dataType: 'json'
            })

                    //jQuery.post('/MediaAPI/ImageFly/API', FD, null, 'json')
                    .done(this, this.on_color_uploaded.bindToObject(this))
                    .fail(this, this.on_color_upload_error.bindToObject(this))
                    .always(this, this.hideLoader.bindToObject(this));
            return this;
        };

        F.prototype.on_color_uploaded = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.reload();
                }
                if (d.status === 'error') {
                    return this.on_color_upload_error(d.error_info.message);
                }
            }
            return this.on_color_upload_error("invalid server responce");
        };
        F.prototype.on_color_upload_error = function (d) {
            d = U.NEString(d, "network error");
            U.TError(d);
            return this;
        };
        //</editor-fold>
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.color_list = null;
            return this.reload();

        };

        //</editor-fold>                  
        //<editor-fold defaultstate="collapsed" desc="Комманды">   
        //<editor-fold defaultstate="collapsed" desc="info">
        F.prototype.onCommandImage_control_info = function (j) {
            var context = U.NEString(j.data('context'), null);
            var id = U.NEString(j.data('owner_id'), null);
            var image = U.NEString(j.data('image'), null);
            if (context && id && image) {
                this._data_for_title_editor = {context: context, id: id, image: image};
                this.showLoader();
                Y.load('media.title_editor')
                        .done(this, this.on_title_editor_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            } else {
                this._data_for_title_editor = null;
            }
            return this;
        };

        F.prototype.on_title_editor_ready = function (x) {
            x.show().load(this._data_for_title_editor.context, this._data_for_title_editor.id, this._data_for_title_editor.image)
                    .setCallback(this, this.reload);
            return this;// callback
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="remover">
        F.prototype.onCommandColor_control_remove = function (j) {
            var image = U.NEString(j.data('color'), null);
            if (image) {
                EFO.simple_confirm().set_icon("?").set_close_btn(true)
                        .set_text(EFO.Translator().T(MC + ":remove_color_confirm"))
                        .set_style("blue").set_buttons(["Отмена", "Ok"])
                        .set_title(EFO.Translator().T(MC + "remove_color_confirm_title"))
                        .set_callback(this, function (c, x) {
                            if (x === 2) {
                                this.showLoader();
                                jQuery.getJSON('/MediaAPI/ImageFly/API', {action: "remove_color", image: image})
                                        .done(this.reload.bindToObject(this))
                                        .fail(this.on_network_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
                            }
                        })
                        .show();

            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="cropper">
        F.prototype.onCommandColor_control_crop = function (j) {
            var context = "_color";
            var id = 100;
            var image = U.NEString(j.data('color'), null);
            if (context && id && image) {
                this._data_for_cropper = {context: context, id: id, image: image};
                this.showLoader();
                Y.load('media.image_cropper')
                        .done(this, this.on_cropper_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            } else {
                this._data_for_cropper = null;
            }
            return this;
        };

        F.prototype.on_cropper_ready = function (c) {
            var p = U.safeObject(this._data_for_cropper);
            c.show().load("_color", 100, p.image).setCallback(this, this.reload);
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="extended_editor">
        F.prototype.onCommandColor_control_edit = function (t) {
            var context = "_color";
            var id = 100;
            var image = U.NEString(t.data('color'), null);
            if (context && id && image) {
                this._data_for_editor = {context: context, id: id, image: image};
                this.showLoader();
                Y.load('media.image_editor_pixlr')
                        .done(this, this.on_pixlr_ready)
                        .fail(this, this.onRequiredComponentFail)
                        .always(this, this.hideLoader);
            } else {
                this._data_for_editor = null;
            }
            return this;
        };
        F.prototype.on_pixlr_ready = function (x) {
            x.show().load("_color", 100, this._data_for_editor.image).setCallback(this, this.reload);
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="remover">
        F.prototype.onCommandRemove_color = function (t) {
            var uid = U.NEString(t.data('color'), null);
            if (uid) {
                var color = this.color_list.get_by_uid(uid);
                if (color) {
                    color.removed = !color.removed;
                    t.closest('.' + MC + 'OneColorItem')[(color.removed ? 'addClass' : 'removeClass')](MC + 'RemovedColor');
                }
            }
            return this;
        };
        F.prototype.onCommandRestore_color = F.prototype.onCommandRemove_color;
        //</editor-fold>



        //</editor-fold>





        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="zoom">
        F.prototype.restore_size = function () {
            var key = [MC, 'zoom'].join('');
            var size = U.FloatMoreOr(localStorage.getItem(key), .3, 1.0);
            this.getRole('list').css("font-size", [size.toFixed(2), 'em'].join(''));
            return this;
        };


        F.prototype.onCommandZoom_in = function () {
            var key = [MC, 'zoom'].join('');
            var size = U.FloatMoreOr(localStorage.getItem(key), .3, 1.0);
            size = Math.max(size - .1, .3);
            localStorage.setItem(key, size.toFixed(2));
            return this.restore_size();
        };
        F.prototype.onCommandZoom_out = function () {
            var key = [MC, 'zoom'].join('');
            var size = U.FloatMoreOr(localStorage.getItem(key), .3, 1.0);
            size = Math.max(size + .1, .3);
            localStorage.setItem(key, size.toFixed(2));
            return this.restore_size();
        };
        //</editor-fold>
        //
        F.prototype.export = function () {
            return this.color_list ? this.color_list.export() : [];
        };
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