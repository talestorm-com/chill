<?php
/* Smarty version 3.1.33, created on 2021-01-22 19:26:48
  from '/var/VHOSTS/site/_layouts/front/layout_a.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_600afcc876b684_42494797',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3f3e68b628f25418cd12a50d2c5b9df2a72588a1' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/layout_a.tpl',
      1 => 1611332705,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./../".((string)$_smarty_tpl->tpl_vars[\'asset\']->value->template).".tpl' => 1,
    'file:./g_head.tpl' => 1,
    'file:./cc.tpl' => 1,
    'file:./g_body.tpl' => 1,
    'file:./yametrika.tpl' => 1,
    'file:./balans.tpl' => 1,
    'file:./login_inc.tpl' => 1,
    'file:./signup_inc.tpl' => 1,
    'file:./review_inc.tpl' => 1,
    'file:./gotop.tpl' => 1,
    'file:./search_scr.tpl' => 1,
  ),
),false)) {
function content_600afcc876b684_42494797 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.eve_canonical.php','function'=>'smarty_function_eve_canonical',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_user_auth_status.php','function'=>'smarty_function_get_user_auth_status',),2=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.display_user_money.php','function'=>'smarty_function_display_user_money',),3=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.display_page_content.php','function'=>'smarty_function_display_page_content',),4=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),5=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>
<!DOCTYPE HTML>
<html lang="<?php echo $_smarty_tpl->tpl_vars['controller']->value->get_current_language();?>
">

<head>
    <style>.async-hide { opacity: 0 !important} </style>
    
<?php echo '<script'; ?>
>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'async-hide','dataLayer',4000,
{'OPT-T46SZK7':true});<?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
 src="https://www.googleoptimize.com/optimize.js?id=OPT-T46SZK7"><?php echo '</script'; ?>
>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="referrer" content="origin" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="robots" content="index, follow" />
    <title><?php echo $_smarty_tpl->tpl_vars['controller']->value->get_meta_title();?>
</title>
    <meta name="google-site-verification" content="6Q-dz1_JZQ6gWlUpTyKwCYymWhh_l-ZS5LirE1WH72A" />
    <meta name="yandex-verification" content="135e9ad4ad881915" />
    <meta property="og:site_name" content="Chill">
    <meta property="og:type" content="Article">
    <meta name="description" content="Веб-сериалы со всего мира">
    <meta property="og:title" content="Chill">
    <meta property="og:description" content="Веб-сериалы со всего мира">
    <meta property="og:url" content="https://chillvision.ru">
    <meta property="og:image" content="https://chillvision.ru/assets/chill/images/logo_out.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://chillvision.ru/">
    <meta property="twitter:title" content="Chill">
    <meta property="twitter:description" content="Веб-сериалы со всего мира">
    <meta property="twitter:image" content="https://chillvision.ru/assets/chill/images/logo_out.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/chill/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/chill/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/chill/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/chill/favicon/manifest.json">
    <link rel="mask-icon" href="/assets/chill/favicon/safari-pinned-tab.svg" color="#000000">
    <link rel="shortcut icon" href="/assets/chill/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-config" content="/assets/chill/favicon/browserconfig.xml">
    <meta name="theme-color" content="#000000">
    <?php echo smarty_function_eve_canonical(array('assign'=>'canonical'),$_smarty_tpl);?>

    <?php if ($_smarty_tpl->tpl_vars['canonical']->value) {?>
        <link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['canonical']->value;?>
" />
    <?php }?>
        

    <link type="text/css" rel="stylesheet" href="/assets/chill/css/materialize.min.css">
    <link href="/assets/chill/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/owl.theme.default.min.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/owl.carousel.min.css" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/main.css?v=<?php echo time();?>
" media="all" rel="stylesheet" type="text/css">
    <link href="/assets/chill/css/mob.css" media="all" rel="stylesheet" type="text/css">
    <?php echo '<script'; ?>
 type="text/javascript" src="/assets/js/efo.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="/assets/chill/js/owl.carousel.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="/assets/chill/js/nouislider.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="/assets/chill/js/materialize.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="/assets/chill/js/main.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="/assets/vendor/datepicker/js_async.js" async="async"><?php echo '</script'; ?>
>
    <link href="/assets/vendor/datepicker/css.css" media="all" rel="stylesheet" type="text/css">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['OUT']->value->assets, 'asset');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['asset']->value) {
?>
    <?php $_smarty_tpl->_subTemplateRender("file:./../".((string)$_smarty_tpl->tpl_vars['asset']->value->template).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php $_smarty_tpl->_subTemplateRender('file:./g_head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
$_smarty_tpl->_subTemplateRender('file:./cc.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</head>

<body>
<?php $_smarty_tpl->_subTemplateRender('file:./g_body.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <?php $_smarty_tpl->_subTemplateRender('file:./yametrika.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
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
                            <input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('search',true);?>
" />
                            <div id="quick_reult"></div>
            </form>
        </div>
    </div>
</div>
            <div id="header_search_bg"></div>
        </div>
        <div id="desktop_header">
            <div class="container">
                <div class="row">
                <div class="col s3 m5 offset-m1">
                        <div id="logo">
                            <a href="/" title="chill">
                                <img src="/assets/chill/images/logo_grad.png" alt="chill">
                            </a>
                        </div>
                    </div>
                    <div class="col s9 m5 right-align">
                        <div id="search_out_a">
                            <img src="/assets/chill/images/00_search.svg">
                        </div>
                        <div id="balans_in"></div>
                    </div>
                     </div>
                    </div>
               
        </div>
    </header>
<div id="bala" data-auth="<?php echo smarty_function_get_user_auth_status(array(),$_smarty_tpl);?>
"><?php echo smarty_function_display_user_money(array(),$_smarty_tpl);?>
</div>
    <?php $_smarty_tpl->_subTemplateRender('file:./balans.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <main class="page_page">
<div id="page">
    
            <div id="page_body">
                <div class="container">
                    <div class="row">
                        <div class="col s12 m10 offset-m1">
                            <div class="row">
                                <div class="col s11 offset-s1">
                            <?php echo smarty_function_display_page_content(array(),$_smarty_tpl);?>

                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        </main>
    <?php $_smarty_tpl->_subTemplateRender('file:./login_inc.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php $_smarty_tpl->_subTemplateRender('file:./signup_inc.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php $_smarty_tpl->_subTemplateRender('file:./review_inc.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <!-- Подвал -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                <div class="col l2  s11 offset-s1 offset-l1">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/"><?php echo smarty_function_TT(array('t'=>'footer_lent'),$_smarty_tpl);?>
</a>
                            </li>
                            <li>
                                <a href="/page/o-nas">О CHILL</a>
                            </li>
                            <li>
                                        <a href="/page/faq">F.A.Q.</a>
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
                <div class="col l4  s11 offset-s1">
                    <div class="one_footer_colomn">
                        <ul>
									
                            
                            <li>
                                <a href="/page/for_authors"><?php echo smarty_function_TT(array('t'=>'how_become_aut'),$_smarty_tpl);?>
</a>
                            </li>
                            <li>
                                <a href="/page/use_rules"><?php echo smarty_function_TT(array('t'=>'regular_use'),$_smarty_tpl);?>
</a>
                            </li>
                            <li>
                                <a href="/page/policy"><?php echo smarty_function_TT(array('t'=>'policy_footer'),$_smarty_tpl);?>
</a>
                            </li>
                            <li>
                                <a href="/page/usl_raz">Условия размещения контента пользователя в сервисе</a>
                            </li>
                        </ul>
                    </div>
                </div>
               <!-- <div class="col l4 s6">
                    <div class="one_footer_colomn">
                        <ul>
                            <li>
                                <a href="/page/help"><?php echo smarty_function_TT(array('t'=>'Help'),$_smarty_tpl);?>
</a>
                            </li>
                            <li>
                                <a href="/page/about"><?php echo smarty_function_TT(array('t'=>'about'),$_smarty_tpl);?>
</a>
                            </li>
                           <li>
                                <a href="/page/pay_rules"><?php echo smarty_function_TT(array('t'=>'regular_pay'),$_smarty_tpl);?>
</a>
                            </li>
                        </ul>
                    </div>
                </div>-->
                <div class="col l4  s11 offset-s1">
                    <div class="one_footer_colomn" id="last_column_footer">
                        <ul>
                            <li>
                                <i class="mdi-email mdi"></i><a href="mailto:help@chillvision.ru">help@chillvision.ru</a>
                            </li>
                            <li>
                                ©️ <?php echo smarty_modifier_date_format(time(),"%Y");?>
. ООО «ЧИЛЛ ВИЖН»
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
                            <a href="https://ok.ru/group/58370962686133" target="_blank">
                                <img src="/assets/chill/ss/ok.svg">
                            </a>
                            <a href="https://www.instagram.com/itschillonline/" target="_blank">
                                <img src="/assets/chill/ss/in.svg">
                            </a>
                            <!--<a href="https://www.tiktok.com/@chill.online" target="_blank">
                                <img src="/assets/chill/ss/tt.svg">
                            </a>-->
                            <a href="https://www.facebook.com/itschillonline" target="_blank">
                                <img src="/assets/chill/ss/fb.svg">
                            </a>
                            <!--<a href="https://twitter.com/chill__online" target="_blank">
                                <img src="/assets/chill/ss/tw.svg">
                            </a>-->
                            <a href="https://www.youtube.com/channel/UC8uFUbZc0ozz4DpDJlbfL-g?view_as=subscriber" target="_blank">
                                <img src="/assets/chill/ss/yt.svg">
                            </a>
                            <a href="https://t.me/chill_online" target="_blank">
                                <img src="/assets/chill/ss/tg.svg">
                            </a>
                            <!--<a href="https://invite.viber.com/?g2=AQBXfyVzMPFaukvu5oYNoZzZh7tj2Jpj7a8m8Uumbm11%2FHHcv4TLrnONIN4nWop%2F" target="_blank">
                                <img src="/assets/chill/ss/vb.svg">
                            </a>-->
                            <a href="https://zen.yandex.ru/id/5b51f43bd86e5c00a8b3f77c" target="_blank">
                                <img src="/assets/chill/ss/zi.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
        <?php $_smarty_tpl->_subTemplateRender('file:./gotop.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <?php $_smarty_tpl->_subTemplateRender('file:./search_scr.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</body>


</html><?php }
}
