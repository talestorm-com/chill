(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.flatController, PARP = PAR.prototype, APS = Array.prototype.slice;
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable'];
        U.initMixines(F);
        F.prototype.MD = MD;

        F.prototype.context = null;
        F.prototype.owner_id = null;
        F.prototype.image_list = null;
        F.prototype.media_title_editor = 'media.title_editor';
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">        
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.handle.on('change', 'input[type=file]', this.on_file_changed.bindToObjectWParam(this));
            this.create_image_url = this._create_image_url.bindToObjectWParam(this);
            this.restore_size();
            this.instance_id = U.UID();
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
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

        //<editor-fold defaultstate="collapsed" desc="dnd">
        F.prototype.init_dnd = function () {
            if (!this._drag_init_complete) {
                EFO.DnDManager().LEM.on('ON_DRAG_STARTS_' + this.context + '_image_' + this.instance_id, this, this.on_drag_starts);
                EFO.DnDManager().LEM.on('ABORT_DRAG_' + this.context + "_image_" + this.instance_id, this, this.on_abort_drag);
                EFO.DnDManager().LEM.on('ON_DROPPED_' + this.context + "_image_" + this.instance_id, this, this.on_drop);
                this._drag_init_complete = true;
            }
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
                    // console.log(draggable);
                    var draggable_context = U.NEString(draggable.data('context'), null);
                    var draggable_id = U.NEString(draggable.data('id'), null);
                    var draggable_image = U.NEString(draggable.data('image'), null);
                    if (draggable_context && draggable_id && draggable_image) {
                        //  console.log("draggable_ok");
                        var drop_context = U.NEString(T.parent().data('context'), null);
                        var drop_id = U.NEString(T.parent().data('id'), null);
                        var drop_img = U.NEString(T.parent().data('image'), null);
                        if (drop_context && drop_id && drop_img) {
                            //  console.log("droppable_ok");
                            if (drop_context === draggable_context && drop_id === draggable_id && drop_img !== draggable_image) {
                                draggable.insertAfter(T.parent());
                                var sorts = [];
                                var images = this.handle.find(['.', MC, 'OneImageOuter'].join(''));
                                images.each(function () {
                                    var i = U.NEString(jQuery(this).data('image'), null);
                                    i ? sorts.push(i) : i;
                                });
                                this.showLoader();
                                console.log(sorts);
                                jQuery.post('/MediaAPI/ImageFly/API', {action: "reorder_images", data: JSON.stringify({
                                        order: sorts, context: drop_context, id: drop_id
                                    })})
                                        .done(this.on_load_responce.bindToObject(this))
                                        .fail(this.on_network_fail.bindToObject(this))
                                        .always(this.hideLoader.bindToObject(this));
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
        F.prototype._create_image_url = function (x) {
            var t = (new Date()).getTime();
            return ["/media/", x.context, '/', x.owner_id, '/', x.image, '.SW_250H_250', '.jpg?acfp=a', t].join('');
        };

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

        //установка параметров и перезагрузка - вручную с хоста 
        F.prototype.set_params = function (context, owner_id) {
            this.context = U.NEString(context, null);
            if (this.context && !this._dnd_ok) {
                this.init_dnd();
            }
            this.set_owner_id(owner_id);
            return this;
        };

        F.prototype.set_owner_id = function (owner_id) {
            this.owner_id = U.NEString(owner_id, null);
            this.reload();
            return this;
        };

        F.prototype.reload = function () {
            if (this.context && this.owner_id) {
                this.disable_controls();
                this.showLoader();// нужно заменить неймспейс или имя папки - они конфликтуют
                jQuery.getJSON('/MediaAPI/ImageFly/API', {action: "list", context: this.context, owner_id: this.owner_id, extension: this.extension})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.show_error("save owner object to activate image manipulation");
                this.disable_controls();
            }
            return this;
        };

        F.prototype.on_network_fail = function () {
            this.show_error("network error");
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this.render_images(U.safeArray(d.list)).enable_controls();
                    this.LEM.run('IMAGELIST_CHANGED', this);
                    return this;
                }
                if (d.status === 'error') {
                    return this.show_error(d.error_info.message);
                }
            }
            this.show_error("invalid server responce");
        };


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            //сделать ререндеринг изображений
            this.owner_id = null;
            this.image_list = null;
            return this.render_images().disable_controls();

        };

        //</editor-fold>    
        F.prototype.get_parts_templates = function () {
            return {
                'image_control_panel': EFO.TemplateManager().get('control', MC),
                'empty_image_list': EFO.TemplateManager().get('empty', MC),
                'one_image': EFO.TemplateManager().get('one_image', MC),
                'droptarget': EFO.TemplateManager().get('droptarget', MC)
            };
        };
        F.prototype.render_images = function (list) {
            this.image_list = U.safeArray(list);
            if (this.context && this.owner_id) {
                list = U.safeArray(list);
                this._images = list;
                this.getRole('images').html(Mustache.render(EFO.TemplateManager().get('list', MC), this, this.get_parts_templates()));
            } else {
                this.show_error("save owner object to activate image manipulation");
            }
            this._images = null;
            this.on_after_render_images();

            return this;
        };

        F.prototype.on_after_render_images = function () {
            return this;
        };

        F.prototype.show_error = function (x) {
            this._error_text = EFO.Translator().T(U.NEString(x, "unknown error"));
            this.getRole("images").html(Mustache.render(EFO.TemplateManager().get('error', MC), this));
            return this;
        };
        F.prototype.disable_controls = function () {
            this.getRole('cp').addClass(MC + "disabled");
            return this;
        };
        F.prototype.enable_controls = function () {
            this.getRole('cp').removeClass(MC + "disabled");
            return this;
        };

        F.prototype.set_title_editor = function (x) {
            this.media_title_editor = U.NEString(x, F.prototype.media_title_editor);
            return this;
        };
        F.prototype.set_meta_editor = F.prototype.set_title_editor;

        F.prototype.get_default_image = function () {
            if (this.image_list && this.image_list.length) {
                return U.NEString(this.image_list[0].image, null);
            }
            return null;
        };

        //<editor-fold defaultstate="collapsed" desc="Комманды">   
        //<editor-fold defaultstate="collapsed" desc="download">
        F.prototype.onCommandImage_control_download = function (t) {
            var context = U.NEString(t.data('context'), null);
            var id = U.NEString(t.data('owner_id'), null);
            var image = U.NEString(t.data('image'), null);
            if (context && id && image) {
                var url = "/MediaAPI/ImageFly/DownloadSource?context=" + encodeURIComponent(context) + "&owner_id=" + encodeURIComponent(id) + "&image=" + encodeURIComponent(image);
                var win = window.open(url, 'imagedown');
                if (!win) {
                    U.TError('Требуется разрешение на открытие всплывающих окон!');
                }
            }
            return this;
        };
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="info">
        F.prototype.onCommandImage_control_info = function (j) {
            var context = U.NEString(j.data('context'), null);
            var id = U.NEString(j.data('owner_id'), null);
            var image = U.NEString(j.data('image'), null);
            if (context && id && image) {
                this._data_for_title_editor = {context: context, id: id, image: image};
                this.showLoader();
                Y.load(this.media_title_editor)
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
        F.prototype.onCommandImage_control_remove = function (j) {
            var context = U.NEString(j.data('context'), null);
            var id = U.NEString(j.data('owner_id'), null);
            var image = U.NEString(j.data('image'), null);
            if (context && id && image) {
                EFO.simple_confirm().set_icon("?").set_close_btn(true)
                        .set_text(EFO.Translator().T("remove_image_confirm"))
                        .set_style("blue").set_buttons(["Отмена", "Ok"])
                        .set_title(EFO.Translator().T("remove_confirm_title"))
                        .set_callback(this, function (c, x) {
                            if (x === 2) {
                                this.showLoader();
                                jQuery.getJSON('/MediaAPI/ImageFly/API', {action: "remove_image", context: context, owner_id: id, image: image})
                                        .done(this.on_load_responce.bindToObject(this))
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
        F.prototype.onCommandImage_control_crop = function (j) {
            var context = U.NEString(j.data('context'), null);
            var id = U.NEString(j.data('owner_id'), null);
            var image = U.NEString(j.data('image'), null);
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
            c.show().load(p.context, p.id, p.image).setCallback(this, this.reload);
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="extended_editor">
        F.prototype.onCommandImage_control_edit = function (t) {
            var context = U.NEString(t.data('context'), null);
            var id = U.NEString(t.data('owner_id'), null);
            var image = U.NEString(t.data('image'), null);
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
            x.show().load(this._data_for_editor.context, this._data_for_editor.id, this._data_for_editor.image).setCallback(this, this.reload);
            return this;
        };
        //</editor-fold>

        //</editor-fold>





        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="zoom">
        F.prototype.restore_size = function () {
            var key = [MC, 'zoom'].join('');
            var size = U.FloatMoreOr(localStorage.getItem(key), .3, 1.0);
            this.getRole('images').css("font-size", [size.toFixed(2), 'em'].join(''));
            return this;
        };


        F.prototype.onCommandImage_uploader_zoom_in = function () {

            var key = [MC, 'zoom'].join('');
            var size = U.FloatMoreOr(localStorage.getItem(key), .3, 1.0);
            size = Math.max(size - .1, .3);
            localStorage.setItem(key, size.toFixed(2));
            return this.restore_size();
        };
        F.prototype.onCommandImage_uploader_zoom_out = function () {
            var key = [MC, 'zoom'].join('');
            var size = U.FloatMoreOr(localStorage.getItem(key), .3, 1.0);
            size = Math.max(size + .1, .3);
            localStorage.setItem(key, size.toFixed(2));
            return this.restore_size();
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