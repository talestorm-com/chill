(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var E = window.Eve, EFO = E.EFO, U = EFO.U, PAR = EFO.windowController, PARP = PAR.prototype, APS = Array.prototype.slice;
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
        F.mixines = ['Roleable', 'Fieldable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">        
        F.prototype.onInit = function (product_id, params) {
            this.product_id = product_id;
            this.params = U.safeObject(params);
            PARP.onInit.apply(this, APS.call(arguments));
            this.render_button_block = this._render_button_block.bindToObject(this);
            this.init_instance.apply(this, APS.call(arguments));
            var self = this;
            this.handle.on('click', function (e) {
                if (jQuery(e.target).is(self.handle)) {                    
                    self.hide();
                }
            });
            return this;
        };

        F.prototype.init_instance = function () {

        };

        F.prototype.onAfterShow = function () {
            jQuery('body').addClass(MC + 'display');
            PARP.onAfterShow.apply(this, APS.call(arguments));
            return this;
        };
        F.prototype.onAfterHide = function () {
            jQuery('body').removeClass(MC + 'display');
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

        F.prototype.RCSS = function () {
            return MC;
        };

        F.prototype.show_loader = function () {
            this.getRole('loader_f').show();
            return this;
        };
        F.prototype.showLoader = F.prototype.show_loader;
        F.prototype.hide_loader = function () {
            this.getRole('loader_f').hide();
            return this;
        };
        F.prototype.hideLoader = F.prototype.hide_loader;

        F.prototype.getDefaultTitle = function () {
            return "";
        };
        F.prototype.enumSubTemplates = function () {
            var a = PARP.enumSubTemplates.call(this);
            a = U.isArray(a) ? a : [];
            return a.concat([
                MC + ".loader"
                        //,MC+""
            ]);
        };
        //</editor-fold>                          
        //<editor-fold defaultstate="collapsed" desc="Лоадер">      
        F.prototype.load_product = function (product_id, shop_id, color_id, size_id) { // предопределенки не обязательные
            this.showLoader();
            this.shop = U.IntMoreOr(shop_id, 0, null);
            this.selected_color = U.NEString(color_id, null);
            this.selected_sizes = [];
            size_id = U.IntMoreOr(size_id, 0, null);
            size_id ? this.selected_sizes.push(size_id) : 0;
            E.product_manager_ready = E.product_manager_ready || [];
            E.product_manager_ready.push((function () {
                E.ProductManager().load_product(product_id, this, this.on_product_loaded_int);
            }).bindToObject(this));
            return this;
        };

        F.prototype.on_product_loaded_int = function (product_info) {
            if (!this.selected_color && product_info.color_count === 1) {
                this.selected_color = product_info.colors.items[0].guid;
            }
            try {
                this.product_info = product_info;
                this.getRole('product_info').html(Mustache.render(EFO.TemplateManager().get('product_info', MC), this));
                if (product_info.color_count && product_info.color_count > 1) {
                    this.getRole('colors').html(Mustache.render(EFO.TemplateManager().get('colors', MC), this));
                    this.getRole('colors').show();
                } else {
                    this.getRole('colors').hide();
                    this.getRole('colors').html('');
                }

                if (this.shop) {
                    this.shop_object = E.ProductManager().get_offline_shop_by_id(this.shop);
                    this.getRole('shop_info').html(Mustache.render(EFO.TemplateManager().get('shop_info', MC), this));
                    this.getRole('shop_info').show();
                } else {
                    this.getRole('shop_info').hide();
                    this.getRole('shop_info').html('');
                }
                if (product_info.size_count) {
                    var size_values = [];
                    for (var i = 0; i < product_info.sizes.items.length; i++) {
                        var size = product_info.sizes.items[i];
                        var p = {id: size.id, values: [size.value]};
                        for (var j = 0; j < product_info.sizes.defs.length; j++) {
                            var key = product_info.sizes.defs[j].key;
                            p.values.push(U.NEString(U.safeObject(U.safeObject(size.alters)[key]).value, '--'));
                        }
                        size_values.push(p);
                    }
                    this.size_values = size_values;
                    this.getRole('sizes').html(Mustache.render(EFO.TemplateManager().get('size_list', MC), this));
                    this.getRole('sizes').show();
                } else {
                    this.getRole('sizes').html('');
                    this.getRole('sizes').hide();
                }
                this.getRole('button_block').html(this._render_button_block());
                if (this.display_intro()) {
                    this.getRole('introText').show();
                    this.getRole('introText').html(this.render_intro_text());
                } else {
                    this.getRole('introText').hide();
                }
                // растопыр                
                if (this.cloned_content) {
                    this.cloned_content.remove();
                }
                var html = this.getRole('windowContent').get(0).outerHTML;
                html = html.replace(/data-/ig, "atad-");
                this.cloned_content = jQuery(html);
                this.cloned_content.addClass(MC + "cloned");
                this.cloned_content.removeClass(MC + "org");
                this.getRole('window').append(this.cloned_content);
                this.getRole("windowContent").addClass(MC + "org");
                this.update_availability();

                this.on_product_loaded(product_info);
                this.hideLoader();
                this.on_before_render_complete();
                this.on_product_render_complete();
            } catch (ee) {
                U.TError(ee);
            }
            return this;
        };

        F.prototype.display_intro = function () {
            return false;
        };

        F.prototype.render_intro_text = function () {
            return null;
        };

        F.prototype.on_product_loaded = function () {
            // override
        };

        F.prototype.on_before_render_complete = function () {
            this._fields = U.scan(this.handle, 'field');
            this._roles = U.scan(this.handle, 'role');
            return this;
        };

        F.prototype.on_product_render_complete = function () {

        };

        //</editor-fold>                


        F.prototype._render_button_block = function () {
            return Mustache.render(this.get_buttons_template(), this);
        };

        F.prototype.get_buttons_template = function () {
            return '';
        };



        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            this.LEM.Run('RESET_CONTENT');
            return this;
        };
        F.prototype.hideclear = function () {
            return this.hide().clear();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="monitors">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Комманды">

        F.prototype.onCommandSelect_color = function (t) {
            this.selected_color = U.NEString(t.data('colorId'), null);
            this.update_availability();
            return this;
        };

        F.prototype.update_availability = function () {
            this.getRole('colors').find('.selected').removeClass('selected');
            if (this.selected_color) {
                this.getRole('colors').find('[data-color-id="' + this.selected_color + '"]').addClass('selected');
            }
            this.getRole('sizes').find('.selected').removeClass('selected');
            for (var i = 0; i < this.selected_sizes.length; i++) {
                this.getRole('sizes').find('[data-size="' + this.selected_sizes[i] + '"]').addClass('selected');
            }
            var shop_id = null;
            if (this.shop_object) {
                shop_id = this.shop_object.storage_id;
            }
            var color_id = null;
            if (this.selected_color) {
                color_id = this.selected_color;
            }
            if (this.product_info.size_count) {
                var sid = {};
                var found_sizes = 0;
                for (var i = 0; i < this.product_info.sizes.items.length; i++) {
                    var size = this.product_info.sizes.items[i];
                    var qty = E.ProductManager().get_filter_qty_of(this.product_info.product_id, shop_id, color_id, size.id);
                    sid[["P", size.id].join('')] = qty;
                    qty ? found_sizes++ : 0;
                }
                for (var i = 0; i < this.product_info.sizes.items.length; i++) {
                    var size = this.product_info.sizes.items[i];
                    var sk = ["P", size.id].join('');
                    this.getRole('sizes').find('[data-size=' + size.id + ']')[sid[sk] ? 'removeClass' : 'addClass']('disabled');
                }
            }

            //блокирову по цветам?
            // на завтра - проверить со всеми комбинациями. подумать о блокировке цвета
            // или как отображать ситуацию когда цвета в наличии нет
            // фикс на селекте магаза?
            // дополнит bb контактами и сделать отправку резерва (подумать - магазу нужен email)
            // сразу сделать фпз и можно приступать к корзине
            // корзина - несколько размеров?

            return this;
        };

        F.prototype.onCommandToggle_size = function (t) {
            var size_id = U.IntMoreOr(t.data('size'), 0, null);
            if (size_id) {
                var i = this.selected_sizes.indexOf(size_id);
                if (i < 0) {
                    this.selected_sizes.push(size_id);
                } else {
                    this.selected_sizes = this.selected_sizes.slice(0, i).concat(this.selected_sizes.slice(i + 1));
                }
            }
            this.update_availability();
            return this;
        };


        //</editor-fold>





        //</editor-fold>       
        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            throw new Error("component load error");
        };
        Y.reportSuccess(FQCN, F);
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