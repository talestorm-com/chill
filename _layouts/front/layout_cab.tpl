<html lang="ru-RU">
    <head>
        <title>HOMY. Индивидуальные тренировки Москва</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <link type="text/css" rel="stylesheet" href="/assets/front_main/css/materialize.min.css">
        <link href="/assets/front_main/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/lk.css" media="all" rel="stylesheet" type="text/css">
        <link href="/assets/front_main/css/lk_mob.css" media="all" rel="stylesheet" type="text/css"> 
        <link href="/assets/front_main/css/main_new.css" media="all" rel="stylesheet" type="text/css"> 
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
        <header>
            <div id="header_menu">
                <div id="settings"><a href="/Cabinet/profile">Настройки</a></div>
                <div id="logout"><a href="/Auth/Logout">Выход</a></div>
        </header>
        <div class="row" id="main">
            <div class="col s12 l3" id="left_menu">
                <div id="logo_block">
                    <div id="logo"><img src="/assets/front_main/images/logo.png"></div>
                    <div id="lk_title">Личный<br>Кабинет</div>
                </div>
                <div id="left_menu_menu">
                    <div id="statistics"><a href="/Cabinet/stat">Статистика</a></div>
                    <div id="calendar"><a href="/Cabinet/calendar">Календарь</a></div>
                </div>
                <div id="right_menu_footer" class="hide-on-med-and-down">
                    <div id="policy"> <a href="/page/policy">Политика конфиденциальности</a></div>
                    <div id="copyright">Все права защищены</div>
                </div>
            </div>
            <div class="col s12 l9" id="main_block">
                {display_page_content}
            </div>
        </div>
    </body>
</html>