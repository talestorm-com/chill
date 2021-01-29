<?php
/* Smarty version 3.1.33, created on 2020-07-31 17:13:52
  from '/var/VHOSTS/site/_layouts/front/yametrika.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f2427202745b6_33886395',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c4d8a36944d2fc71561e805838221bbb2ce09324' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/yametrika.tpl',
      1 => 1596204809,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f2427202745b6_33886395 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Yandex.Metrika counter -->
<?php echo '<script'; ?>
 type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(64534015, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
   });
<?php echo '</script'; ?>
>
<noscript><div><img src="https://mc.yandex.ru/watch/64534015" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php }
}
