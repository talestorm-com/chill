<?php
/* Smarty version 3.1.33, created on 2020-06-20 17:50:55
  from '/var/VHOSTS/site/_views/mailer/report/payment/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5eee224f64b088_26410286',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'efa468774be05a255568d193bc5c9862d7795bc3' => 
    array (
      0 => '/var/VHOSTS/site/_views/mailer/report/payment/index.tpl',
      1 => 1592664390,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./../../mailer_common/header.tpl' => 1,
    'file:./../../mailer_common/footer.tpl' => 1,
  ),
),false)) {
function content_5eee224f64b088_26410286 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender('file:./../../mailer_common/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<h3><?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
</h3>
Отчет во вложении.<br><br><br>
<?php $_smarty_tpl->_subTemplateRender('file:./../../mailer_common/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
