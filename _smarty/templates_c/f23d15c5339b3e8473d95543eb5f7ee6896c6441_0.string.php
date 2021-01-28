<?php
/* Smarty version 3.1.33, created on 2020-07-24 22:39:39
  from 'f23d15c5339b3e8473d95543eb5f7ee6896c6441' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f1b38fba0e739_63757229',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f1b38fba0e739_63757229 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- start content_block `scroll_to` --><?php echo '<script'; ?>
>
  $(document).ready(function(){
    var b = sessionStorage.getItem("scrolltop");
    if(b !='' ||typeof b != "undefined"){
      $(window).scrollTop(b);
    }
  });
  $(document).on("scroll",function(){
    var a = $(window).scrollTop();
    sessionStorage.setItem("scrolltop",a);
  });
<?php echo '</script'; ?>
><!-- end of content_block `scroll_to` --><?php }
}
