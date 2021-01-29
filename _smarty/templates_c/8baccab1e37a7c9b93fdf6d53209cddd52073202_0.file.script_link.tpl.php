<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:19:44
  from '/var/VHOSTS/site/_layouts/script_link.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4e45079ceb1_04146269',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8baccab1e37a7c9b93fdf6d53209cddd52073202' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/script_link.tpl',
      1 => 1555069448,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed4e45079ceb1_04146269 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['asset']->value->url;?>
" <?php if ($_smarty_tpl->tpl_vars['asset']->value->async) {?> async <?php }?> data-id="<?php echo $_smarty_tpl->tpl_vars['asset']->value->asset_key;?>
"><?php echo '</script'; ?>
><?php }
}
