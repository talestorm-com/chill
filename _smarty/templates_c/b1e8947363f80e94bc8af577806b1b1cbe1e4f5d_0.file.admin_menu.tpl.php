<?php
/* Smarty version 3.1.33, created on 2020-11-19 22:51:10
  from '/var/VHOSTS/site/_layouts/admin_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fb6ccaeb1af98_70941982',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b1e8947363f80e94bc8af577806b1b1cbe1e4f5d' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/admin_menu.tpl',
      1 => 1605815469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fb6ccaeb1af98_70941982 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_current_language_id.php','function'=>'smarty_function_get_current_language_id',),));
?>
<div class="AdminLayoutMenuContent">
    <div class="AdminLayoutMenuContentInner">
        <ul class="AdminLayout-menu-top-level-items">            
            <li><a href="/admin/Users/index">Пользователи</a></li>
            <!-- li><a href="/admin/T/index">Словарь</a></li -->
            <li><a href="/admin/MediaContent/Index">Медиаконтент</a></li>
            <li><a href="/admin/MediaGrid/Index">Сетка</a></li>
            <li><a href="/admin/MediaContent/Banner">Баннеры</a></li>
            <!-- li><a href="/admin/Package/Index">Пакеты</a></li -->
            <li><a href="/admin/Presets/Index">Настройки</a></li>            
            <li><a href="#">Отзывы</a>
                <ul class="AdminLayout-menu-sub">
                    <li><a href="/admin/Reviews/Index">По сериалам</a></li>                                        
                    <li><a href="/admin/ChillComment/Index">Комментируй чилл</a></li>
                    
                </ul>
            </li>  
            <li><a href="#">Контент</a>
                <ul class="AdminLayout-menu-sub">
                    <li><a href="/admin/Pages/index">Инфостраницы</a></li>                                        
                    <li><a href="/admin/ContentBlock/index">Блоки</a></li>
                    <li><a href="/admin/FallbackImage/index">Фаллбаки</a></li>                
                </ul>
            </li>  
            <li><a href="#">Справочники</a>
                <ul class="AdminLayout-menu-sub">
                    <li><a href="/admin/AgeRestrictionList/index">Возрастные ограничения</a></li>                                        
                    <li><a href="/admin/MediaPerson/Index">Персоналии</a></li>                    
                    <li><a href="/admin/LanguageList/Index">Языки контента</a></li>                    
                    <li><a href="/admin/CountryList/Index">Страны - источники контента</a></li>     
                    <li><a href="/admin/VendorList/Index">Студии - производители контента</a></li>     
                    <li><a href="/admin/GenreList/Index">Жанры</a></li>     
                    <li><a href="/admin/EmojiList/Index">Эмодзи</a></li>  
                    <li><a href="/admin/TagList/Index">Теги</a></li>  
                    <li><a href="/admin/TracklangList/Index">Языки озвучки</a></li>  
                    <li><a href="/admin/Preplay/Index">Преплеи</a></li>  
                    <li><a href="/admin/Sticker/Index">Стикеры</a></li>  
                    
                </ul>
            </li>  
            <li><a href="/admin/PromoList/Index">Промокоды</a></li> 
            <li ><a href="/admin/Requests/Index">Заявки</a></li> 
             <li><a href="#">Служебное</a>
                <ul class="AdminLayout-menu-sub">
                    <li><a href="/admin/T/index">Транслятор / адм</a></li>                                        
                    <li><a href="/admin/TR/Index">Транслятор / фронт</a></li>                    
                    <li><a href="/admin/PaymentReport/Index">Отчет</a></li>                                        
                </ul>
            </li>  

            <li style="display:none" ><a href="#" style="color:crimson">Настройки</a>
                <ul class="AdminLayout-menu-sub">
                    <!-- li><a href="/admin/Navigation/index">Навигация</a></li -->  

                    <!-- li><a href="/admin/Gallery/index">Галереи</a></li -->
                    <li style="display:none"><a href="/admin/Import/index">Импорт из файла</a></li>
                    <li style="display:none"><a href="/admin/SizeVoc/Def">Системы размеров</a></li>
                    <li style="display:none"><a href="/admin/Presets/Index">Настройки сайта</a></li>
                </ul>
            </li>     
            <li style="display:none"><a href="/admin/Order/Index">Заказы</a></li>
            <li style="display:none"><a href="#">Магазин</a>
                <ul class="AdminLayout-menu-sub">
                    <li><a href="/admin/Storage/index">Склады</a></li>  
                    <li><a href="/admin/Storage/Offline">Оффлайн-магазины</a></li>
                    <li><a href="/admin/Storage/Partner">Магазины-партнеры</a></li>
                    <li><a href="/admin/Storage/Warehouse">Остатки</a></li>
                </ul>
            </li>
            <li ><a href="/Auth/Logout">Выход (<?php echo $_smarty_tpl->tpl_vars['controller']->value->auth->user_info->login;?>
)</a></li>
            <li class="AdminLayoutMenuHelp"><a class="AdminLayoutMenuHelpLink" href="#">Справка</a></li>
        </ul>
        <div class="AdminLayoutLanguageSelector">
            <div class="AdminLayoutLanguageSelectorInner" data-command="select_language">
                <?php echo smarty_function_get_current_language_id(array(),$_smarty_tpl);?>

            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
>
    (function () {
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            var U = window.Eve.EFO.U;
            jQuery(function () {
                jQuery('a.AdminLayoutMenuHelpLink').on('click', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    var url_key = location.pathname;
                    url_key = U.NEString(url_key.replace(/^\//ig, '').replace(/\/$/ig, '').replace(/\//ig, '_'), null);
                    window.Eve.EFO.Com().load("system.help")
                            .done(window, function (x) {
                                x.show().load(url_key);
                            })
                            .fail(window, function () {
                                U.TError("Ошибка при загрузке компонента справки");
                            });
                });
            });
            jQuery(function () {
                jQuery(document).on('click', '.AdminLayoutLanguageSelectorInner', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : null;
                    window.Eve.EFO.Com().load('selectors.language_selector')
                            .done(function (x) {
                                x.show().setCallback(window, function (r) {
                                    var lang = r[0].id;
                                    jQuery('body').hide();
                                    jQuery.getJSON('/Info/API', {action: "set_preferred_language", "language": lang})
                                            .done(function () {
                                                window.location.reload();
                                            })
                                            .always(function () {
                                                jQuery('body').show();
                                            })
                                    return this;
                                }).set_allow_multi(false);
                            });
                });
            });
        });
    })();
<?php echo '</script'; ?>
><?php }
}
