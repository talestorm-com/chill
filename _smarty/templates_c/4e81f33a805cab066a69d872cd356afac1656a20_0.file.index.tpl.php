<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:10:21
  from '/var/VHOSTS/site/_views/mailer/new_request_fos/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed5367d9b02a2_34698139',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4e81f33a805cab066a69d872cd356afac1656a20' => 
    array (
      0 => '/var/VHOSTS/site/_views/mailer/new_request_fos/index.tpl',
      1 => 1590794679,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./../mailer_common/header.tpl' => 1,
    'file:./../mailer_common/footer.tpl' => 1,
  ),
),false)) {
function content_5ed5367d9b02a2_34698139 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender('file:./../mailer_common/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<h3><?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
</h3>
Уважаемый менеджер!<br>
Поступил новый запрос на размещение контента в кинотеатре "Chill".
<br>
Информация:<br>
<b>Пользователь:</b><?php echo $_smarty_tpl->tpl_vars['contact']->value;?>
<br>
<b>email:</b><?php echo $_smarty_tpl->tpl_vars['email']->value;?>
<br>
<b>Наименование:</b><?php echo $_smarty_tpl->tpl_vars['common_name']->value;?>
<br>
<b>Наименование (en):</b><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
<br>
<b>Год выхода:</b><?php echo $_smarty_tpl->tpl_vars['year']->value;?>
<br>
<b>Режиссер:</b><?php echo $_smarty_tpl->tpl_vars['director']->value;?>
<br>
<b>Продюсер:</b><?php echo $_smarty_tpl->tpl_vars['producer']->value;?>
<br>
<b>Актеры:</b><?php echo $_smarty_tpl->tpl_vars['actor']->value;?>
<br>
<b>Аннотация:</b>
<div style="font-family:monospace;background:whitesmoke;padding:1em"><?php echo $_smarty_tpl->tpl_vars['annotation']->value;?>
</div>
<?php $_smarty_tpl->_subTemplateRender('file:./../mailer_common/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
