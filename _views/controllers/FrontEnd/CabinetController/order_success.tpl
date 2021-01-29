<!DOCTYPE html>
<html lang="ru-RU">
    <head>
        <title>HOMY. Индивидуальные тренировки Москва</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <link type="text/css" rel="stylesheet" href="/assets/front_main/css/materialize.min.css">
        <link href="/assets/front_main/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css">        
        <link href="/assets/front_main/css/main.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/mob.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/login.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/main_new.css" media="all" rel="stylesheet" type="text/css">        
        <link href="/assets/front_main/css/order_d.css" media="all" rel="stylesheet" type="text/css">        
        <script async="async" src="/assets/js/efo.js"></script>
        <script>
            (function () {
                window.Eve = window.Eve || {};
                window.Eve.EFO = window.Eve.EFO || {};
                window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                window.Eve.EFO.Ready.push(function () {
                    var urls = [
                        "/assets/front_main/js/materialize.min.js"
                    ];
                    for (var i = 0; i < urls.length; i++) {
                        window.Eve.EFO.Com().js(urls[i]);
                    }
                });
            })();
        </script>
    </head>
    <body>
        <div class="{$controller->MC}_order_success">
            <div class="{$controller->MC}_order_success_header">Заказ оформлен!</div>
            <div class="{$controller->MC}_order_success_content">
                <div class="{$controller->MC}_order_success_content_row">Заказ № {$order.id}</div>
                <div class="{$controller->MC}_order_success_content_row">Пакет {$order.package_name}</div>
                <div class="{$controller->MC}_order_success_content_row">{$order.usages} посещений</div>
                <div class="{$controller->MC}_order_success_content_row">до {$order.expires|date_format:'%d.%m.%Y'}</div>
                <div class="{$controller->MC}_order_success_content_row"><b>{$order.cost|number_format:2:'.':' '}</b> RUR </div>
            </div>
        </div>
    </body>
</html>