$(document).ready(function () {
    var FFF = {"form1":"<div class=\"border_order\">\n<div class=\"close_btn\">\n<i class=\"mdi mdi-close\"><\/i>\n<\/div>\n<h2>\u041e\u0431\u0440\u0430\u0442\u043d\u044b\u0439 \u0437\u0432\u043e\u043d\u043e\u043a<\/h2>\n<form id=\"form_1\" class=\"row\" data-url=\"\/?page=form_report&action=form1\">\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_input\">\n<input name=\"form_2_name\" data-id=\"form_2_name\" placeholder=\"\u0418\u043c\u044f\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_input\">\n<input name=\"form_3_email\" data-id=\"form_2_email\" placeholder=\"\u0422\u0435\u043b\u0435\u0444\u043e\u043d\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_checkbox\">\n<input type=\"checkbox\" id=\"form_3_check111\" checked=\"checked\" \/>\n<label for=\"form_3_check111\">\u043d\u0430\u0436\u0438\u043c\u0430\u044f \u043d\u0430 \u043a\u043d\u043e\u043f\u043a\u0443, \u0432\u044b \u0434\u0430\u0435\u0442\u0435 \u0441\u043e\u0433\u043b\u0430\u0441\u0438\u0435 \u043d\u0430 \u043e\u0431\u0440\u0430\u0431\u043e\u0442\u043a\u0443 \u0441\u0432\u043e\u0438\u0445 \u043f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u044b\u0445 \u0434\u0430\u043d\u043d\u044b\u0445<\/label>\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_btn\">\n<button>\u041e\u0442\u043f\u0440\u0430\u0432\u0438\u0442\u044c<\/button>\n<\/div>\n<\/div>\n<\/form>\n<\/div>","form2":"<div class=\"border_order\">\n\n<div class=\"close_btn\">\n<i class=\"mdi mdi-close\"><\/i>\n<\/div>\n<h2>\u0417\u0430\u043a\u0430\u0437\u0430\u0442\u044c \u043f\u043e\u0434 \u0431\u044e\u0434\u0436\u0435\u0442<\/h2>\n<form class=\"row\" id=\"form_2\" data-url=\"\/?page=form_report&action=form2\">\n<div class=\"col s12 l6\">\n<div class=\"one_input\">\n<input name=\"form_2_how\" id=\"form_2_how\" placeholder=\"\u0421\u043a\u043e\u043b\u044c\u043a\u043e \u0440\u044e\u043a\u0437\u0430\u043a\u043e\u0432 \u0432\u0430\u043c \u043d\u0443\u0436\u043d\u043e?\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6\">\n<div class=\"one_input\">\n<input name=\"form_2_money\" data-id=\"form_2_money\" placeholder=\"\u041a\u0430\u043a\u043e\u0439 \u0443 \u0432\u0430\u0441 \u0431\u044e\u0434\u0436\u0435\u0442 \u043d\u0430 1 \u043f\u043e\u0434\u0430\u0440\u043e\u043a?\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6\">\n<div class=\"one_input\">\n<input name=\"form_2_name\" data-id=\"form_2_name\" placeholder=\"\u0418\u043c\u044f\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6\">\n<div class=\"one_input\">\n<input name=\"form_3_email\" data-id=\"form_2_email\" placeholder=\"Email\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6\">\n<div class=\"one_input\">\n<input name=\"form_2_phone\" data-id=\"form_2_phone\" placeholder=\"\u0422\u0435\u043b\u0435\u0444\u043e\u043d\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6\">\n<div class=\"one_input\">\n<input name=\"form_2_city\" data-id=\"form_2_city\" placeholder=\"\u0413\u043e\u0440\u043e\u0434\">\n<\/div>\n<\/div>\n<div class=\"col s12\">\n<div class=\"one_checkbox\">\n<input type=\"checkbox\" id=\"form_2_checkw3we3\" checked=\"checked\" \/>\n<label for=\"form_2_checkw3we3\">\u043d\u0430\u0436\u0438\u043c\u0430\u044f \u043d\u0430 \u043a\u043d\u043e\u043f\u043a\u0443, \u0432\u044b \u0434\u0430\u0435\u0442\u0435 \u0441\u043e\u0433\u043b\u0430\u0441\u0438\u0435 \u043d\u0430 \u043e\u0431\u0440\u0430\u0431\u043e\u0442\u043a\u0443 \u0441\u0432\u043e\u0438\u0445 \u043f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u044b\u0445 \u0434\u0430\u043d\u043d\u044b\u0445<\/label>\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l6\">\n<div class=\"one_btn\">\n<button>\u041e\u0442\u043f\u0440\u0430\u0432\u0438\u0442\u044c<\/button>\n<\/div>\n<\/div>\n<\/form>\n<\/div>","form3":"<div class=\"border_order\">\n<div class=\"close_btn\">\n<i class=\"mdi mdi-close\"><\/i>\n<\/div>\n<h2>\u041f\u043e\u043b\u0443\u0447\u0438\u0442\u044c \u043a\u0430\u0442\u0430\u043b\u043e\u0433<\/h2>\n<form id=\"form_3\" class=\"row\" data-url=\"\/?page=form_report&action=form3\">\n<div class=\"col s12 m6 offset-m3\">\n<div class=\"one_input\">\n<input name=\"form_3_name\" data-id=\"form_3_name\" placeholder=\"\u0418\u043c\u044f\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_input\">\n<input name=\"form_3_email\" data-id=\"form_3_email\" placeholder=\"Email\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_input\">\n<input name=\"form_3_phone\" data-id=\"form_3_phone\" placeholder=\"\u0422\u0435\u043b\u0435\u0444\u043e\u043d\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_input\">\n<input name=\"form_3_city\" data-id=\"form_3_city\" placeholder=\"\u0413\u043e\u0440\u043e\u0434\">\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_checkbox\">\n<input type=\"checkbox\" id=\"form_3_checkgvgvgvg\" checked=\"checked\" \/>\n<label for=\"form_3_checkgvgvgvg\">\u043d\u0430\u0436\u0438\u043c\u0430\u044f \u043d\u0430 \u043a\u043d\u043e\u043f\u043a\u0443, \u0432\u044b \u0434\u0430\u0435\u0442\u0435 \u0441\u043e\u0433\u043b\u0430\u0441\u0438\u0435 \u043d\u0430 \u043e\u0431\u0440\u0430\u0431\u043e\u0442\u043a\u0443 \u0441\u0432\u043e\u0438\u0445 \u043f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u044b\u0445 \u0434\u0430\u043d\u043d\u044b\u0445<\/label>\n<\/div>\n<\/div>\n<div class=\"col s12 l6 offset-l3\">\n<div class=\"one_btn\">\n<button>\u041e\u0442\u043f\u0440\u0430\u0432\u0438\u0442\u044c<\/button>\n<\/div>\n<\/div>\n<\/form>\n<\/div>","form4":"<form style=\"margin-top:0\" data-url=\"\/?page=form_report&action=form1\">\n<div class=\"row\">\n<div class=\"col s12 m4\">\n<div class=\"block_13_input\">\n<input type=\"text\" placeholder=\"\u0438\u043c\u044f\" name=\"form_2_name\">\n<\/div>\n<\/div>\n<div class=\"col s12 m4\">\n<div class=\"block_13_input\">\n<input type=\"text\" placeholder=\"\u0422\u0435\u043b\u0435\u0444\u043e\u043d\" name=\"form_3_email\">\n<\/div>\n<\/div>\n<div class=\"col s12 m4\">\n<div class=\"block_13_btn\">\n<button>\u041e\u0442\u043f\u0440\u0430\u0432\u0438\u0442\u044c<\/button>\n<\/div>\n<\/div>\n<div class=\"col s12 m4 offset-m8\">\n<div class=\"one_checkbox\">\n<input type=\"checkbox\" id=\"form_2_check\" checked=\"checked\">\n<label for=\"form_2_check\">\u043d\u0430\u0436\u0438\u043c\u0430\u044f \u043d\u0430 \u043a\u043d\u043e\u043f\u043a\u0443, \u0432\u044b \u0434\u0430\u0435\u0442\u0435 \u0441\u043e\u0433\u043b\u0430\u0441\u0438\u0435 \u043d\u0430 \u043e\u0431\u0440\u0430\u0431\u043e\u0442\u043a\u0443 \u0441\u0432\u043e\u0438\u0445 \u043f\u0435\u0440\u0441\u043e\u043d\u0430\u043b\u044c\u043d\u044b\u0445 \u0434\u0430\u043d\u043d\u044b\u0445<\/label>\n<\/div>\n<\/div>\n<\/div>\n<\/form>"};
    $("body").on("click", ".close_btn", function () {
        $(".order").fadeOut(0);
        $(".bg_bg").fadeOut(0);
        $("body").css("overflow-y", "scroll");
    });
    $("#zakaz_zvonka").click(function () {
        $("#zakaz_zvonka_block").html(FFF.form1);
        $("#zakaz_zvonka_block").fadeIn(0);
        $("body").css("overflow-y", "hidden");
        $(".bg_bg").fadeIn(0);
        var a = $(window).width();
        var b = $(window).height();
        var c = $("#zakaz_zvonka_block").width();
        var d = $("#zakaz_zvonka_block").height();
        var aa = (a - c) / 2 - 10;
        var bb = (b - d) / 2 - 10;
        $("#zakaz_zvonka_block").css("left", aa);
        $("#zakaz_zvonka_block").css("top", bb);
        $("#zakaz_zvonka_block").find('button').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            var form = jQuery(this).closest('form');
            var button = jQuery(this);
            var name = jQuery.trim(form.find('input[name=form_2_name]').val());
            if (!(name && name.length)) {
                alert("Укажите имя!");
                return;
            }
            var phone = jQuery.trim(form.find('input[name=form_3_email]').val());
            if (!(phone && phone.length)) {
                alert("Укажите телефон");
                return;
            }
            if (!/^\d{10,}$/.test(phone.replace(/\D/g, ''))) {
                alert("Неверно указан номер!");
                return;
            }
            if (!form.find('input[type=checkbox]').prop('checked')) {
                alert("Требуется согласие на обработку персональных данных");
                return;
            }

            button.hide();
            jQuery.post(form.data('url'), {name: name, phone: phone, "c": "form1"}, null, 'json')
                    .done(function (d) {
                        var d = (d && typeof (d) === 'object') ? d : {};
                        if (d.status === 'ok') {
                            $("#zakaz_zvonka_block").hide();
                            $(".bg_bg").hide();
                            $("body").css("overflow-y", "scroll");
                            alert("Отправлено. \nОператор свяжется с Вами в ближайшее рабочее время");
                            return;
                        }
                        alert("Ошибка!\nПопробуйте отправить запрос позднее");
                    })
                    .fail(function () {
                        alert("Ошибка!\nПопробуйте отправить запрос позднее");
                    })
                    .always(function () {
                        button.show();
                    });
        });
    });


    $("#poluchit_pod_budjet").click(function () {
        $("#zakaz_block_1").html(FFF.form2)
        $("#zakaz_block_1").fadeIn(0);
        $("body").css("overflow-y", "hidden");
        $(".bg_bg").fadeIn(0);
        var a = $(window).width();
        var b = $(window).height();
        var c = $("#zakaz_block_1").width();
        var d = $("#zakaz_block_1").height();
        var aa = (a - c) / 2 - 10;
        var bb = (b - d) / 2 - 10;
        $("#zakaz_block_1").css("left", aa);
        $("#zakaz_block_1").css("top", bb);
        $("#zakaz_block_1").find('button').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            var form = jQuery(this).closest('form');
            var button = jQuery(this);
            var qty = parseInt(form.find('input[name=form_2_how]').val());
            var money = parseFloat(jQuery.trim(form.find('input[name=form_2_money]').val()).replace(/,/g, '.'));
            var name = jQuery.trim(form.find('input[name=form_2_name]').val());
            var email = jQuery.trim(form.find('input[name=form_3_email]').val());
            var phone = jQuery.trim(form.find('input[name=form_2_phone]').val());
            var city = jQuery.trim(form.find('input[name=form_2_city]').val());
            var check = form.find('input[type=checkbox]').prop('checked');
            if (!qty || isNaN(qty)) {
                alert("Укажите количество");
                return;
            }
            if (money < 1 || isNaN(money)) {
                alert("Укажите сумму");
                return;
            }
            if (!(name && name.length)) {
                alert("Укажите имя");
                return;
            }
            if (!(city && city.length)) {
                alert("Укажите город");
                return;
            }
            if (!(phone && phone.length)) {
                alert("Укажите телефон");
                return;
            }
            if (!/^\d{10,}$/.test(phone.replace(/\D/g, ''))) {
                alert("Неверно указан номер телефона!");
                return;
            }
            if (!email || !email.length || !/^[^@]{1,}@[^@]{1,}\.[^@\.]{1,}$/.test(email)) {
                alert("укажите корректный email");
                return;
            }
            if (!check) {
                alert("Требуется согласие на обработку персональных данных");
                return;
            }

            button.hide();
            jQuery.post(form.data('url'), {
                name: name, c: "form2", qty: qty, money: money.toFixed(2), email: email, phone: phone, city: city
            }, null, 'json')
                    .always(function () {
                        button.show();
                    })
                    .done(function (d) {
                        d = (d && typeof (d) === 'object') ? d : {};
                        if (d.status === 'ok') {
                            $("#zakaz_block_1").hide();
                            $("body").css("overflow-y", "scroll");
                            $(".bg_bg").hide();
                            alert("Запрос отправлен!\nСпасибо!");
                            return;
                        }
                        alert("Ошибка!\nПопробуйте повторить запрс позднее!");
                    })
                    .fail(function () {
                        alert("Ошибка!\nПопробуйте повторить запрс позднее!");
                    });

        });
    });
    $("#block_2btn_open").click(function () {
        $("#zakaz_katalog").html(FFF.form3);
        $("#zakaz_katalog").fadeIn(0);
        $("body").css("overflow-y", "hidden");
        $(".bg_bg").fadeIn(0);
        var a = $(window).width();
        var b = $(window).height();
        var c = $("#zakaz_katalog").width();
        var d = $("#zakaz_katalog").height();
        var aa = (a - c) / 2 - 10;
        var bb = (b - d) / 2 - 10;
        $("#zakaz_katalog").css("left", aa);
        $("#zakaz_katalog").css("top", bb);
        $("#zakaz_katalog").find('button').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            var form = jQuery(this).closest('form');
            var button = jQuery(this);
            var name = jQuery.trim(form.find('input[name=form_3_name]').val());
            var email = jQuery.trim(form.find('input[name=form_3_email]').val());
            var phone = jQuery.trim(form.find('input[name=form_3_phone]').val());
            var city = jQuery.trim(form.find('input[name=form_3_city]').val());
            var check = form.find('input[type=checkbox]').prop('checked');
            if (!(name && name.length)) {
                alert("Укажите имя");
                return;
            }
            if (!email || !email.length || !/^[^@]{1,}@[^@]{1,}\.[^@\.]{1,}$/.test(email)) {
                alert("укажите корректный email");
                return;
            }
            if (!(phone && phone.length)) {
                alert("Укажите телефон");
                return;
            }
            if (!/^\d{10,}$/.test(phone.replace(/\D/g, ''))) {
                alert("Неверно указан номер телефона!");
                return;
            }
            if (!(city && city.length)) {
                alert("Укажите город");
                return;
            }
            if (!check) {
                alert("Требуется согласие на обработку персональных данных");
                return;
            }
            button.hide();
            jQuery.post(form.data('url'), {
                name: name, c: "form3", email: email, phone: phone, city: city
            }, null, 'json')
                    .always(function () {
                        button.show();
                    })
                    .done(function (d) {
                        d = (d && typeof (d) === 'object') ? d : {};
                        if (d.status === 'ok') {
                            $("#zakaz_katalog").hide();
                            $("body").css("overflow-y", "scroll");
                            $(".bg_bg").hide();
                            alert("Запрос отправлен!\nСпасибо!");
                            return;
                        }
                        alert("Ошибка!\nПопробуйте повторить запрс позднее!");
                    })
                    .fail(function () {
                        alert("Ошибка!\nПопробуйте повторить запрс позднее!");
                    });
        });
    });

    window.setTimeout(function () {
        jQuery('#ifyouhavaquestion').html(FFF.form4);
        jQuery('#ifyouhavaquestion').find('button').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault ? e.preventDefault() : e.returnValue = false;
            var form = jQuery(this).closest('form');
            var button = jQuery(this);
            var name = jQuery.trim(form.find('input[name=form_2_name]').val());
            if (!(name && name.length)) {
                alert("Укажите имя!");
                return;
            }
            var phone = jQuery.trim(form.find('input[name=form_3_email]').val());
            if (!(phone && phone.length)) {
                alert("Укажите телефон");
                return;
            }
            if (!/^\d{10,}$/.test(phone.replace(/\D/g, ''))) {
                alert("Неверно указан номер!");
                return;
            }
            if (!form.find('input[type=checkbox]').prop('checked')) {
                alert("Требуется согласие на обработку персональных данных");
                return;
            }

            button.css('visibility','hidden');
            jQuery.post(form.data('url'), {name: name, phone: phone, "c": "form1"}, null, 'json')
                    .done(function (d) {
                        var d = (d && typeof (d) === 'object') ? d : {};
                        if (d.status === 'ok') {
                            form.find('input[type=text]').each(function(){
                                this.value='';
                            });
                            alert("Отправлено. \nОператор свяжется с Вами в ближайшее рабочее время");
                            return;
                        }
                        alert("Ошибка!\nПопробуйте отправить запрос позднее");
                    })
                    .fail(function () {
                        alert("Ошибка!\nПопробуйте отправить запрос позднее");
                    })
                    .always(function () {
                        button.css('visiblity','visible')
                    });
        });
    }, 500);
});
