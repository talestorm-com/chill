<?php
/* Smarty version 3.1.33, created on 2020-07-24 20:32:45
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/SearchController/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f1b1b3dd93698_57108678',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '09ac0505fab180f93bd0172a4687a693f830bb75' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/SearchController/default.tpl',
      1 => 1566134624,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f1b1b3dd93698_57108678 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css("/assets/css/front/search.css",0));?>

<div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
Wrapper">
    <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
Warning">
        Уважаемые посетители!<br>
        По техническим причинам поиск некоторое время может работать некорректно.<br>
        Приносим извинения за доставленные неудобства, мы постараемся решить эту проблему в кратчайшие сроки.
    </div>
<div id="ya-site-results" data-bem="{&quot;tld&quot;: &quot;ru&quot;,&quot;language&quot;: &quot;ru&quot;,&quot;encoding&quot;: &quot;utf-8&quot;,&quot;htmlcss&quot;: &quot;1.x&quot;,&quot;updatehash&quot;: true}">
</div>

<?php echo '<script'; ?>
 type="text/javascript">
    (function (w, d, c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0];s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Results.init();})})(window, document, 'yandex_site_callbacks');
<?php echo '</script'; ?>
>

</div>
<?php }
}
