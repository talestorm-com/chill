<?php
/* Smarty version 3.1.33, created on 2021-01-29 09:19:21
  from '/data/_layouts/front/yametrika.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_601370a9e743c8_63194144',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8c0518633d7b1e8a98995e1fb7c04323cb40c78e' => 
    array (
      0 => '/data/_layouts/front/yametrika.tpl',
      1 => 1611292659,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_601370a9e743c8_63194144 (Smarty_Internal_Template $_smarty_tpl) {
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
