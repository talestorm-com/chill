<!DOCTYPE HTML>
<html lang="{$controller->get_current_language()}">

<head>
    <title>{$controller->get_meta_title()}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="{$controller->get_meta_description()}">
    {if $controller->is_og_support()}
    <meta property="og:title" content="{$controller->get_og_title()}">
    <meta property="og:description" content="{$controller->get_og_description()}">
    <meta property="og:url" content="{$controller->get_og_url()}">
    {if $controller->is_og_image_support()}
    <meta property="og:image" content="{$controller->get_og_image_url()}">
    {/if}
    {/if}
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/chill/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/chill/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/chill/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/chill/favicon/manifest.json">
    <link rel="mask-icon" href="/assets/chill/favicon/safari-pinned-tab.svg" color="#000000">
    <link rel="shortcut icon" href="/assets/chill/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-config" content="/assets/chill/favicon/browserconfig.xml">
    <meta name="theme-color" content="#000000">
    <link type="text/css" rel="stylesheet" href="/assets/chill/css/materialize.min.css">
    <link href="/assets/chill/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/owl.theme.default.min.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/owl.carousel.min.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/main.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/mob.css" media="all" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/assets/js/efo.js"></script>
    <script type="text/javascript" src="/assets/chill/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/nouislider.min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/materialize.min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/main.js"></script>
    <script type="text/javascript" src="/assets/vendor/datepicker/js_async.js" async="async"></script>
    <link href="/assets/vendor/datepicker/css.css" media="all" rel="stylesheet" type="text/css">
    {foreach from=$OUT->assets item=asset}
    {include "./../{$asset->template}.tpl"}
    {/foreach}
</head>

