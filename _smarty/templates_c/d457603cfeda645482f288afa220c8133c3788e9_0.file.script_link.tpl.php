<?php
/* Smarty version 3.1.33, created on 2021-01-29 09:19:21
  from '/data/_layouts/script_link.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_601370a9e56790_97172756',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd457603cfeda645482f288afa220c8133c3788e9' => 
    array (
      0 => '/data/_layouts/script_link.tpl',
      1 => 1611292657,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_601370a9e56790_97172756 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['asset']->value->url;?>
" <?php if ($_smarty_tpl->tpl_vars['asset']->value->async) {?> async <?php }?> data-id="<?php echo $_smarty_tpl->tpl_vars['asset']->value->asset_key;?>
"><?php echo '</script'; ?>
><?php }
}
