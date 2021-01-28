(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('inline.mce_cm_html').promise,
        Y.load('media.image_uploader').promise
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice, ADVT = window.Eve.ADVTable;
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
            this.init_editor();
            this.init_image_list();
            this.init_table();
            return this;
        };


        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".TAB_common"
                        , MC + ".TAB_intro"
                        , MC + ".TAB_gallery"
                        , MC + ".TAB_lent"
                        , MC + ".TAB_display"

            ]);
        };

        F.prototype.onAfterShow = function () {
            PARP.onAfterShow.apply(this, APS.call(arguments));
            this.intro_editor.init_editor();
            this.placeAtCenter();
            return this;
        };
        F.prototype.onAfterHide = function () {
            this.intro_editor.destroy_editor();
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
            return "Редактирование подборки";
        };
        //</editor-fold>   
        //<editor-fold defaultstate="collapsed" desc="параметры ленты">
        //<editor-fold defaultstate="collapsed" desc="preplay selector">        
        F.prototype.onCommandSelect_preplay = function () {
            this.showLoader();
            Y.load('selectors.preplay_selector')
                    .done(this, this.on_preplay_selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.on_preplay_selector_ready = function (x) {
            x.show().load().set_allow_multi(false).setCallback(this, this.on_preplay_selector_done);
        };
        F.prototype.on_preplay_selector_done = function (d) {
            this.getField('preplay').val(U.IntMoreOr(d[0].id, 0, null));
            this.getField('preplay_name').val(U.NEString(d[0].name, null));
            return this;
        };
        //</editor-fold>
        F.prototype.onMonitorLent_mode = function (t) {
            this.getRole('lent_param_video').hide();
            this.getRole('lent_param_gif').hide();
            if (t.val() === 'video') {
                this.getRole('lent_param_video').show();
            } else if (t.val() === 'gif') {
                this.getRole('lent_param_gif').show();
            }
            return this;
        };
        F.prototype.onMonitorLent_mode2 = function (t) {
            this.getRole('lent_param_video2').hide();
            this.getRole('lent_param_gif2').hide();
            if (t.val() === 'video') {
                this.getRole('lent_param_video2').show();
            } else if (t.val() === 'gif') {
                this.getRole('lent_param_gif2').show();
            }
            return this;
        };

        F.prototype._set_field_lent_mode = function (d, fi) {
            var mode = U.NEString(d.lent_mode, 'poster');
            fi.val(mode);
            fi.change();
            return this;
        };
        F.prototype._set_field_lent_mode2 = function (d, fi) {
            var mode = U.NEString(d.lent_mode2, 'poster');
            fi.val(mode);
            fi.change();
            return this;
        };

        F.prototype.onMonitorPoster_image = function (t) {
            var reader = new FileReader();
            reader.onloadend = this.poser_image_load_end.bindToObject(this);
            reader.readAsDataURL(t.get(0).files[0]);
            return this;
        };
        F.prototype.onMonitorPoster_image2 = function (t) {
            var reader = new FileReader();
            reader.onloadend = this.poser_image_load_end2.bindToObject(this);
            reader.readAsDataURL(t.get(0).files[0]);
            return this;
        };

        F.prototype.onMonitorGif_image = function (t) {
            var reader = new FileReader();
            reader.onloadend = this.gif_image_load_end.bindToObject(this);
            reader.readAsDataURL(t.get(0).files[0]);
            return this;
        };
        F.prototype.onMonitorGif_image2 = function (t) {
            var reader = new FileReader();
            reader.onloadend = this.gif_image_load_end2.bindToObject(this);
            reader.readAsDataURL(t.get(0).files[0]);
            return this;
        };
        F.prototype.gif_image_load_end = function (e) {
            this.getRole('gif_upload').get(0).src = e.target.result;
            return this;
        };

        F.prototype.gif_image_load_end2 = function (e) {
            this.getRole('gif_upload2').get(0).src = e.target.result;
            return this;
        };


        F.prototype.poser_image_load_end = function (e) {
            this.getRole('poster_image').get(0).src = e.target.result;
            return this;
        };
        F.prototype.poser_image_load_end2 = function (e) {
            this.getRole('poster_image2').get(0).src = e.target.result;
            return this;
        };

        F.prototype.onCommandSelect_lent_video2 = function () {
            if (!U.IntMoreOr(this.getField('id').val(), 0, null)) {
                U.TError('save first!');
                return this;
            }
            this.showLoader();
            Y.load('cdn.FileManager').done(this, this.on_lent_fm_ready2).fail(this, this.onRequiredComponentFail).always(this, this.hideLoader);
            return this;
        };
        F.prototype.onCommandSelect_lent_video = function () {
            if (!U.IntMoreOr(this.getField('id').val(), 0, null)) {
                U.TError('save first!');
                return this;
            }
            this.showLoader();
            Y.load('cdn.FileManager').done(this, this.on_lent_fm_ready).fail(this, this.onRequiredComponentFail).always(this, this.hideLoader);
            return this;
        };
        F.prototype.on_lent_fm_ready = function (x) {
            var pathbuilder = window.Eve.PathBuilder('COLLECTION', this.getField('id').val(), 'lent');
            pathbuilder.set_private(false);
            x.show().load(pathbuilder).setCallback(this, this.lent_video_select_done);
            return this;
        };
          F.prototype.on_lent_fm_ready2 = function (x) {
            var pathbuilder = window.Eve.PathBuilder('COLLECTION', this.getField('id').val(), 'lent');
            pathbuilder.set_private(false);
            x.show().load(pathbuilder).setCallback(this, this.lent_video_select_done2);
            return this;
        };
        F.prototype.lent_video_select_done = function (e) {
            if (U.NEString(e, null)) {
                this.getField('video_cdn_id').val(e);
                this.showLoader();
                jQuery.getJSON('/admin/CDNAPI/API', {action: "info", id: e})
                        .done(this.on_video_url_ready.bindToObject(this))
                        .fail(this.on_video_url_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.getField('video_cdn_id').val('');
                this.getField('video_cdn_url').val('');
            }
            return this;
        };
         F.prototype.lent_video_select_done2 = function (e) {
            if (U.NEString(e, null)) {
                this.getField('video_cdn_id2').val(e);
                this.showLoader();
                jQuery.getJSON('/admin/CDNAPI/API', {action: "info", id: e})
                        .done(this.on_video_url_ready2.bindToObject(this))
                        .fail(this.on_video_url_fail2.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            } else {
                this.getField('video_cdn_id2').val('');
                this.getField('video_cdn_url2').val('');
            }
            return this;
        };
        F.prototype.on_video_url_fail = function (x) {
            this.getField('video_cdn_url').val('');
            this.getField('video_cdn_id').val('');
            U.TError(U.NEString(x, 'network error'));
            return this;
        };
         F.prototype.on_video_url_fail2 = function (x) {
            this.getField('video_cdn_url2').val('');
            this.getField('video_cdn_id2').val('');
            U.TError(U.NEString(x, 'network error'));
            return this;
        };

        F.prototype.on_video_url_ready = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_video_url_success(d);
                }
                if (d.status === "error") {
                    return this.on_url_fail(d.error_info.message);
                }
            }
            return this.on_url_fail("invalid server response");
        };
        F.prototype.on_video_url_ready2 = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_video_url_success2(d);
                }
                if (d.status === "error") {
                    return this.on_url_fail2(d.error_info.message);
                }
            }
            return this.on_url_fail2("invalid server response");
        };

        F.prototype.on_video_url_success = function (d) {
            var url = U.NEString(U.safeObject(U.safeObject(U.safeObject(d).cdnapi).info).cdn_url, null);
            if (url) {
                this.getField('video_cdn_url').val(url);
            } else {
                this.getField('video_cdn_url').val('');
                this.getField('video_cdn_id').val('');
            }
        };
        F.prototype.on_video_url_success2 = function (d) {
            var url = U.NEString(U.safeObject(U.safeObject(U.safeObject(d).cdnapi).info).cdn_url, null);
            if (url) {
                this.getField('video_cdn_url2').val(url);
            } else {
                this.getField('video_cdn_url2').val('');
                this.getField('video_cdn_id2').val('');
            }
        };

        F.prototype._set_field_poster_image = function (d, fi) {
            fi.val('');
            if (U.NEString(d.lent_image_name, null) && U.IntMoreOr(d.id, 0, null)) {
                this.getRole('poster_image').get(0).src = ['/media/lent_poster/', d.id, '/', d.lent_image_name, '.SW_250H_250.jpg?apfs=', (new Date()).getTime()].join('');
            } else {
                this.getRole('poster_image').get(0).src = '';
            }
            return this;
        };
        F.prototype._get_field_poster_image = function () {
            return null;
        };
        F.prototype._set_field_poster_image2 = function (d, fi) {
            fi.val('');
            if (U.NEString(d.lent_image_name2, null) && U.IntMoreOr(d.id, 0, null)) {
                this.getRole('poster_image2').get(0).src = ['/media/lent_poster/', d.id, '/', d.lent_image_name2, '.SW_250H_250.jpg?apfs=', (new Date()).getTime()].join('');
            } else {
                this.getRole('poster_image2').get(0).src = '';
            }
            return this;
        };
        F.prototype._get_field_poster_image2 = function () {
            return null;
        };

        F.prototype._set_field_gif_cdn_id = function (d, fi) {
            var cdn_id = U.NEString(d.gif_cdn_id, null);
            var cdn_url = U.NEString(d.gif_cdn_url, null);
            this.getField('gif_cdn_id').val(cdn_id);
            this.getField('gif_cdn_url').val(cdn_url);
            if (cdn_id && cdn_url) {
                this.getRole('gif_upload').get(0).src = '//' + cdn_url;
            } else {
                this.getRole('gif_upload').get(0).src = '';
            }
        };
        F.prototype._get_field_gif_cdn_id = function () {
            return null;
        };
        F.prototype._set_field_gif_cdn_url = function (d, fi) {
            return this;
        };
        F.prototype._get_field_gif_cdn_url = function () {
            return null;
        };
        F.prototype._get_field_gif_image = function () {
            return null;
        };
        F.prototype._set_field_gif_image = function (d, fi) {
            fi.val('');
            return this;
        };
        
         F.prototype._set_field_gif_cdn_id2 = function (d, fi) {
            var cdn_id = U.NEString(d.gif_cdn_id2, null);
            var cdn_url = U.NEString(d.gif_cdn_url2, null);
            this.getField('gif_cdn_id2').val(cdn_id);
            this.getField('gif_cdn_url2').val(cdn_url);
            if (cdn_id && cdn_url) {
                this.getRole('gif_upload2').get(0).src = '//' + cdn_url;
            } else {
                this.getRole('gif_upload2').get(0).src = '';
            }
        };
        F.prototype._get_field_gif_cdn_id2 = function () {
            return null;
        };
        F.prototype._set_field_gif_cdn_url2 = function (d, fi) {
            return this;
        };
        F.prototype._get_field_gif_cdn_url2 = function () {
            return null;
        };
        F.prototype._get_field_gif_image2 = function () {
            return null;
        };
        F.prototype._set_field_gif_image2 = function (d, fi) {
            fi.val('');
            return this;
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
                jQuery.getJSON('/admin/MediaContent/API', {action: "get", id: id, content_type: 'ctCOLLECTION'})
                        .done(this.on_load_responce.bindToObject(this))
                        .fail(this.on_network_fail_fatal.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));
            }
            return this;
        };

        F.prototype.on_load_responce = function (d) {
            if (U.isObject(d)) {
                if (d.status === "ok") {
                    return this.on_data_success(d.data);
                }
                if (d.status === 'error') {
                    return this.on_network_fail_fatal(d.error_info.message);
                }
            }
            return this.on_network_fail_fatal("invalid server responce");
        };

        F.prototype.on_data_success = function (d) {
            this.setFields(d);
            this.image_list.set_owner_id(U.NEString(U.IntMoreOr(this.getField('id').val(), 0, null), null));
            return this;
        };

        //</editor-fold>                
        //<editor-fold defaultstate="collapsed" desc="editor">
        F.prototype.init_editor = function () {
            var cf = Y.get_loaded_component('inline.mce_cm_html');
            this.intro_editor = cf();
            this.intro_editor.setContainer(this.getRole('intro'));
            return this;
        };
        F.prototype.init_image_list = function () {
            var cf = Y.get_loaded_component('media.image_uploader');
            this.image_list = cf();
            this.image_list.setContainer(this.getRole('gallery'));
            this.image_list.set_params('media_content_poster', null);
            return this;
        };


        F.prototype._get_field_content_type = function () {
            return "ctCOLLECTION";
        };



        F.prototype._set_field_html_mode = function () {
            return this;
        };
        F.prototype._get_field_html_mode = function () {
            return ((this.intro_editor.get_check_state() ? 1 : 0) << 1) + 0;
        };

        F.prototype._set_field_intro = function (a) {
            var mode = !!(U.IntMoreOr(a.html_mode, 0, 0) & 2);
            this.intro_editor.setText(U.NEString(a.intro, ''), mode);
            return this;
        };

        F.prototype._get_field_intro = function () {
            return this.intro_editor.getText();
        };



        F.prototype._set_field_default_poster = function (d, fi) {

        };


        F.prototype._get_field_default_poster = function () {
            return this.image_list.get_default_image();
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.intro_editor.setText('', true);
            this.image_list.set_owner_id(null);
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="tabs">
        F.prototype.onTabSelectedIntro = function () {
            this.intro_editor.refresh();
            return this;
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="table">
        F.prototype.get_table_def = function () {
            return {
                id: MC,
                filters: false,
                sorter: false, //'SimpleLocal',
                paginator: false,
                perPage: [50, 100, 200, 500, 1000],
                rowKey: "content_id",
                css: MC,
                interceptClicks: false,
                columns: [
                    {id: "content_id", key: "content_id", property: "content_id", "text": "id", filter: false, sort: false},
                    {id: "content_type", key: "content_type", property: "content_type", "text": "Тип", filter: false, sort: true},
                    {id: "name", key: "name", property: "name", "text": "Наименование", filter: false, sort: true},
                    {id: "enabled", key: "enabled", property: "enabled", "text": "Вкл", filter: false, sort: true},
                    {id: "sort", key: "sort", property: "sort", "text": "Сорт", filter: false, sort: true},
                    {id: "control", key: "control", property: "control", "text": "control", filter: false, sort: false}
                ]
            };
        };
        F.prototype.init_table = function () {
            var def = this.get_table_def();
            var TMPLTS = null;
            /*<?=\ADVTable\TemplateBuilder\TemplateBuilder::F()->buildTemplatesRet(__DIR__,"TMPLTS","TPLS")?>*/
            ADVT.TemplateManager.LocalTemplateManager(MC, TMPLTS);
            this.table = ADVT.Table(def);

            this.table.addRenderer('getMC', function () {
                return MC;
            });
            this.table.addRenderer('getMD', function () {
                return MD;
            });

            this.table.addRenderer('is_element_enabled', (function (x) {
                return U.anyBool(x.enabled, false);
            }).bindToObjectWParam(this));
            this.table.addRenderer('render_ctype', (function (x) {
                return U.NEString({'ctVIDEO': "Видео", 'ctSEASON': "Сериал"}[x.content_type], x.content_type);
            }).bindToObjectWParam(this));
            //<editor-fold defaultstate="collapsed" desc="renderers">            
            this.table.addRenderer('row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                this._row_index++;
                return this._row_index;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index', (function () {
                this._row_index = U.IntMoreOr(this._row_index, 0, 0);
                return this._row_index;
            }).bindToObject(this));
            this.table.addRenderer('row_index2', (function () {
                this._row_index2 = U.IntMoreOr(this._row_index2, 0, 0);
                this._row_index2++;
                return this._row_index2;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index2', (function () {
                this._row_index2 = U.IntMoreOr(this._row_index2, 0, 0);
                return this._row_index2;
            }).bindToObject(this));
            this.table.addRenderer('row_index3', (function () {
                this._row_index3 = U.IntMoreOr(this._row_index3, 0, 0);
                this._row_index3++;
                return this._row_index3;
            }).bindToObject(this));
            this.table.addRenderer('repeat_row_index3', (function () {
                this._row_index3 = U.IntMoreOr(this._row_index3, 0, 0);
                return this._row_index3;
            }).bindToObject(this));
            //</editor-fold>            
            this.source = ADVT.DataSource.ArrayDataSource(this.table.TableOptions);
            this.table.setDataSource(this.source);
            this.table.appendTo(this.getRole('table').get(0));
            return this;
        };

        F.prototype._get_field_items = function () {
            var sa = [].concat(U.safeArray(this.source.source));
            for (var i = 0; i < sa.length; i++) {
                sa[i].natsort = i;
            }
            sa.sort(function (a, b) {
                var r = a.sort - b.sort;
                return r === 0 ? a.natsort - b.natsort : r;
            });

            return sa;
        };

        F.prototype._set_field_items = function (a, fi) {
            this.source.setSource(U.safeArray(a.items));
            return this;
        };

        //</editor-fold>



        //<editor-fold defaultstate="collapsed" desc="monitors">
        F.prototype.onMonitorMcsort = function (t) {
            t.val(U.IntOr(t.val(), 0));
            return this;
        };
        F.prototype.onMonitorSort = function (x) {
            var id = U.IntMoreOr(x.data('id'), 0, null);
            if (id) {
                var item = this.get_item_by_content_id(id);
                if (item) {
                    item.sort = U.IntOr(x.val(), 0);
                    x.val(item.sort);
                }
            }
            return this;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">

        //<editor-fold defaultstate="collapsed" desc="remove_item">
        F.prototype.onCommandItem_remove = function (x) {
            var id = U.IntMoreOr(x.data('id'), 0, null);
            if (id) {
                var item = this.get_item_by_content_id(id);
                if (item) {
                    var sa = [].concat(U.safeArray(this.source.source));
                    var index = sa.indexOf(item);
                    if (index >= 0) {
                        sa = sa.slice(0, index).concat(sa.slice(index + 1));
                        this.source.setSource(sa);
                    }
                }
            }
            return this;
        };
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="add item">
        F.prototype.onCommandAdd_item = function () {
            this.showLoader();
            Y.load('selectors.collection_item_selector')
                    .done(this, this.selector_ready)
                    .fail(this, this.onRequiredComponentFail)
                    .always(this, this.hideLoader);
            return this;
        };

        F.prototype.selector_ready = function (x) {
            x.show().set_allow_multi(true).load().setCallback(this, this.on_selected);
            return this;
        };
        F.prototype.on_selected = function (sel) {
            var items = [];
            sel = U.safeArray(sel);
            for (var i = 0; i < sel.length; i++) {
                var ci = U.safeObject(sel[i]);
                var xi = {
                    content_id: U.IntMoreOr(ci.id, 0, null),
                    content_type: U.NEString(ci.ctype, null),
                    name: U.NEString(ci.name, null),
                    common_name: U.NEString(ci.common_name, null),
                    enabled: U.anyBool(ci.enabled, false),
                    sort: 0
                };
                if (xi.content_id && xi.content_type && xi.name && xi.common_name) {
                    items.push(xi);
                }
            }

            if (items.length) {
                var sa = [].concat(U.safeArray(this.source.source));
                for (var i = 0; i < items.length; i++) {
                    var pdi = this.get_item_by_content_id(items[i].content_id);
                    if (!pdi) {
                        sa.push(items[i]);
                    }
                }
                this.source.setSource(sa);
            }
            return this;
        };
        F.prototype.get_item_by_content_id = function (x) {
            x = U.IntMoreOr(x, 0, null);
            if (x) {
                var sa = U.safeArray(this.source.source);
                for (var i = 0; i < sa.length; i++) {
                    if (x === U.IntMoreOr(sa[i].content_id, 0, null)) {
                        return sa[i];
                    }
                }
            }
            return null;
        };

        //</editor-fold>


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
        F.prototype.save = function (keep_open) {
            this._keep_open = U.anyBool(keep_open);
            var raw_data = this.getFields();
            var data = EFO.Filter.Filter().applyFiltersToHash(raw_data, this.getFilters().getSectionExport('node'));
            EFO.Filter.Filter().throwValuesErrorFirst(data, true);
//            var post_data = {
//                action: "put",
//                data: JSON.stringify(data)
//            };
            /*inserted**/
            var post_data = new FormData();
            post_data.append('action', "put");
            post_data.append('data', JSON.stringify(data));
            if (this.getField('poster_image').get(0).files.length) {
                post_data.append('poster_image', this.getField('poster_image').get(0).files[0]);
            }
            if (this.getField('gif_image').get(0).files.length) {
                post_data.append('gif_image', this.getField('gif_image').get(0).files[0]);
            }
            
            if (this.getField('poster_image2').get(0).files.length) {
                post_data.append('poster_image2', this.getField('poster_image2').get(0).files[0]);
            }
            if (this.getField('gif_image2').get(0).files.length) {
                post_data.append('gif_image2', this.getField('gif_image2').get(0).files[0]);
            }
            
            this.showLoader();
            var request = new XMLHttpRequest();
            request.onload = this.fd_request_done.bindToObjectWParam(this);
            request.onerror = this.fd_request_error.bindToObjectWParam(this);
            request.open("POST", '/admin/MediaContent/API');
            this.showLoader();
            request.send(post_data);
            /*inserted*/
//            this.showLoader();
//            jQuery.post('/admin/MediaContent/API', post_data, null, 'json')
//                    .done(this.on_post_responce.bindToObject(this))
//                    .fail(this.on_network_fail.bindToObject(this))
//                    .always(this.hideLoader.bindToObject(this));
            return this;
        };
        F.prototype.fd_request_done = function (rq) {
            this.hideLoader();
            if (rq.status === 200) {
                var rt = null;
                try {
                    rt = JSON.parse(rq.responseText);
                } catch (e) {
                    rt = null;
                }
                if (rt) {
                    return this.on_post_responce(rt);
                }
                return this.on_network_fail("invalid server response");
            }
            return this.on_network_fail("network error");
        };

        F.prototype.fd_request_error = function (rq) {
            return this.on_network_fail("network error").hideLoader();
        };
        F.prototype.on_post_responce = function (d) {
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

        F.prototype.on_network_fail = function (x) {
            U.TError(U.NEString(x, "network error"));
            return this;
        };

        F.prototype.on_network_fail_fatal = function (x) {
            this.on_network_fail.apply(this, APS.call(arguments));
            this.hide().clear();
            return this;
        };
        //</editor-fold>        
        //</editor-fold>

        //
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