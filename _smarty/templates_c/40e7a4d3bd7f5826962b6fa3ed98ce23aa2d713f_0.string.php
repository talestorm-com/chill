<?php
/* Smarty version 3.1.33, created on 2020-06-14 12:15:38
  from '40e7a4d3bd7f5826962b6fa3ed98ce23aa2d713f' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ee5eaba57cc80_51522175',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ee5eaba57cc80_51522175 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- start content_block `scroll_to` --><?php echo '<script'; ?>
>
  $(document).ready(funciton(){
    var b = localStorage.getItem("scrolltop");
  	if(b !='' ||typeof b != "undefined"){
      $(window).scrollTop(b);
    }
  });
  $(document).on("scroll",function(){
    var a = $(window).scrollTop();
    localStorage.setItem("scrolltop",a);
  });
<?php echo '</script'; ?>
><!-- end of content_block `scroll_to` --><?php }
}
