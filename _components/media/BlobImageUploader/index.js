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
        F.prototype.images = null; // все изображения        
        F.prototype.images_order = null; //локальная сортировка //image name = md5 (guid)

        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">        
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.handle.on('change', 'input[type=file]', this.on_file_changed.bindToObjectWParam(this));
            this.create_image_url = this._create_image_url.bindToObjectWParam(this);
            this.instance_id = U.UID();
            this._roles = U.scan(this.handle, 'mediablobimageuploaderrole');
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
                //draggable_image_{{instance_id}}
                EFO.DnDManager().LEM.on('ON_DRAG_STARTS_draggable_image_' + this.instance_id, this, this.on_drag_starts);
                EFO.DnDManager().LEM.on('ABORT_DRAG_draggable_image_' + this.instance_id, this, this.on_abort_drag);
                EFO.DnDManager().LEM.on('ON_DROPPED_draggable_image_' + this.instance_id, this, this.on_drop);
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
                    var draggable_uid = U.NEString(draggable.data('id'), null);
                    if (draggable_uid) {
                        var drop_uid = U.NEString(T.data('id'), null);
                        if (drop_uid) {
                            draggable.insertAfter(T.parent());
                            var move_uid = draggable_uid;
                            var after_uid = drop_uid;
                            var mov_s = this.get_image_by_uid(move_uid);
                            var ni = [];

                            for (var i = 0; i < this.images.length; i++) {
                                if (this.images[i] === mov_s) {
                                    continue;
                                }
                                ni.push(this.images[i]);
                                if (this.images[i].uid === after_uid) {
                                    ni.push(mov_s);
                                }
                            }
                            this.images = ni;
                            this.render_images();
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
            U.isArray(this.images) ? 0 : this.images = [];
            var jn = jQuery(n);
            var val = U.NEString(jn.val(), null);
            if (val) {
                var support_key = EFO.MD5().MD5(U.UUID());
                for (var i = 0; i < n.files.length; i++) {
                    var f = n.files[i];
                    if (/^image/i.test(f.type)) {
                        var ni = {
                            temp: true,
                            mime: f.type,
                            file: f,
                            src: URL.createObjectURL(f),
                            uid: EFO.MD5().MD5([support_key, i].join('')),
                            field: jn
                        };
                        this.images.push(ni);
                    }
                }
                this.render_images();
            }
            jn.parent().html('<div class="' + MC + 'UploadButtonText">Загрузить</div><input type="file" multiple="multiple" accept=".jpg,.gif,.png,.psd,.tif,.tiff" />');
            return this;
        };


        //установка параметров и перезагрузка - вручную  
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
                this.showLoader();// 
                jQuery.getJSON('/MediaAPI/ImageFly/API', {action: "list", context: this.context, owner_id: this.owner_id, extension: this.extension})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.render_images();  // темпы останутся               
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
                    var ni = [];
                    var ri = U.safeArray(d.list);
                    for (var i = 0; i < ri.length; i++) {
                        ni.push({
                            context: ri[i].context,
                            owner_id: ri[i].owner_id,
                            image: ri[i].image,
                            temp: false,
                            uid: ri[i].image
                        });
                    }
                    this.combine_images(this.images, ni);
                    this.render_images();
                    this.LEM.run('IMAGELIST_CHANGED', this);
                    return this;
                }
                if (d.status === 'error') {
                    return this.show_error(d.error_info.message);
                }
            }
            this.show_error("invalid server responce");
        };

        F.prototype.combine_images = function (sa, new_images) {
            sa = U.safeArray(sa);
            new_images = U.safeArray(new_images);
            var result = [];
            var ri = {};
            for (var i = 0; i < new_images.length; i++) {
                result.push(new_images[i]);
                ri[new_images[i].uid] = new_images[i].uid;
            }
            if (false) {
                for (var i = 0; i < sa.length; i++) {
                    if (ri[sa[i].uid] !== sa[i].uid) {
                        if (!sa[i].removed) {
                            ri[sa[i].uid] = sa[i].uid;
                            result.push(sa[i]);
                        }
                    }
                }
            }
            this.images = result;
            this.images_order = [];
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            //сделать ререндеринг изображений
            this.owner_id = null;
            this.images = null;
            this.images_order = [];
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
        F.prototype.render_images = function () {
            this.getRole('filelist').html(Mustache.render(EFO.TemplateManager().get('list', MC), this, this.get_parts_templates()));
            this.on_after_render_images();
            return this;
        };

        F.prototype.on_after_render_images = function () {
            return this;
        };

        F.prototype.show_error = function (x) {
            debugger;
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

        F.prototype.get_default_image = function () {
            if (this.images && this.images.length) {
                return U.NEString(this.images[0].uid, null);
            }
            return null;
        };

        F.prototype.get_image_by_uid = function (uid) {
            var t = U.safeArray(this.images);
            uid = U.NEString(uid, null);
            if (uid) {
                for (var i = 0; i < t.length; i++) {
                    if (t[i].uid === uid) {
                        return t[i];
                    }
                }
            }
            return null;
        };

        //<editor-fold defaultstate="collapsed" desc="Комманды">    
        //<editor-fold defaultstate="collapsed" desc="remover">
        F.prototype.onCommandImage_control_remove = function (j) {
            var uid = U.NEString(j.data('id'), null);
            if (uid) {
                var item = this.get_image_by_uid(uid);
                if (item) {
                    if (!item.temp) {
                        item.removed = true;
                        this.handle.find([".", MC, "ImageMarker", item.uid].join('')).addClass([MC + 'ImageRemoved'].join(''));
                    } else {
                        var ix = this.images.indexOf(item);
                        if (ix >= 0) {
                            this.images = this.images.slice(0, ix).concat(this.images.slice(ix + 1));
                            this.render_images();
                        }
                    }
                }
            }
            return this;
        };
        F.prototype.onCommandImage_control_remove_undo = function (j) {
            var uid = U.NEString(j.data('id'), null);
            if (uid) {
                var item = this.get_image_by_uid(uid);
                if (item) {
                    item.removed = false;
                    this.handle.find([".", MC, "ImageMarker", item.uid].join('')).removeClass([MC + 'ImageRemoved'].join(''));
                }
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="cropper">
        F.prototype.onCommandImage_control_crop = function (j) {
            var uid = U.NEString(j.data('id'), null);
            if (uid) {
                var item = this.get_image_by_uid(uid);
                if (item) {
                    if (item.temp) {
                        U.TError("Это изображение еще не загружено на сервер.<br>Подрезчик работает только с картинками на стороне сервера.");
                    } else {
                        var context = U.NEString(item.context, null);
                        var id = U.NEString(item.owner_id, null);
                        var image = U.NEString(item.image, null);
                        if (context && id && image) {
                            this._data_for_cropper = {context: context, id: id, image: image};
                            this.showLoader();
                            Y.load('media.image_cropper')
                                    .done(this, this.on_cropper_ready)
                                    .fail(this, this.onRequiredComponentFail)
                                    .always(this, this.hideLoader);
                        }
                    }
                }
            }
            return this;
        };

        F.prototype.on_cropper_ready = function (c) {
            var p = U.safeObject(this._data_for_cropper);
            c.show().load(p.context, p.id, p.image).setCallback(this, this.on_cropper_done); //reload only target image!
            return this;
        };

        F.prototype.on_cropper_done = function (imagedata) {
            if (U.isObject(imagedata)) {
                var c = this.get_image_by_uid(U.NEString(imagedata.image, null));
                if (c) {
                    var url = this._create_image_url(c);
                    this.handle.find(['.', MC, "ImageMarker", c.uid].join('')).find('img').attr('src', url);
                }
            }
            return this;
        };
        //</editor-fold>
        //
        F.prototype.set_field_name = function (x) {
            this._field_name = U.NEString(x);
            return this;
        };

        F.prototype.collect_data_fields = function (x, context, callback) {
            if (this.images && this.images.length) {
                var fn = U.NEString(this._field_name, 'image_file');
                x[[fn, 'transfer_mode'].join('_')] = 'form/data';
                var uploads = [];
                var orders = [];
                var removes = [];
                var used_fields = [];
                var file_fields = [];
                for (var i = 0; i < this.images.length; i++) {
                    if (this.images[i].temp && !this.images[i].removed) {
                        if (file_fields.indexOf(this.images[i].field.get(0)) < 0) {
                            file_fields.push(this.images[i].field.get(0));
                        }
                    }
                }
                var grouped_fields = {};
                for (var i = 0; i < file_fields.length; i++) {
                    var key = ["A", i].join('');
                    for (var j = 0; j < this.images.length; j++) {
                        if (this.images[j].temp && !this.images[j].removed && this.images[j].field.get(0) === file_fields[i]) {
                            U.isArray(grouped_fields[key]) ? 0 : grouped_fields[key] = [];
                            grouped_fields[key].push(this.images[j]);
                        }
                    }
                }
                var basics = {};
                for (var i = 0; i < this.images.length; i++) {
                    if (this.images[i].temp && !this.images[i].removed) {
                        var ffindex = file_fields.indexOf(this.images[i].field.get(0));
                        var ffkey = ['A', ffindex].join('');
                        if (used_fields.indexOf(this.images[i].field.get(0)) < 0) {
                            uploads.push(this.images[i].uid);
                            x[[fn, 'file', this.images[i].uid].join('_')] = this.images[i].field;
                            used_fields.push(this.images[i].field.get(0));
                            basics[ffkey] = this.images[i].uid;
                        } else {
                            var basic = basics[ffkey];
                            this.images[i].uid = EFO.MD5().MD5(basic + grouped_fields[ffkey].indexOf(this.images[i]));
                        }
                        //x.append([fn, 'file', this.images[i].uid].join('_'), this.images[i].file);
                    } else if (!this.images[i].temp && this.images[i].removed) {
                        removes.push(this.images[i].uid);
                    }
                    if (!this.images[i].removed) {
                        orders.push(this.images[i].uid);
                    }
                }
                x[[fn, 'removes[]'].join('_')] = removes;
                x[[fn, 'uploads[]'].join('_')] = uploads;
                x[[fn, 'orders[]'].join('_')] = orders;
                if (U.isCallable(callback)) {
                    callback.apply((U.isObject(context) ? context : this), [this, x]);
                }
            }else{
                if (U.isCallable(callback)) {
                    callback.apply((U.isObject(context) ? context : this), [this, x]);
                }
            }
        };

        F.prototype.collect_data = function (x, context, callback) {
            // для чтения как data_url нужен каллбак
            if (this.images && this.images.length) {
                var fn = U.NEString(this._field_name, 'image_file');
                if (window.FormData && (x instanceof window.FormData)) {
                    var fn = U.NEString(this._field_name, 'image_file');
                    x.append([fn, 'transfer_mode'].join('_'), 'form/data');
                    var uploads = [];
                    var orders = [];
                    var removes = [];
                    for (var i = 0; i < this.images.length; i++) {
                        if (this.images[i].temp && !this.images[i].removed) {
                            uploads.push(this.images[i].uid);
                            x.append([fn, 'file', this.images[i].uid].join('_'), this.images[i].file);
                        } else if (!this.images[i].temp && this.images[i].removed) {
                            removes.push(this.images[i].uid);
                        }
                        if (!this.images[i].removed) {
                            orders.push(this.images[i].uid);
                        }
                    }
                    for (var i = 0; i < removes.length; i++) {
                        x.append([fn, 'removes[]'].join('_'), removes[i]);
                    }
                    for (var i = 0; i < uploads.length; i++) {
                        x.append([fn, 'uploads[]'].join('_'), uploads[i]);
                    }
                    for (var i = 0; i < orders.length; i++) {
                        x.append([fn, 'orders[]'].join('_'), orders[i]);
                    }
                    if (U.isCallable(callback)) {
                        callback.apply((U.isObject(context) ? context : this), [this, x]);
                    }
                } else if (U.isObject(x)) {
                    var fn = U.NEString(this._field_name, 'image_file');
                    x[ [fn, 'transfer_mode'].join('_') ] = 'content/data-url';
                    var uploads = [];
                    var orders = [];
                    var removes = [];
                    for (var i = 0; i < this.images.length; i++) {
                        if (this.images[i].temp && !this.images[i].removed) {
                            uploads.push(this.images[i].uid);
                            // x.append([fn, 'file', this.images[i].uid].join('_'), this.images[i].file);
                        } else if (!this.images[i].temp && this.images[i].removed) {
                            removes.push(this.images[i].uid);
                        }
                        if (!this.images[i].removed) {
                            orders.push(this.images[i].uid);
                        }
                    }
                    var promises = [];
                    x[ [fn, 'removes[]'].join('_') ] = removes;
                    x[ [fn, 'uploads[]'].join('_') ] = uploads;
                    x[ [fn, 'orders[]'].join('_') ] = orders;
                    var self = this;
                    function local_callback(a) {
                        var uid = U.NEString(a.target.uid, null);
                        if (uid) {
                            promises.push({n: uid, d: a.target.result});
                        }
                        if (promises.length === uploads.length) {
                            x[ [fn, 'files'].join('_') ] = promises;
                            if (U.isCallable(callback)) {
                                callback.apply((U.isObject(context) ? context : self), [self, x]);
                            }
                        }
                    }
                    for (var i = 0; i < this.images.length; i++) {
                        if (this.images[i].temp && !this.images[i].removed) {
                            var reader = new FileReader();
                            reader.uid = this.images[i].uid;
                            reader.onloadend = local_callback;
                            reader.readAsDataURL(this.images[i].file);
                        }
                    }

                }
            } else if (U.isCallable(callback)) {
                callback.apply((U.isObject(context) ? context : this), [this, x]);
            }
            return this;
        };
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