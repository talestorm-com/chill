<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:18:00
  from '/var/VHOSTS/site/_views/mailer/restore/phase2/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed5384836e029_84969824',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '960ea6edaa48bb35e867fa45a9d3ff373278c7dd' => 
    array (
      0 => '/var/VHOSTS/site/_views/mailer/restore/phase2/index.tpl',
      1 => 1587475844,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./../../mailer_common/header.tpl' => 1,
    'file:./../../mailer_common/footer.tpl' => 1,
  ),
),false)) {
function content_5ed5384836e029_84969824 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender('file:./../../mailer_common/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<h3><?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
</h3>
Уважаемый <?php echo $_smarty_tpl->tpl_vars['user_info']->value->name;?>
 <?php echo $_smarty_tpl->tpl_vars['user_info']->value->eldername;?>
!
<br><br>
Пароль от Вашего личного кабинета на сайте <?php echo $_smarty_tpl->tpl_vars['host']->value;?>
 был сброшен.<br>
Ваш новый пароль:<b style="font-size:1.1em"><?php echo $_smarty_tpl->tpl_vars['new_password']->value;?>
</b><br>
<br>
Вы можете изменить пароль на более удобный в Вашем <a href="<?php if ($_smarty_tpl->tpl_vars['https']->value) {?>https://<?php } else { ?>http://<?php }
echo $_smarty_tpl->tpl_vars['host']->value;?>
/Profile">личном кабинете</a>
<br>
<br>
<br>

<?php $_smarty_tpl->_subTemplateRender('file:./../../mailer_common/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
