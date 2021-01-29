<?php
/* Smarty version 3.1.33, created on 2020-07-24 23:55:28
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/submit_error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f1b4ac0271768_94448291',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '512e7035af83f13c12f678c2f4f9233db0904c84' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/submit_error.tpl',
      1 => 1587395166,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f1b4ac0271768_94448291 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/css/lk_error_eve.css',0));?>

<div class="submit-profile-error">
    <div class="submit-profile-error-inner">
        <div class="submit-profile-error-text">Ошибка!<br><?php echo $_smarty_tpl->tpl_vars['error']->value->getMessage();?>
</div>
    </div>
</div><?php }
}
