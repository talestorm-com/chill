<!DOCTYPE html>
<html lang="ru-RU">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Capital Solutions</title>
        <base href=".">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" rel="stylesheet" href="/assets/front_capital_main/css/materialize.min.css" media="screen,projection">
        <link href="/assets/front_capital_main/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="/assets/front_capital_main/css/main.css" media="screen">
        <script type="text/javascript" src="/assets/front_capital_main/js/jquery-2.2.1.min.js"></script>
        <script type="text/javascript" src="/assets/front_capital_main/js/materialize.min.js"></script>
        <script type="text/javascript" src="/assets/front_capital_main/js/main.js"></script>
    </head>
    <body>
        <div id="lk_header">
            <ul id="menu_header">
                <li id="new_zaya" class="active">Новая заявка</li>
                <li id="all_zaya">Заявки</li>
                <li id="zav_zaya">Завершенные</li>
            </ul>
            <div id="exit_btn" >
                <a href="/Auth/Logout">Выход</a>
            </div>
        </div>
        <div id="new_zaya_main">
            <div class="container">
                <h2>Новая заявка</h2>
                <div id="new_zaya_form">
                    <form id="zayavka">
                        <div class="row">
                            <div class="col s12 l4">
                                <div class="select_block">
                                    <select id="zaya_select">
                                        <option disabled selected value="0">Сфера деятельности</option>
                                        {foreach from=$profiles_all item='profile'} 
                                            <option value="{$profile.id}">{$profile.name}</option>                                        
                                        {/foreach}                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col s12 l4">
                                <div class="input_block">
                                    <input type="text" placeholder="Название компании" id="zaya_comp_name">
                                </div>
                            </div>
                            <div class="col s12 l4">
                                <div class="input_block">
                                    <input type="text" placeholder="Адрес компании" id="zaya_address">
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="textarea_block">
                                    <textarea placeholder="Реквизиты" id="zaya_rekv"></textarea>
                                </div>
                            </div>
                        </div>
                        <h4>Инвойс</h4>
                        <div class="row">
                            <div class="col s8">
                                <div class="input_block">
                                    <input type="text" placeholder="Наименование услуги" id="zaya_usl">
                                </div>
                            </div>
                            <div class="col s4">
                                <div class="input_block">
                                    <input type="text" placeholder="Cумма без НДС, €" id="zaya_price" data-monitor="calc">
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input_block">
                                    <input type="text" placeholder="НДС, %" id="zaya_nds_pc" data-monitor="calc">
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="input_block">
                                    <input type="text" placeholder="НДС, €" id="zaya_nds_eur" data-monitor="calc">
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="zaya_result_block">
                                    Общая сумма:<span id="zaya_result_value">0</span> &euro;
                                </div>
                            </div>
                        </div>
                        <h4>Способ связи</h4>
                        <div class="row">
                            <div class="col s6">
                                <div class="input_block">
                                    <input type="text" placeholder="Номер телефона" id="zaya_phn">
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="check_block">
                                    <p>
                                        <input type="checkbox" id="zaya_telegram" />
                                        <label for="zaya_telegram">Telegram</label>
                                    </p>
                                    <p>
                                        <input type="checkbox" id="zaya_whatsapp" />
                                        <label for="zaya_whatsapp">Whatsapp</label>
                                    </p>
                                    <p>
                                        <input type="checkbox" id="zaya_viber" />
                                        <label for="zaya_viber">Viber</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="zaya_btn">
                            <button>Отправить</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <div id="all_zaya_main" style="display:none">
            <div class="container">
                <h2>Мои заявки</h2>
                <div id="zaya_list">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Сфера</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th class="center-align"></th>
                            </tr>
                        </thead>
                        <tbody id="inprogress_list">
                            <tr>
                                <td>101</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">В работе</td>
                                <td class="center-align"><a class="cancel"><i class="mdi mdi-close-circle-outline"></i> <span>Отменить</span></a></td>
                            </tr>
                            <tr>
                                <td>102</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">Новая</td>
                                <td class="center-align"><a class="cancel"><i class="mdi mdi-close-circle-outline"></i> <span>Отменить</span></a></td>
                            </tr>
                            <tr>
                                <td>103</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">К отправке</td>
                                <td class="center-align"><a class="cancel"><i class="mdi mdi-close-circle-outline"></i> <span>Отменить</span></a></td>
                            </tr>
                            <tr>
                                <td>104</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">Отправлена</td>
                                <td class="center-align"><a class="cancel"><i class="mdi mdi-close-circle-outline"></i> <span>Отменить</span></a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="zav_zaya_main" style="display:none">
            <div class="container">
                <h2>Мои заявки</h2>
                <div id="zaya_list">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Сфера</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th class="center-align"></th>
                            </tr>
                        </thead>
                        <tbody id="finished_list">

                            <tr>
                                <td>105</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">Завершенная</td>
                                <td class="center-align"></td>
                            </tr>
                            <tr>
                                <td>105</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">Завершенная</td>
                                <td class="center-align"></td>
                            </tr>
                            <tr>
                                <td>105</td>
                                <td>Строительство</td>
                                <td>10.12.2019 17:36</td>
                                <td class="bold">Завершенная</td>
                                <td class="center-align"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="bg_bg">
        </div>
        <div id="uveren">
            <div class="uveren_in">
                <h3>Вы уверены?</h3>
                <p>Отмененную заявку восстановить нельзя. Отменить заявку?</p>
                <div class="uveren_niz">
                    <button id="da">Да</button>
                    <button id="net">Нет</button>
                </div>
            </div>
        </div>
        <div style="display:none" id="loader_stub">
            <div class="loader-outer">
                <div class="loader-inner">
                    <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="100px" height="100px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M59.6 0h8v40h-8V0z" fill="#000000" fill-opacity="1"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(30 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(60 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(90 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(120 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#b2b2b2" fill-opacity="0.3" transform="rotate(150 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#999999" fill-opacity="0.4" transform="rotate(180 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#7f7f7f" fill-opacity="0.5" transform="rotate(210 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#666666" fill-opacity="0.6" transform="rotate(240 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#4c4c4c" fill-opacity="0.7" transform="rotate(270 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#333333" fill-opacity="0.8" transform="rotate(300 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#191919" fill-opacity="0.9" transform="rotate(330 64 64)"/><animateTransform attributeName="transform" type="rotate" values="0 64 64;30 64 64;60 64 64;90 64 64;120 64 64;150 64 64;180 64 64;210 64 64;240 64 64;270 64 64;300 64 64;330 64 64" calcMode="discrete" dur="960ms" repeatCount="indefinite"></animateTransform></g></svg>
                </div>
            </div>
        </div>
        <script>
            {literal}
                jQuery(function () {
                    jQuery('#new_zaya_main [data-monitor=calc]').on('change',function(e){
                        var data = get_form_data();
                        var nds_summ = parseFloat(data.nds_eur);
                        nds_summ = nds_summ === null || nds_summ === void(0) || isNaN(nds_summ) ? 0 : nds_summ;
                        nds_summ = Math.max(0,nds_summ);
                        var invoice_summ  = parseFloat(data.position_cost);
                        invoice_summ = invoice_summ === null || invoice_summ === void(0) || isNaN(invoice_summ) ? 0 : invoice_summ;
                        invoice_summ = Math.max(0,invoice_summ);
                        var result = Math.round( (invoice_summ + nds_summ)*100) / 100; 
                        jQuery('#zaya_result_value').html(result);
                    });
                    
                    
                    
                    var id_to_remove = void(0);
                    function show_loader(n) {
                        n.html(['<tr class="loader-row" ><td colspan="5">', jQuery("#loader_stub").html(), "</td></tr>"].join(''));
                    }


                    function render_flow_items(items) {
                        if (!items.length) {
                            render_flow_error("Активных заявок не найдено!");
                            return;
                        }
                        var r = [];
                        for (var i = 0; i < items.length; i++) {
                            var t = [
                                "<tr><td>", items[i].id, "</td>",
                                "<td>", items[i].profile_name, "</td>",
                                "<td>", items[i].acreated, "</td>",
                                "<td class=\"bold\" style=\"color:", items[i].status_color, "\">", items[i].status_name, "</td>",
                                "<td class=\"center-align\"><a class=\"cancel remove_request\" data-id=\"", items[i].id, "\"><i class=\"mdi mdi-close-circle-outline\"></i> <span>Отменить</span></a></td></tr>"
                            ];
                            r.push(t.join(''));
                        }
                        jQuery('#inprogress_list').html(r.join(''));
                    }
                    function render_finished_items(items) {                        
                        if (!items.length) {
                            render_finished_error("Архивных заявок не найдено!");
                            return;
                        }
                        var r = [];
                        for (var i = 0; i < items.length; i++) {
                            var t = [
                                "<tr><td>", items[i].id, "</td>",
                                "<td>", items[i].profile_name, "</td>",
                                "<td>", items[i].acreated, "</td>",
                                "<td class=\"bold\" style=\"color:", items[i].status_color, "\">", items[i].status_name, "</td>",
                                "<td class=\"center-align\"></td></tr>"
                            ];
                            r.push(t.join(''));
                        }
                        jQuery('#finished_list').html(r.join(''));
                    }

                    function render_flow_error(m) {
                        jQuery('#inprogress_list').html([
                            "<tr class=\"flow-error-message\"><td colspan=\"5\">", m, "</td></tr>"
                        ].join(''));
                    }
                    function render_finished_error(m) {
                        jQuery('#finished_list').html([
                            "<tr class=\"flow-error-message\"><td colspan=\"5\">", m, "</td></tr>"
                        ].join(''));
                    }


                    function load_flow() {
                        jQuery('#inprogress_list').html('');
                        show_loader(jQuery('#inprogress_list'));
                        jQuery.getJSON('/Cabinet/API', {action: "flow_list"})
                                .done(function (d) {
                                    if (d.status === "ok") {
                                        render_flow_items(d.items);
                                        return;
                                    }
                                    if (d.status === "error") {
                                        render_flow_error(d.error_info.message);
                                        return;
                                    }
                                    render_flow_error("Некорректный ответ сервера");
                                })
                                .fail(function () {
                                    render_flow_error("Ошибка связи с сервером");
                                });
                    }


                    function load_archive() {
                        jQuery('#finished_list').html('');
                        show_loader(jQuery('#finished_list'));
                        jQuery.getJSON('/Cabinet/API', {action: "finished_list"})
                                .done(function (d) {
                                    if (d.status === "ok") {
                                        debugger;
                                        render_finished_items(d.items);
                                        return;
                                    }
                                    if (d.status === "error") {
                                        debugger;
                                        render_finished_error(d.error_info.message);
                                        return;
                                    }
                                    render_finished_error("Некорректный ответ сервера");
                                })
                                .fail(function () {
                                    render_finished_error("Ошибка связи с сервером");
                                });
                    }

                    $("#all_zaya").click(function () {
                        $("#new_zaya_main").fadeOut(0);
                        $("#all_zaya_main").fadeIn(0);
                        $("#zav_zaya_main").fadeOut(0);

                        $("#zav_zaya").removeClass("active");
                        $("#new_zaya").removeClass("active");
                        $("#all_zaya").addClass("active");
                        load_flow();
                    });
                    $("#zav_zaya").click(function () {
                        $("#new_zaya_main").fadeOut(0);
                        $("#all_zaya_main").fadeOut(0);
                        $("#zav_zaya_main").fadeIn(0);

                        $("#zav_zaya").addClass("active");
                        $("#new_zaya").removeClass("active");
                        $("#all_zaya").removeClass("active");
                        load_archive();
                    });

                    jQuery('#inprogress_list').on('click', '.remove_request', function (e) {
                        var t = jQuery(this);
                        id_to_remove = parseInt(t.data('id'));
                        if (!isNaN(id_to_remove) && id_to_remove !== null && id_to_remove > 0) {
                            jQuery(".bg_bg").fadeIn(0);
                            jQuery("#uveren").fadeIn(0);
                        }
                    });
                    jQuery('.uveren_niz #da').on('click', function (e) {
                        jQuery(".bg_bg").fadeOut(0);
                        jQuery("#uveren").fadeOut(0);
                        if (id_to_remove) {
                            show_loader(jQuery('#inprogress_list'));
                            jQuery.getJSON('/Cabinet/API', {action: "flow_list_remove", id_to_remove: id_to_remove})
                                    .done(function (d) {
                                        if (d.status === "ok") {
                                            render_flow_items(d.items);
                                            return;
                                        }
                                        if (d.status === "error") {
                                            render_flow_error(d.error_info.message);
                                            return;
                                        }
                                        render_flow_error("Некорректный ответ сервера");
                                    })
                                    .fail(function () {
                                        render_flow_error("Ошибка связи с сервером");
                                    });
                        }
                    });






                    function get_form_data() {
                        var r = {
                            profile: jQuery('#zaya_select').val(),
                            company_name: jQuery("#zaya_comp_name").val(),
                            company_address: jQuery('#zaya_address').val(),
                            requisite: jQuery('#zaya_rekv').val(),
                            position_name: jQuery('#zaya_usl').val(),
                            position_cost: jQuery('#zaya_price').val(),
                            phone: jQuery('#zaya_phn').val(),
                            telegramm: jQuery('#zaya_telegram').prop('checked'),
                            whatsapp: jQuery('#zaya_whatsapp').prop('checked'),
                            viber: jQuery('#zaya_viber').prop('checked'),
                            nds_pc:jQuery('#zaya_nds_pc').val(),
                            nds_eur:jQuery('#zaya_nds_eur').val()
                        };
                        return r;
                    }

                    function check_data(data) {
                        var c = parseInt(data.profile);
                        if (isNaN(c) || c === null || c < 1) {
                            throw new Error("Выберите сферу деятельности");
                        }
                        var c = jQuery.trim(data.company_name);
                        if (!c || !c.length) {
                            throw new Error("Укажите название компании");
                        }
                        var c = jQuery.trim(data.company_address);
                        if (!c || !c.length) {
                            throw new Error("Укажите адрес компании");
                        }
                        var c = jQuery.trim(data.requisite);
                        if (!c || !c.length) {
                            throw new Error("Укажите реквизиты компании");
                        }
                        var c = jQuery.trim(data.position_name);
                        if (!c || !c.length) {
                            throw new Error("Укажите наименование услуги");
                        }
                        var c = jQuery.trim(data.position_cost).replace(/,/g, '.');
                        var cc = parseFloat(c);
                        if (!cc || isNaN(cc) || cc <= 0) {
                            throw new Error("Укажите стоимость услуги");
                        }
                        var c = jQuery.trim(data.nds_pc).replace(/,/g, '.');
                        var cc = parseFloat(c);
                        if ( isNaN(cc)||cc===null||cc===void(0) || cc < 0) {
                            throw new Error("Укажите ставку НДС");
                        }
                        var c = jQuery.trim(data.nds_eur).replace(/,/g, '.');
                        var cc = parseFloat(c);
                        if (cc===null||cc===void(0) || isNaN(cc) || cc < 0) {
                            throw new Error("Укажите сумму НДС");
                        }                                             
                        var c = jQuery.trim(data.phone);
                        if (!c || !c.length) {
                            throw new Error("Укажите телефон");
                        }
                        if (!(data.telegramm || data.whatsapp || data.viber)) {
                            throw new Error("Выберите способ связи");
                        }
                    }

                    jQuery('#zayavka').on('click', 'button', function (e) {
                        if (jQuery('#zayavka button').hasClass('in_loading')) {
                            return;
                        }
                        e.stopPropagation();
                        e.preventDefault ? e.preventDefault() : e.returnValue = false;
                        var data = get_form_data();
                        try {
                            check_data(data);
                        } catch (e) {
                            alert(e.message);
                            return;
                        }
                        data.action = "post_request";
                        jQuery('#zayavka button').addClass('in_loading');
                        jQuery.post('/Cabinet/API', data)
                                .done(function (d) {
                                    if (d.status === "ok") {
                                        alert("Заявка успешно создана");
                                        window.location.reload(true);
                                        return;
                                    }
                                    if (d.status === 'error') {
                                        alert(d.error_info.message);
                                        return;
                                    }
                                    alert("Некорректный ответ сервера");
                                })
                                .fail(function () {
                                    alert("Ошибка связи с сервером!");
                                })
                                .always(function () {
                                    jQuery('#zayavka button').removeClass('in_loading');
                                });
                    });

                });
            {/literal}
        </script>
    </body>
</html>