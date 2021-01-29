<?php
/* Smarty version 3.1.33, created on 2020-06-14 12:16:26
  from '042fd1adaf2bb81f5aba4ea0cbd3efc3fa210110' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ee5eaea530726_10563467',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ee5eaea530726_10563467 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- start content_block `scroll_to` --><?php echo '<script'; ?>
>
  $(document).ready(function(){
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
