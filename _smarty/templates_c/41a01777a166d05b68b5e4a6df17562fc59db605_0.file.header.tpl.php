<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:49:57
  from '/var/VHOSTS/site/_layouts/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4eb6546ec46_86393365',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '41a01777a166d05b68b5e4a6df17562fc59db605' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/header.tpl',
      1 => 1555067564,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./".((string)$_smarty_tpl->tpl_vars[\'asset\']->value->template).".tpl' => 1,
  ),
),false)) {
function content_5ed4eb6546ec46_86393365 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="ru">
    <head>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['OUT']->value->assets, 'asset');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['asset']->value) {
?>
            <?php $_smarty_tpl->_subTemplateRender("file:./".((string)$_smarty_tpl->tpl_vars['asset']->value->template).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
