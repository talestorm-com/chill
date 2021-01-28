<?php
/* Smarty version 3.1.33, created on 2020-06-18 14:58:15
  from '/var/VHOSTS/site/_layouts/front/pass.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5eeb56d77e3e24_02499001',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '28f55ac7e99ce6ab5dbd6b53ed5baf887985405e' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/pass.tpl',
      1 => 1592481490,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eeb56d77e3e24_02499001 (Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
var a = localStorage.getItem("admin");
if(a != 'on'){
	$("main").fadeOut(0);
	$("header").fadeOut(0);
	$("footer").fadeOut(0);
	$("#line_out").fadeOut(0);
	$("#close_video").fadeOut(0);
	$("div#video_first").css("display","block!important");
}
<?php echo '</script'; ?>
>
<?php }
}
