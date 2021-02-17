(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент      
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.image_uploader').promise,
        Y.load('inline.property_editor').promise,
        Y.css('/assets/css/advt.css'),
        Y.js('/assets/js/ET/ADVTable/advt.js')
    ];
    //</editor-fold>
    function initPlugin() {
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            window.Eve.ADVTable = window.Eve.ADVTable || {};
            window.Eve.ADVTable.Ready = window.Eve.ADVTable.Ready || [];
            window.Eve.ADVTable.Ready.push(deps_ready);
        });
    }

    function deps_ready() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = E.ADVTable;
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Fieldable', 'Monitorable', 'Callbackable', 'Sizeable', 'Tabbable'];
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
            this.init_editors();
            this.init_image_list();
            return this;
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.placeAtCenter();
            this.text_editor_intro.init_editor();
            this.text_editor_info.init_editor();
            return this;
        };

        F.prototype.onAfterHide = function () {
            this.text_editor_intro.destroy_editor();
            this.text_editor_info.destroy_editor();
            return PARP.onAfterHide.apply(this, APS.call(arguments));
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
            return "Редактирование студии";
        };

        F.prototype.enumSubTemplates = function () {
            return [].concat(
                    PARP.enumSubTemplates.call(this),
                    [
                        MC + ".TAB_common"
                                , MC + ".TAB_intro"
                                , MC + ".TAB_info"
                                , MC + ".TAB_images"
                                , MC + ".TAB_properties"


                    ]);
        };
        //</editor-fold>   
        //<editor-fold defaultstate="collapsed" desc="parts editors">
        //<editor-fold defaultstate="collapsed" desc="quill">
        F.prototype.init_editors = function () {
            //debugger;
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.text_editor_info = cf();
            this.text_editor_info.setContainer(this.getRole('info'));
            this.text_editor_intro = cf();
            this.text_editor_intro.setContainer(this.getRole('intro'));
            this.init_property_editor();
            return this;
        };
        F.prototype.init_property_editor = function () {
            var cf = Y.get_loaded_component('inline.property_editor');
            this.property_editor = cf(MC);
            this.property_editor.setContainer(this.getRole('properties'));
            return this;
        };



        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="imagelist">
        F.prototype.init_image_list = function () {
            var UF = Y.get_loaded_component('media.image_uploader');
            this.image_list = UF();
            this.image_list.set_params("media_studio", null);
            this.image_list.setContainer(this.getRole("image-list"));
            return this;
        };
        //</editor-fold>                
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Лоадер">                
        F.prototype.load = function (id) {
            this.clear();
            if (U.IntMoreOr(id, 0, null)) {
                this.showLoader();
                jQuery.getJSON('/admin/VendorList/API', {action: "get", id: id})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    return this.on_load_success(d);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_network_fail = function (x) {
            x = U.NEString(x, 'network error');
            U.TError(x);
            return this;
        };

        F.prototype.on_network_fail_fatal = function () {
            this.on_network_fail.apply(this, APS.call(arguments));
            return this.hideclear();
        };

        F.prototype.on_load_success = function (x) {
            this.setFields(x.data);
            this.image_list.set_params('media_studio', this.getField('id').val());
            return this;
        };
        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.image_list ? this.image_list.clear() : false;
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">



        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="field get/set overrides">


        F.prototype._set_field_html_mode = function () {
            return this;
        };

        F.prototype._get_field_html_mode = function () {
            return ((this.text_editor_intro.get_check_state() ? 1 : 0) << 1) + (this.text_editor_info.get_check_state() ? 1 : 0);
            //return this.text_editor_info.get_check_state();
        };

        F.prototype._set_field_default_image = function () {
            return this;
        };
        F.prototype._get_field_default_image = function () {
            return this.image_list.get_default_image();
        };

        F.prototype._set_field_images = function () {
            return this;
        };

        F.prototype._get_field_images = function () {
            return null;
        };

        F.prototype._get_field_info = function () {
            return this.text_editor_info.getText();
        };
        F.prototype._set_field_info = function (c) {
            var html_mode = U.IntMoreOr(c.html_mode, 0, 0);
            var ch = !!(html_mode & 1);
            this.text_editor_info.setText(U.NEString(c.info, ''), ch);
            //this.text_editor_info.root.innerHTML = U.NEString(c.description, '');
            return this;
        };
        F.prototype._get_field_intro = function () {
            return this.text_editor_intro.getText();
        };
        F.prototype._set_field_intro = function (c) {
            var html_mode = U.IntMoreOr(c.html_mode, 0, 0);
            var ch = !!(html_mode & 2);
            this.text_editor_intro.setText(U.NEString(c.intro, ''), ch);
            return this;
        };

        F.prototype._set_field_properties = function (x) {
            this.property_editor.set_data(U.safeArray(x.properties));
            return this;
        };

        F.prototype._get_field_properties = function () {
            return this.property_editor.get_data();
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
        //</editor-fold>



        //<editor-fold defaultstate="collapsed" desc="save">  
        F.prototype.getFilters = function () {
            if (!this.FD) {
                this.FD = EFO.Filter.FilterDescriptor('values', MC);
            }
            return this.FD;
        };

        //<editor-fold defaultstate="collapsed" desc="checkers">
        F.prototype.check_data_common = function (raw, out) {
            try {
                var data = EFO.Filter.Filter().applyFiltersToHash(raw, this.getFilters().getSectionExport('common'));
                EFO.Filter.Filter().throwValuesErrorFirst(data, true);
                for (var k in data) {
                    if (data.hasOwnProperty(k) && !U.isCallable(data[k])) {
                        out[k] = data[k];
                    }
                }
            } catch (e) {
                U.Error(MC + ":Common:" + e.message);
            }
            return this;
        };
        //</editor-fold>

        F.prototype.check_data = function (raw_data) {
            var data = {};
            try {
                for (var k in F.prototype) {
                    if (U.isCallable(F.prototype[k]) && F.prototype.hasOwnProperty(k)) {
                        if (/^check_data_.*/i.test(k)) {
                            F.prototype[k].apply(this, [raw_data, data]);
                        }
                    }
                }
            } catch (ee) {
                U.TError(ee);
                return false;
            }

            return data;
        };

        F.prototype.save = function (keep_open) {
            this.keep_open = U.anyBool(keep_open, true);
            var raw_data = this.getFields();
            var data = this.check_data(raw_data);
            if (data) {
                this.showLoader();
                jQuery.post('/admin/VendorList/API', {action: 'put', data: JSON.stringify(data)}, null, 'json')
                        .done(this.on_post_responce.bindToObject(this))
                        .fail(this.on_network_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));

            }
            return this;
        };

        F.prototype.on_post_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === 'ok') {
                    this.runCallback();
                    if (this.keep_open) {
                        this.on_load_success(d);
                    } else {
                        this.hideclear();
                    }
                    return this;
                }
                if (d.status === 'error') {
                    return this.on_network_fail(d.error_info.message);
                }
            }
            return this.on_network_fail("invalid server responce");
        };


        F.prototype.onTabSelectedInfo = function () {
            this.text_editor_info.refresh();
            return this;
        };

        F.prototype.onTabSelectedIntro = function () {
            this.text_editor_intro.refresh();
            return this;
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