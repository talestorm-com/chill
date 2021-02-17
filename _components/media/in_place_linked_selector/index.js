(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент                      
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Callbackable', 'Sizeable'];
        U.initMixines(F);
        F.prototype.MD = MD;

        F.prototype.mallow_multi = true;
        F.prototype.menable_clear = false;
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
            return 80;
        };
        F.prototype.sizeable_defaultHeight = function () {
            return 80;
        };
        //</editor-fold>
        F.prototype.onInit = function () {
            H = this;
            PARP.onInit.apply(this, APS.call(arguments));
            this.LEM.On('NEED_POSITE', this, this.placeAtCenter);
            this.get_image_url = this._get_image_url.bindToObjectWParam(this);
            return this;
        };

        F.prototype._get_image_url = function (x) {

            return ["/media/", x.context, "/", x.owner_id, "/", x.image, ".SW_200H_200CF_1.jpg?csp=", (new Date()).getTime()].join('');
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
                {'command': "apply", 'text': "Применить"}
            ];
        };
        F.prototype.getDefaultTitle = function () {
            return "Выбор картинки";
        };
        //</editor-fold>   

        F.prototype.set_multi = function (x) {
            x = U.anyBool(x, F.prototype.mallow_multi);
            this.mallow_multi = x;
            return this;
        };

        F.prototype.enable_clear = function (x) {
            x = U.anyBool(x, F.prototype.menable_clear);
            this.menable_clear = x;
            return this;
        };

        F.prototype.set_source = function (x) {
            var c = [].concat(U.safeArray(x.image_list));
            this.render_images(c);
            return this;
        };

        F.prototype.render_images = function (x) {
            this._images = U.safeArray(x);
            this.getRole('list').html(Mustache.render(EFO.TemplateManager().get('image_list', MC), this, {
                'one_image': EFO.TemplateManager().get('one_image', MC),
                'one_clear': EFO.TemplateManager().get('clear_image', MC)
            }));
            this._images = null;
            if (this.menable_clear) {
                this.handle.addClass(MC + "EnableClear");
            } else {
                this.handle.removeClass(MC + "EnableClear");
            }
            return this;
        };


        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            this.clearCallbacks();
            this.mallow_multi = F.prototype.mallow_multi;
            this.menable_clear = F.prototype.menable_clear;
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
        F.prototype.onCommandApply = function () {
            this.save(false);
            return this;
        };
        //</editor-fold>
        //
        F.prototype.onCommandSelect_me = function (t) {
            if (!this.mallow_multi) {
                this.handle.find(['.', MC, "SelectedItem"].join('')).removeClass([MC, "SelectedItem"].join(''));
            }
            this.handle.find(['.', MC, 'OneImageClear'].join('')).removeClass([MC, "SelectedItem"].join(''));
            t.toggleClass([MC, "SelectedItem"].join(''));
            return this;
        };

        F.prototype.onCommandClear = function (t) {
            this.handle.find(['.', MC, "SelectedItem"].join('')).removeClass([MC, "SelectedItem"].join(''));
            this.handle.find(['.', MC, 'OneImageClear'].join('')).addClass([MC, "SelectedItem"].join(''));
            return this;
        };
        //</editor-fold>




        //<editor-fold defaultstate="collapsed" desc="save">         
        F.prototype.save = function () {
            var selected_items = [];
            this.handle.find(['.', MC, "SelectedItem"].join('')).each(function () {
                var t = jQuery(this);
                if (U.NEString(t.data('command')) === 'clear') {
                    selected_items.push(null);
                } else {
                    selected_items.push({
                        context: U.NEString(t.data('context'), null),
                        owner_id: U.NEString(t.data('ownerId'), null),
                        image: U.NEString(t.data('image'), null)
                    });
                }
            });
            if (!selected_items.length) {
                U.TError(MC + ":nothing selected");
                return this;
            }
            this.runCallback(this.mallow_multi ? selected_items : selected_items.slice(0, 1));
            return this.hide().clear();
        };
        //</editor-fold>        
        //</editor-fold>      
        //
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