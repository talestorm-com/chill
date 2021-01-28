(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [// стиль встроен в компонент              
        Y.load('front.basket_dialog.abstract').promise
    ];
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
        F.xInheritE(Y.get_loaded_component('front.basket_dialog.abstract'));
        F.mixines = ['Roleable', 'Commandable', 'Fieldable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">        

        F.prototype.init_instance = function () {
            H = this;
        };


        F.prototype.getCssClass = function () {
            return MC;
        };


        F.prototype.get_buttons_template = function () {
            return EFO.TemplateManager().get('buttons', MC);
        };

        //</editor-fold>          

        F.prototype.onMonitorPhone = function (t) {
            t.val(EFO.Checks.tryFormatPhone(t.val()));
            return this;
        };


        F.prototype.on_product_render_complete = function () {
            jQuery.getJSON('/Basket/API', {action: 'get_user_info'})
                    .done(this.on_user_info_responce.bindToObject(this));
        };

        F.prototype.on_user_info_responce = function (d) {
            d = U.safeObject(d);
            if (d.status === 'ok') {
                var ui = U.safeObject(d.user_info);
                var name = U.NEString(ui.name, null);
                if (name) {
                    this.getField('user_name').val(name);
                }
                var phone = U.NEString(ui.phone, null);
                if (phone) {
                    this.getField('user_phone').val(EFO.Checks.tryFormatPhone(phone));
                }
                var email = U.NEString(ui.email, null);
                if (email) {
                    this.getField('user_email').val(email);
                }
            }
            return this;
        };





        F.prototype.onCommandDoPreorder = function (t) {
            try {
                var selected_color = U.NEString(this.selected_color, null);
                if (this.product_info.color_count && !selected_color) {
                    throw new Error("Выберите цвет");
                }
                var selected_sizes = U.safeArray(this.selected_sizes);
                if (this.product_info.size_count && !selected_sizes.length) {
                    throw new Error("Выберите размер!");
                }
                // проверить наличие
                

                var user_email = EFO.Checks.isEmail(this.getField('user_email').val()) ? this.getField('user_email').val() : null;
                if (!user_email) {
                    throw new Error("Укажите корректный email!");
                }
                var user_phone = EFO.Checks.formatPhone(this.getField('user_phone').val());
                if (!user_phone) {
                    throw new Error("Укажите корректный номер телефона!");
                }
                var user_name = U.NEString(this.getField('user_name').val(), null);
                if (!user_name) {
                    throw new Error("Укажите Ваше имя!");
                }

                this.showLoader();
                jQuery.post('/Basket/API', {
                    action: "preorder",
                    data: JSON.stringify({
                        "product_id": this.product_info.product_id,
                        color_id: selected_color,
                        sizes: selected_sizes,
                        phone: user_phone,
                        name: user_name,
                        email: user_email                        
                    })
                }, null, 'json')
                        .done(this.on_post_responce.bindToObject(this))
                        .fail(this.on_post_fail.bindToObject(this))
                        .always(this.hideLoader.bindToObject(this));

            } catch (ee) {
                U.Error(ee);
            }
            return this;
        };

        F.prototype.on_post_responce = function (d) {
            d = U.safeObject(d);
            if (d.status === 'ok') {
                //$this->out->add("order_id", $order_id)->add("shop_name", $shop->name)->add('shop_address', $shop->address);
                var message = ["Предзаказ № ", d.order_id, " создан.<br>Оператор скоро свяжется с Вами "].join('');
                EFO.simple_confirm()
                        .set_style("blue")
                        .set_text(message)
                        .set_title("Отправлена")
                        .set_icon("success")
                        .set_close_btn(true)
                        .set_image("success")
                        .set_buttons(["Ok"])
                        .show();
                this.clear().hide();
                return this;
            }
            if (d.status === 'error') {
                return this.on_post_fail(d.error_info.message);
            }
            return this.on_post_fail("invalid server responce");
        };
        F.prototype.on_post_fail = function (x) {
            x = U.NEString(x, 'network error');
            U.TError(x);
        };


        F.prototype.display_intro = function () {
            return true;
        };

        F.prototype.render_intro_text = function () {
            return Mustache.render(EFO.TemplateManager().get('intro', MC), this);
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
            var color_id = null;
            if (this.selected_color) {
                color_id = this.selected_color;
            }
            this.getRole('sizes').find('.disabled').removeClass('disabled');

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
                    this.selected_sizes = [];
                    this.selected_sizes.push(size_id);
                } else {
                    this.selected_sizes = [];
                }
            }
            this.update_availability();
            return this;
        };


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