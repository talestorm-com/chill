<?php
/* Smarty version 3.1.33, created on 2020-09-08 23:54:41
  from '/var/VHOSTS/site/_layouts/front/cookie.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f57ef9132c6c3_38301183',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '595435159aaef46a7710bc865676aa7dfa3b265e' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/cookie.tpl',
      1 => 1599598397,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f57ef9132c6c3_38301183 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="noty_body">
<div id="noty_close">
<i class="mdi mdi-close"></i>
</div>
<div class="noty_body_text">
Этот сайт использует файлы <strong>cookie</strong>. Продолжая пользоваться данным сайтом, Вы соглашаетесь на использование нами Ваших файлов <strong>cookie</strong>. <a href="/page/policy">Узнать больше</a>
</div>
</div>


<?php echo '<script'; ?>
>
$(document).ready(function(){
    var a = localStorage.getItem("cookie");
    if(a != "true"){
        $("#noty_body").fadeIn(300);
    }
});
$("#noty_close").click(function(){
    $("#noty_body").fadeOut(300);
    localStorage.setItem("cookie","true"); 
});
<?php echo '</script'; ?>
>
<?php }
}
