<?php
/* Smarty version 3.1.33, created on 2020-08-16 18:29:12
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/social_error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f3950c8e12ff7_48396597',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a743d86f664ca9fb2e0ef92993995ef6930b555c' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/social_error.tpl',
      1 => 1587856698,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f3950c8e12ff7_48396597 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        Произошла ошибка!
        <pre style="display:none"><?php echo var_dump($_smarty_tpl->tpl_vars['error']->value);?>
</pre>
    </body>
</html><?php }
}