<body>
    <header class="hide-on-med-and-down">
        <!--             <div id="mobile_header" class='hide-on-large-only'>
                <div class='container'>
                    <div class="row">
                        <div class="col s6">
                            <div id="logo_mobile">
                                <a href="/">
                                    <img src="/assets/chill/images/logo.png">
                                </a>
                            </div>
                        </div>
                        <div class="col s6 right-align">
                            <a id="login_btn" href="/Profile">
                                <img src="/assets/chill/images/account.png">
                            </a>
                        </div>
                    </div>
                </div>
            </div> -->
        <div id="desktop_header">
            <div class='container'>
                <div class="row">
                    <div class="col s6 l1">
                        <div id="logo">
                            <a href="/" title="chill">
                                <img src="/assets/chill/images/logo.png" alt="chill">
                            </a>
                        </div>
                    </div>
                    <div class="col l5">
                        <div id="desktop_menu">
                            <ul>
                                <li class="active">
                                    <a href="/">
                                        Лента
                                    </a>
                                </li>
                                <li>
                                    <a href="/catalog">
                                        Каталог
                                    </a>
                                </li>
                                <li>
                                    <a href="/newslist">
                                        Новости
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col l5 right-align">
                        <form method=”GET” id="search_block" action="/Search/Search">
                            <i class="mdi mdi-magnify"></i>
                            <input type="text" id="search_input" placeholder="Поиск" name="query">
                        </form>
                    </div>
                    <div class="col s6 l1 right-align">
                        <a id="login_btn" href="/Profile" title="Профиль">
                            <img src="/assets/chill/images/account.png" alt="Профиль">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        {display_page_content}
    </main>
    {include './login_inc.tpl'}
    {include './signup_inc.tpl'}
    {include './review_inc.tpl'}
    <!-- Подвал -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col l1 s6 hide-on-med-and-down">
                    <div class="one_footer_colomn">
                        <a href="/" title="chill">
                            <img src="/assets/chill/images/logo.png" alt="chill">
                        </a>
                    </div>
                </div>
                <div class="col l2 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/">Лента</a>
                            </li>
                            <li>
                                <a href="/catalog">Каталог</a>
                            </li>
                            <li>
                                <a href="/newslist">Новости</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col l3 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/page/about">О сервисе</a>
                            </li>
                            <li>
                                <a href="/page/pay_rules">Правила оплаты и возврата</a>
                            </li>
                            <li>
                                <a href="/page/for_authors">Как стать автором контента</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col l3 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/page/help">Помощь</a>
                            </li>
                            <li>
                                <a href="/page/use_rules">Правила использования</a>
                            </li>
                            <li>
                                <a href="/page/policy">Политика конфиденциальности</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col l3 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <i class="mdi-email mdi"></i><a>help@chill.com</a>
                            </li>
                            <li>
                                <i class="mdi mdi-phone"></i><a>+7 (999) 999-99-99</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="menu_menu_mobile" class="hide-on-large-only">
        <div id="menu_menu_mobile_menu">
            <div id="inchill">
                <a href="/">
                <img src="/assets/chill/images/logo_in.jpg">
            </a>
        </div>
            <div class="row">
                <div class="col s4">
                    <a href="/catalog">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M141.367,116.518c-7.384-7.39-19.364-7.39-26.748,0c-27.416,27.416-40.891,65.608-36.975,104.79
			c0.977,9.761,9.2,17.037,18.803,17.037c0.631,0,1.267-0.032,1.898-0.095c10.398-1.04,17.983-10.316,16.943-20.707
			c-2.787-27.845,6.722-54.92,26.079-74.278C148.757,135.882,148.757,123.901,141.367,116.518z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M216.276,0C97.021,0,0,97.021,0,216.276s97.021,216.276,216.276,216.276s216.276-97.021,216.276-216.276
			S335.53,0,216.276,0z M216.276,394.719c-98.396,0-178.443-80.047-178.443-178.443S117.88,37.833,216.276,37.833
			c98.39,0,178.443,80.047,178.443,178.443S314.672,394.719,216.276,394.719z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M506.458,479.71L368.999,342.252c-7.39-7.39-19.358-7.39-26.748,0c-7.39,7.384-7.39,19.364,0,26.748L479.71,506.458
			c3.695,3.695,8.531,5.542,13.374,5.542c4.843,0,9.679-1.847,13.374-5.542C513.847,499.074,513.847,487.094,506.458,479.71z" />
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>
                <div class="col s4 offset-s4">
                    <a href="/Profile">
                        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m437.019531 74.980469c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469-68.382812 0-132.667969 26.628906-181.019531 74.980469-48.351563 48.351562-74.980469 112.636719-74.980469 181.019531 0 68.378906 26.628906 132.667969 74.980469 181.019531 48.351562 48.351563 112.636719 74.980469 181.019531 74.980469 68.378906 0 132.667969-26.628906 181.019531-74.980469 48.351563-48.351562 74.980469-112.640625 74.980469-181.019531 0-68.382812-26.628906-132.667969-74.980469-181.019531zm-308.679687 367.40625c10.707031-61.648438 64.128906-107.121094 127.660156-107.121094 63.535156 0 116.953125 45.472656 127.660156 107.121094-36.347656 24.972656-80.324218 39.613281-127.660156 39.613281s-91.3125-14.640625-127.660156-39.613281zm46.261718-218.519531c0-44.886719 36.515626-81.398438 81.398438-81.398438s81.398438 36.515625 81.398438 81.398438c0 44.882812-36.515626 81.398437-81.398438 81.398437s-81.398438-36.515625-81.398438-81.398437zm235.042969 197.710937c-8.074219-28.699219-24.109375-54.738281-46.585937-75.078125-13.789063-12.480469-29.484375-22.328125-46.359375-29.269531 30.5-19.894531 50.703125-54.3125 50.703125-93.363281 0-61.425782-49.976563-111.398438-111.402344-111.398438s-111.398438 49.972656-111.398438 111.398438c0 39.050781 20.203126 73.46875 50.699219 93.363281-16.871093 6.941406-32.570312 16.785156-46.359375 29.265625-22.472656 20.339844-38.511718 46.378906-46.585937 75.078125-44.472657-41.300781-72.355469-100.238281-72.355469-165.574219 0-124.617188 101.382812-226 226-226s226 101.382812 226 226c0 65.339844-27.882812 124.277344-72.355469 165.578125zm0 0"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>


</html>