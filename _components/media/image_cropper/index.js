(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    var presets_list = null;
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
    ];
    //</editor-fold>
    function initPlugin() {
        jQuery.get("/Info/API?action=media_preset_collection")
                .done(function (d) {
                    if (window.Eve.EFO.U.isObject(d)) {
                        if (d.status === "ok") {
                            presets_list = window.Eve.EFO.U.safeArray(d.aspect_presets);
                            initPluginA();
                            return;
                        }
                    }
                    Y.reportFail(FQCN, "Ошибке при загрузке зависимости");
                })
                .fail(function () {
                    Y.reportFail(FQCN, "Ошибке при загрузке зависимости");
                });
    }
    function initPluginA() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        function F() {
            return F.is(H) ? H : (F.is(this) ? this.init() : F.F());
        }
        var SVG = null;
        /*<?=$this->create_svg('SVG')?>*/
        EFO.SVGDriver().register_svg(FQCN, MC, U.NEString(U.safeObject(SVG).svg, null));
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        var ZOOM_STEP = [
            .07, .08, .09, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1.0, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 2.0, 2.1, 2.2, 2.3, 2.4, 2.5
        ];
        var SHADOW_STEP = [
            0.0, 0.1, .2, .3, .4, .5, .6, .7, .8, .9, 1.0
        ];
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
        F.prototype.sizeable_defaultWidth = function () {
            return 95;
        };
        F.prototype.sizeable_defaultHeight = function () {
            return 95;
        };
        //</editor-fold>
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            this.LEM.On('NEED_POSITE', this, this.adapt_view);
            this.init_cropper_move();
            this.init_cropper_resize();
            this.getField('zoom').on('input', this.onMonitorZoom.bindToObjectWParam(this));
            this.getField('shadow').on('input', this.onMonitorShadow.bindToObjectWParam(this));
            this.aspects_voc = [{'name': "none", "width": null, "height": null, display: "Нет"}].concat(U.safeArray(presets_list));
            this.getField('restriction').html(Mustache.render(EFO.TemplateManager().get('crop_option', MC), this));
            this.restore_params();
            this.restore_shadow();
            return this;
        };

        F.prototype.enumSubTemplates = function () {
            var r = PARP.enumSubTemplates.apply(this, APS.call(arguments));
            return r.concat([
                MC + ".toolpanel"
            ]);
        };

        F.prototype.getZoomMax = function () {
            return ZOOM_STEP.length - 1;
        };
        F.prototype.getShadowMax = function () {
            return SHADOW_STEP.length - 1;
        };
        //<editor-fold defaultstate="collapsed" desc="move && resize">
        //<editor-fold defaultstate="collapsed" desc="cropper move">
        F.prototype.init_cropper_move = function () {
            this.clear_cropper_move();
            return this;
        };

        F.prototype.clear_cropper_move = function () {
            this.getRole('cropper-frame').off('mousedown', this.cropper_init_start.bindToObjectWParam(this));
            jQuery(document).off('mouseup', this.clear_cropper_move.bindToObjectWParam(this));
            jQuery(document).off('mousemove', this.cropper_move_trigger.bindToObjectWParam(this));
            jQuery(document).off('mouseup', this.cropper_move_completed.bindToObjectWParam(this));
            jQuery(document).off('mousemove', this.cropper_while_move.bindToObjectWParam(this));
            this.getRole('cropper-frame').on('mousedown', this.cropper_init_start.bindToObjectWParam(this));
            return this;
        };

        F.prototype.cropper_init_start = function (cn, e) {
            if (e.target === this.getRole('cropper-frame').get(0)) {
                var n = this.getRole('cropper-frame').get(0);
                this._init_cropper_offset_x = n.offsetLeft - e.offsetX * 0;
                this._init_cropper_offset_y = n.offsetTop - e.offsetY * 0;
                this._init_cropper_pos_x = e.pageX;
                this._init_cropper_pos_y = e.pageY;
                jQuery(document).on('mouseup', this.clear_cropper_move.bindToObjectWParam(this));
                jQuery(document).on('mousemove', this.cropper_move_trigger.bindToObjectWParam(this));
            }
            return this;
        };

        F.prototype.cropper_move_trigger = function (cn, e) {
            var dx = (Math.max(e.pageX, this._init_cropper_pos_x) - Math.min(e.pageX, this._init_cropper_pos_x));
            var dy = (Math.max(e.pageY, this._init_cropper_pos_y) - Math.min(e.pageY, this._init_cropper_pos_y));
            var delta = Math.sqrt(Math.pow(dx, 2) + Math.pow(dy, 2));
            if (true || Math.abs(delta) > 10) {
                this.clear_cropper_move();
                jQuery(document).on('mouseup', this.cropper_move_completed.bindToObjectWParam(this));
                jQuery(document).on('mousemove', this.cropper_while_move.bindToObjectWParam(this));
                this.cropper_while_move(cn, e);
            }
            return this;
        };

        F.prototype.cropper_while_move = function (cn, e) {
            var dx = e.pageX - this._init_cropper_pos_x;
            var dy = e.pageY - this._init_cropper_pos_y;
            var ex = this._init_cropper_offset_x + dx;
            var ey = this._init_cropper_offset_y + dy;
            var px = 'px';
            this.getRole('cropper-frame').css({left: ex + px, top: ey + px});
            return this;
        };

        F.prototype.cropper_move_completed = function () {
            this.clear_cropper_move();
            this.on_after_resize_or_move();
            return this;

        };


        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="cropper resize">
        F.prototype.init_cropper_resize = function () {
            this.clear_resize_events();
        };
        F.prototype.get_resizers_all = function () {
            return this.getRole('node_top_left').add(this.getRole('node_bottom_left')).add(this.getRole('node_top_right')).add(this.getRole('node_bottom_right'));
        };
        F.prototype.clear_resize_events = function () {
            this.get_resizers_all().off("mousedown", this.on_resizer_md.bindToObjectWParam(this));
            jQuery(document).off('mouseup', this.clear_resize_events.bindToObject(this));
            jQuery(document).off('mousemove', this.on_resize_trigger_check.bindToObject(this));
            jQuery(document).off('mouseup', this.on_resize_finished.bindToObject(this));
            jQuery(document).off('mousemove', this.on_resize_while_move.bindToObject(this));
            this.get_resizers_all().on("mousedown", this.on_resizer_md.bindToObjectWParam(this));
        };

        F.prototype.on_resizer_md = function (n, e) {
            e.stopPropagation();
            var jqn = jQuery(n);
            this._resize_vector_x = jqn.data('x');
            this._resize_vector_y = jqn.data('y');
            this._resize_screen_pos_init_x = e.pageX;
            this._resize_screen_pos_init_y = e.pageY;
            this._resize_handle_posiztion_x = U.FloatOr(n.offsetLeft, 0.0);
            this._resize_handle_posiztion_y = U.FloatOr(n.offsetTop, 0.0);
            var CF = this.getRole('cropper-frame').get(0);
            this._resize_cropper_left = U.FloatOr(CF.offsetLeft, 0.0);
            this._resize_cropper_top = U.FloatOr(CF.offsetTop, 0.0);
            this._resize_cropper_width = U.FloatOr(CF.offsetWidth, 0.0);
            this._resize_cropper_height = U.FloatOr(CF.offsetHeight, 0.0);
            this.clear_resize_events();
            this._restrict = this.get_cropper_restriction();
            jQuery(document).on('mouseup', this.clear_resize_events.bindToObject(this));
            jQuery(document).on('mousemove', this.on_resize_trigger_check.bindToObject(this));
            return this;
        };

        F.prototype.on_resize_trigger_check = function (e) {
            var dx = Math.max(this._resize_screen_pos_init_x, e.pageX) - Math.min(this._resize_screen_pos_init_x, e.pageX);
            var dy = Math.max(this._resize_screen_pos_init_y, e.pageY) - Math.min(this._resize_screen_pos_init_y, e.pageY);
            var delta = Math.sqrt(Math.pow(dx, 2) + Math.pow(dy, 2));
            if (true || delta > 10) {
                this.clear_resize_events();
                jQuery(document).on('mouseup', this.on_resize_finished.bindToObject(this));
                jQuery(document).on('mousemove', this.on_resize_while_move.bindToObject(this));
                this.on_resize_while_move(e);
            }
            return this;
        };

        F.prototype.on_resize_while_move = function (e) {
            var dx = e.pageX - this._resize_screen_pos_init_x;
            var dy = e.pageY - this._resize_screen_pos_init_y;
            var cx = this._resize_cropper_left;
            var cy = this._resize_cropper_top;
            var cw = this._resize_cropper_width;
            var ch = this._resize_cropper_height;
            if (this._resize_vector_x === "left") {
                cx += dx;
                cw -= dx;
            } else {
                cw += dx;
            }
            if (this._resize_vector_y === "top") {
                cy += dy;
                ch -= dy;
            } else {
                ch += dy;
            }
            var px = "px";

            if (this._restrict !== null) {
                var o = {w: cw, h: ch};
                this.update_size_with_restriction(o, this._restrict, dx, dy);
                cw = o.w;
                ch = o.h;
            }
            this._cropper_pos = {top: cy, left: cx, width: cw, height: ch};
            this.getRole('cropper-frame').css({top: cy + px, left: cx + px, width: cw + px, height: ch + px});
            return this;
        };
        F.prototype.update_size_with_restriction = function (oxy, r, dx, dy) {
            oxy.h = oxy.w / r;
        };
        F.prototype.on_resize_finished = function () {
            this.clear_resize_events();
            this.on_after_resize_or_move();
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="aftercropmove">
        F.prototype.on_after_resize_or_move = function () {
            var scale = U.FloatMoreOr(ZOOM_STEP[U.IntMoreOr(this.getField('zoom').val(), -1, 0)], 0, 1.0);
            var px = "px";
            var iw = this._source_image_width * scale;
            var ih = this._source_image_height * scale;
            var pc_w = iw / 100.0;
            var pc_h = ih / 100.0;
            var ibr = this.getRole('image').find('img').get(0).getBoundingClientRect();
            var cbr = this.getRole('cropper-frame').get(0).getBoundingClientRect();
            var ix = ibr.x;
            var iy = ibr.y;
            var delta_x_abs = cbr.x - ix;
            var delta_y_abs = cbr.y - iy;
            var delta_x_pc = delta_x_abs / pc_w;
            var delta_y_pc = delta_y_abs / pc_h;
            var preset = this.get_current_preset();
            //this._image_info.crop_start_x = delta_x_pc;
            //this._image_info.crop_start_y = delta_y_pc;
            preset.csx = delta_x_pc;
            preset.csy = delta_y_pc;
            var width_pc = cbr.width / pc_w;
            var height_pc = cbr.height / pc_h;
            //this._image_info.crop_end_x = this._image_info.crop_start_x + width_pc;
            preset.cex = preset.csx + width_pc;
            //this._image_info.crop_end_y = this._image_info.crop_start_y + height_pc;
            preset.cey = preset.csy + height_pc;

            this.adapt_view();
            return this;
        };
        //</editor-fold>
        //</editor-fold>
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
            return "Кроппер";
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">              
        F.prototype.load = function (context, owner_id, image) {
            this.clear();
            if (U.NEString(context, null) && U.NEString(owner_id, null) && U.NEString(image, null)) {
                this.showLoader();
                jQuery.getJSON('/MediaAPI/ImageFly/API', {action: "get_image_info_v2", context: context, owner_id: owner_id, image: image})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_data_success(d.image_info);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            this._image_info = d;//
            this.load_image();
            return this;
        };

        F.prototype.load_image = function () {
            var image = document.createElement('img');
            image.addEventListener('load', this.on_image_loaded.bindToObjectWParam(this), true);
            image.addEventListener('error', this.on_image_load_error.bindToObject(this), true);
            var jqi = jQuery(image);
            this.getRole('image').html('').append(jqi);
            var t = (new Date()).getTime();
            image.src = ["/MediaAPI/ImageFly/source?context=", this._image_info.context, "&owner_id=", this._image_info.owner_id, "&image=", this._image_info.image, '&ffc=a', t].join('');
            return this;
        };

        F.prototype.on_image_loaded = function (x, e) {
            this._source_image_width = x.naturalWidth;
            this._source_image_height = x.naturalHeight;
            this.adapt_view();
            this.hideLoader();
        };

        F.prototype.on_image_load_error = function () {
            this.hideLoader();
            this.hide().clear();
            U.TError(MC + ":cant load image");
            return this;
        };

        //</editor-fold>   
        //<editor-fold defaultstate="collapsed" desc="cropper_restriction">
        F.prototype.get_cropper_restriction = function () {
            return this.get_current_aspect();
            var v = U.NEString(this.getField('restriction').val(), '');
            var m = /^(\d{1,}):(\d{1,})$/i.exec(v);
            if (m) {
                var x = U.IntMoreOr(m[1], 0, null);
                var y = U.IntMoreOr(m[2], 0, null);
                if (x && y) {
                    return {x: x, y: y};
                }
            }
            return null;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="view update">
        F.prototype.get_preset = function (name) {
            for (var i = 0; i < this._image_info.items.length; i++) {
                if (this._image_info.items[i].preset === name) {
                    return this._image_info.items[i];
                }
            }
            var np = {
                preset: name,
                csx: 0.0,
                csy: 0.0,
                cex: 100.0,
                cey: 100.0
            };
            this._image_info.items.push(np);
            return np;
        };

        F.prototype.get_current_preset = function () {
            var preset_name = this.getField('restriction').val();
            return this.get_preset(preset_name);
        };

        F.prototype.get_current_restriction = function () {
            var preset_name = this.getField('restriction').val();
            return this.get_restriction(preset_name);
        };

        F.prototype.get_restriction = function (name) {
            for (var i = 0; i < this.aspects_voc.length; i++) {
                if (name === this.aspects_voc[i].name) {
                    return this.aspects_voc[i];
                }
            }
            return null;
        };

        F.prototype.get_current_aspect = function () {
            var restriction = this.get_current_restriction();
            if (restriction) {
                return U.FloatOr(restriction.width / restriction.height, null);
            }
            return null;
        };

        F.prototype.adapt_view = function () {
            var preset = this.get_current_preset();
            var aspect = this.get_current_aspect();
            console.log(aspect);
            var img = this.getRole('image').find('img');
            if (img.length) {
                var scale = U.FloatMoreOr(ZOOM_STEP[U.IntMoreOr(this.getField('zoom').val(), -1, 0)], 0, 1.0);
                var px = "px";
                this.display_image_witdh = this._source_image_width * scale;
                this.display_image_height = this._source_image_height * scale;

                img.css({width: this.display_image_witdh.toFixed(3) + px, height: this.display_image_height.toFixed(3) + px});
                this.getRole('image').css({width: this.display_image_witdh.toFixed(3) + px});
                //cropper tool  
                //выставляем нулевую точку положения кропа
                var img_br = img.get(0).getBoundingClientRect();
                var frame_br = this.getRole('cropper-frame').get(0).getBoundingClientRect();
                var require_zero_offset_x = img_br.x - frame_br.x;
                var require_zero_offset_y = img_br.y - frame_br.y;

                var start_x = U.FloatOr(preset.csx, 0.0);
                var start_y = U.FloatOr(preset.csy, 0.0);
                var end_x = U.FloatOr(preset.cex, 100.0);
                var end_y = U.FloatOr(preset.cey, 100.0);
                var pcx = this.display_image_witdh / 100.0;
                var width = ((end_x - start_x) * pcx) - 2;
                var pcy = this.display_image_height / 100.0;
                var height = ((end_y - start_y) * pcy) - 2;
                if (aspect !== null) {
                    height = width / aspect;
                }
                var offset_left_px = start_x * pcx;
                var offset_top_px = start_y * pcy;
                var rq_offset_x = require_zero_offset_x + offset_left_px;
                var rq_offset_y = require_zero_offset_y + offset_top_px;
                var top = this.getRole('cropper-frame').get(0).offsetTop + rq_offset_y;
                var left = this.getRole('cropper-frame').get(0).offsetLeft + rq_offset_x;
                this.getRole('cropper-frame').css({left: left.toFixed(3) + px, top: top.toFixed(3) + px, width: width.toFixed(3) + px, height: height.toFixed(3) + px});
            }
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.getRole('image').html('');
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">
        F.prototype._set_field_zoom = function () {

            return this;
        };

        F.prototype._set_field_restriction = function () {

            return this;
        };

        F.prototype._set_field_shadow = function () {
            return this;
        };

        F.prototype._set_field_preview = function () {
            return this;
        };

        F.prototype.onMonitorRestrict = function (t) {
            localStorage.setItem(MC + "restrict", jQuery(t).val());
            this.adapt_view();
            return this;
        };

        F.prototype.onMonitorZoom = function (t) {
            this.adapt_view();
            localStorage.setItem(MC + "zoom", jQuery(t).val());
            return this;
        };
        F.prototype.onMonitorShadow = function (t) {
            localStorage.setItem(MC + "shadow", jQuery(t).val());
            this.restore_shadow();
            return this;
        };

        F.prototype.restore_params = function () {
            this.getField('restriction').val(U.NEString(localStorage.getItem(MC + 'restrict'), 'none'));
            this.getField('zoom').val(U.IntMoreOr(localStorage.getItem(MC + 'zoom'), -1, ZOOM_STEP.indexOf(1.0)));
            return this;
        };

        F.prototype.restore_shadow = function () {
            var index = U.IntMoreOr(localStorage.getItem(MC + 'shadow'), -1, SHADOW_STEP.indexOf(0.5));
            this.getField('shadow').val(index);
            var value = U.FloatOr(SHADOW_STEP[index], .5);
            this.getRole('cropper-frame').css({'box-shadow': '0 0 0 10000em rgba(0,0,0,' + value + ')'});
            return this;
        };


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
        //<editor-fold defaultstate="collapsed" desc="zoom">
        F.prototype.onCommandZoom_out = function () {
            var c = U.IntMoreOr(this.getField('zoom').val(), -1, 0);
            if (c > 0) {
                this.getField('zoom').val(c - 1);
                this.getField('zoom').change();
            }
            return this;
        };
        F.prototype.onCommandZoom_in = function () {

            var c = U.IntMoreOr(this.getField('zoom').val(), -1, 0);
            if (c >= 0 && c < ZOOM_STEP.length - 1) {
                this.getField('zoom').val(c + 1);
                this.getField('zoom').change();
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="shadow">
        F.prototype.onCommandShadow_out = function () {
            var c = U.IntMoreOr(this.getField('shadow').val(), -1, 0);
            if (c > 0) {
                this.getField('shadow').val(c - 1);
                this.getField('shadow').change();
            }
            return this;
        };
        F.prototype.onCommandShadow_in = function () {
            var c = U.IntMoreOr(this.getField('shadow').val(), -1, 0);
            if (c >= 0 && c < SHADOW_STEP.length - 1) {
                this.getField('shadow').val(c + 1);
                this.getField('shadow').change();
            }
            return this;
        };
        //</editor-fold>
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="save">          
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var data = {
                image_info: this._image_info,
                action: "post_image_crop_v2"
            };
            this.showLoader();
            jQuery.post('/MediaAPI/ImageFly/API', data, null, 'json')
                    .done(this.on_post_responce.bindToObject(this))
                    .fail(this.on_network_fail.bindToObject(this));
            return this;
        };
        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    this.on_data_success(U.safeObject(d.image_info));
                    this.runCallback(U.safeObject(d.image_info));
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

        F.prototype.on_network_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            this.hideLoader();
            return this;
        };

        F.prototype.on_network_fail_fatal = function (x) {
            this.on_network_fail.apply(this, APS.call(arguments));
            this.hide().clear();
            return this;
        };
        //</editor-fold>                        
        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
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