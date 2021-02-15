(function () {
    try {
        if ((!!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform)) || (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream)) {
            document.getElementsByTagName('body')[0].classList.add('DeviceModeIOS');
        }
    } catch (e) {

    }
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready1);
    function ready1() {
        jQuery(ready);
    }

    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U;
        var doc_padding = null;
        var login_form = null;
        function on_scroll(e) {
            if (!doc_padding) {
                doc_padding = U.FloatMoreOr(jQuery('.FrontLayoutPageOuter').css('padding-top'), 0);
            }
            var c = U.FloatMoreOr(jQuery(window).scrollTop(), 0);
            if (c > doc_padding) {
                jQuery('body').addClass("ScrolledTopLayout");
            } else {
                jQuery('body').removeClass("ScrolledTopLayout");
            }
        }

        jQuery(window).on('scroll', on_scroll);
        on_scroll();

        jQuery('body').on('click', '[data-command=add_product_basket]', function (e) {
            var t = jQuery(this);
            add_product_basket(t, t.data(), e);
        });


        function add_product_basket(n, data, e) {
            data = U.safeObject(data);
            var id = U.IntMoreOr(data.productId, 0, null);
            if (id) {
                try {
                    window.show_global_loader();
                } catch (e) {
                }
                EFO.Com().load('front.basket_dialog.basket')
                        .done(window, function (x) {
                            x.show().load_product(id, null, null, null);
                        }).always(window, function () {
                    try {
                        window.hide_global_loader();
                    } catch (e) {
                    }
                });
            }
        }

        jQuery('#header_main_menu_toggler').on('click', function () {
            jQuery('#header_main_menu_body_view').fadeIn(300);
            jQuery('body').addClass("MainMenuOpenMode");
        });

        jQuery('.FrontLayoutMainMenuOpenHeaderContentItem.MainMenuCloseButton').on('click', function () {
            jQuery('#header_main_menu_body_view').fadeOut(300);
            jQuery('body').removeClass("MainMenuOpenMode");
        });


        jQuery('.MainMenuItemL2Header').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            jQuery(this).parent().toggleClass('MainMenuItemL2opened');
        });


        function run_login() {
            try {
                window.show_global_loader();
            } catch (e) {
            }
            EFO.Com().load('front.login_form')
                    .done(window, function (x) {
                        x.show();
                    })
                    .always(window, function () {
                        try {
                            window.hide_global_loader();
                        } catch (e) {
                        }
                    });
        }
         function run_register() {
            try {
                window.show_global_loader();
            } catch (e) {
            }
            EFO.Com().load('front.register_form')
                    .done(window, function (x) {
                        x.show();
                    })
                    .always(window, function () {
                        try {
                            window.hide_global_loader();
                        } catch (e) {
                        }
                    });
        }


        jQuery(document).on('click', '.login_trigger,.FooterMenuColumnRowCustom-login_trigger', function (e) {
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            run_login();
        });

        jQuery(document).on('click', '.register_trigger', function (e) {
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            run_register();
        });

        jQuery(document).on('click', '[data-command=add_to_favorite]', function () {
            var t = jQuery(this);
            var product_id = U.IntMoreOr(t.data('id'), 0, null);
            if (product_id) {
                if (t.hasClass("NowFavorite")) {
                    jQuery.getJSON("/Auth/API", {action: "remove_favorite", favorite_id: product_id})
                            .done(function () {
                                EFO.Events.GEM().run('PRODUCT_FAVORITE', product_id, false);
                            });
                } else {
                    jQuery.getJSON("/Auth/API", {action: "add_favorite", favorite_id: product_id})
                            .done(function (d) {
                                if (U.isObject(d) && d.status === "ok") {
                                    EFO.Events.GEM().run('PRODUCT_FAVORITE', product_id, true);
                                    return;
                                }
                                if (U.isObject(d) && d.status === 'error' && d.error_info.message === 'login_required') {
                                    run_login();
                                    return;
                                }
                            });
                }
            }
            //data-command="add_to_favorite" data-id="{$this->product->id}" 
        });




        function run_search() {
            try {
                window.show_global_loader();
            } catch (ee) {

            }
            window.Eve.EFO.Com().load('front.serach_form')
                    .done(window, function (x) {
                        x.show();
                    })
                    .always(window, function () {
                        try {
                            window.hide_global_loader();
                        } catch (e) {

                        }
                    });
        }

        jQuery(document).on('click', '[data-command=perform_search]', function (e) {
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            run_search();
        });





    }
})();