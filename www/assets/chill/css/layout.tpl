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
    <link href="/assets/chill/css/colorbox.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/main.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/mob.css" media="all" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/assets/js/efo.js?a=1"></script>
    <script type="text/javascript" src="/assets/chill/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/nouislider.min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/materialize.min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="/assets/chill/js/main.js"></script>
    <script src="https://yastatic.net/pcode/adfox/loader.js" crossorigin="anonymous"></script>

    <script type="text/javascript" src="/assets/vendor/datepicker/js_async.js" async="async"></script>
    <link href="/assets/vendor/datepicker/css.css" media="all" rel="stylesheet" type="text/css">
    {foreach from=$OUT->assets item=asset}
    {include "./../{$asset->template}.tpl"}
    {/foreach}

{include './g_head.tpl'}

</head>

<body>
    
{include './g_body.tpl'}

    {include './yametrika.tpl'}

    <div id="line_out">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
            <div class="line_in">
            </div>
            </div>
            </div>
        </div>
    </div>
    <header>
        <div id="header_search">
            <div class="container">
                <div class="row">
                <div class="col s12 m10 offset-m1">
            <form method=”GET” id="search_block" action="/Search/Search">
                            <i class="mdi mdi-magnify"></i>
                            <input type="text" id="search_input" placeholder="Поиск" name="query">
            </form>
        </div>
    </div>
</div>
            <div id="header_search_bg"></div>
        </div>


        <div id="desktop_header">
            <div class="container">
                <div class="row">
                <div class="col s6 m5 offset-m1">
                        <div id="logo">
                            <a href="/" title="chill">
                                <img src="/assets/chill/images/logo_grad.png" alt="chill">
                            </a>
                        </div>
                    </div>

                    <div class="col s6 m5 right-align">
                             
                        <div id="balans_in"></div>
                        <!-- Тут баланс -->
                    </div>
                     </div>
                    </div>
                   
               
        </div>
    </header>
    <div id="bala" data-auth="{get_user_auth_status}">{display_user_money}</div>
    {include './balans.tpl'}
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
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                <div class="col l2 s6 offset-l1">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/">{TT t='footer_lent'}</a>
                            </li>
                            <!-- <li>
                                <a href="/catalog">Каталог</a>
                            </li>
                            <li>
                                <a href="/newslist">Новости</a>
                            </li> -->
                        </ul>
                    </div>
                </div>
                <div class="col l4 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            
                            <li>
                                <a href="/page/for_authors">{TT t='how_become_aut'}</a>
                            </li>
                            <li>
                                <a href="/page/use_rules">{TT t='regular_use'}</a>
                            </li>
                            <li>
                                <a href="/page/policy">{TT t='policy_footer'}</a>
                            </li>
                        </ul>
                    </div>
                </div>
               <!-- <div class="col l4 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/page/help">{TT t='Help'}</a>
                            </li>
                            <li>
                                <a href="/page/about">{TT t='about'}</a>
                            </li>
                           <li>
                                <a href="/page/pay_rules">{TT t='regular_pay'}</a>
                            </li>
                        </ul>
                    </div>
                </div>-->
                <div class="col l4 s6">
                    <div class="one_footer_colomn" id="last_column_footer">
                        <ul>
                            <li>
                                <i class="mdi-email mdi"></i><a href="mailto:help@chillvision.ru">help@chillvision.ru</a>
                            </li>
                            <li>
                                ©️ 2020. ООО «ЧИЛЛ ВИЖН»
                            </li>
                        </ul>
                        <div id="eighty">18+</div>
                    </div>
                </div>
                </div>
                </div>
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div id="soc_footer">
                            <a href="https://vk.com/chill.online" target="_blank">
                                <img src="/assets/chill/ss/vk.svg">
                            </a>
                            <a href="https://ok.com/chill.online" target="_blank">
                                <img src="/assets/chill/ss/ok.svg">
                            </a>
                            <a href="https://www.instagram.com/itschillonline/" target="_blank">
                                <img src="/assets/chill/ss/in.svg">
                            </a>
                            <a href="https://www.tiktok.com/@chill.online" target="_blank">
                                <img src="/assets/chill/ss/tt.svg">
                            </a>
                            <a href="https://www.facebook.com/itschillonline" target="_blank">
                                <img src="/assets/chill/ss/fb.svg">
                            </a>
                            <a href="https://twitter.com/chill__online" target="_blank">
                                <img src="/assets/chill/ss/tw.svg">
                            </a>
                            <a href="https://www.youtube.com/channel/UCeDQFaa7s_WAmyX22vfn1dA" target="_blank">
                                <img src="/assets/chill/ss/yt.svg">
                            </a>
                            <a href="https://t.me/chill_online" target="_blank">
                                <img src="/assets/chill/ss/tg.svg">
                            </a>
                            <a href="https://invite.viber.com/?g2=AQBXfyVzMPFaukvu5oYNoZzZh7tj2Jpj7a8m8Uumbm11%2FHHcv4TLrnONIN4nWop%2F" target="_blank">
                                <img src="/assets/chill/ss/vb.svg">
                            </a>
                            <a href="https://zen.yandex.ru/id/5b51f43bd86e5c00a8b3f77c" target="_blank">
                                <img src="/assets/chill/ss/ya.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

<div id="bg_bg_vert">
<div id="bg_vert">
<div id="bg_vert_in">
Переверни телефон вертикально
</div>
</div>
</div>
    {include './gotop.tpl'}
    
</body>

</html>