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





        F.prototype.onCommandDoReserve = function (t) {
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
                var PM = E.ProductManager();
                if (!this.shop_object) {
                    debugger;
                }
                if (selected_sizes.length) {
                    for (var i = 0; i < selected_sizes.length; i++) {
                        if (!PM.get_filter_qty_of(this.product_info.product_id, this.shop_object.storage_id, this.selected_color, this.selected_sizes[i])) {
                            U.Error(["Размер \"", this.product_info.sizes.get_value_by_id(selected_sizes[i]), "\" отсутствует в выбраном магазине"].join(''));
                        }
                    }
                } else {
                    if (!PM.get_filter_qty_of(this.product_info.product_id, this.shop_object.storage_id, this.selected_color, null)) {
                        U.Error("Товар отсутствует в выбранном магазине");
                    }
                }

                var user_email = EFO.Checks.isEmail(this.getField('user_email').val()) ? this.getField('user_email').val() : null;
                if (!user_email) {
                    throw new Error("Укажите коррентый email!");
                }
                var user_phone = EFO.Checks.formatPhone(this.getField('user_phone').val());
                if (!user_phone) {
                    throw new Error("Укажите коррентый номер телефона!");
                }
                var user_name = U.NEString(this.getField('user_name').val(), null);
                if (!user_name) {
                    throw new Error("Укажите Ваше имя!");
                }

                this.showLoader();
                jQuery.post('/Basket/API', {
                    action: "reserve",
                    data: JSON.stringify({
                        "product_id": this.product_info.product_id,
                        color_id: selected_color,
                        sizes: selected_sizes,
                        phone: user_phone,
                        name: user_name,
                        email: user_email,
                        shop_id: this.shop_object.id
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
                var message = ["Резерв № ", d.order_id, " в магазине \"", d.shop_name, "\" создан.<br>Пожалуйста дождитесь звонка оператора. "].join('');
                EFO.simple_confirm()
                        .set_style("blue")
                        .set_text(message)
                        .set_title("Резерв создан")
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