<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:18:00
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/restore_result.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed538482ea4e9_62771810',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b331a006a3fcc9226fc327db87c358b3a3bfce27' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/restore_result.tpl',
      1 => 1565258138,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed538482ea4e9_62771810 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css("/assets/css/front/auth/restore.css",0));?>

<div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
RestoreSuccess">
    <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
RestoreSuccessInner">
        Ваш пароль успешно сброшен.<br><br>
        Новый пароль отправлен на Ваш email.
    </div>
</div><?php }
}
