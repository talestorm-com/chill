<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:08:02
  from '/var/VHOSTS/site/_views/mailer/restore/phase1/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed535f2c21bd3_57459658',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd4a543c92a5b2fd9a4ff7373a547bfdfd9beb03b' => 
    array (
      0 => '/var/VHOSTS/site/_views/mailer/restore/phase1/index.tpl',
      1 => 1577028014,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./../../mailer_common/header.tpl' => 1,
    'file:./../../mailer_common/footer.tpl' => 1,
  ),
),false)) {
function content_5ed535f2c21bd3_57459658 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender('file:./../../mailer_common/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<h3><?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
</h3>
Уважаемый <?php echo $_smarty_tpl->tpl_vars['user_info']->value->name;?>
 <?php echo $_smarty_tpl->tpl_vars['user_info']->value->eldername;?>
!
<br><br>
Для Вашего личного кабинета на сайте <?php echo $_smarty_tpl->tpl_vars['host']->value;?>
 была запрошена процедура сброса пароля.<br>
<br>
<br>
<?php ob_start();
if ($_smarty_tpl->tpl_vars['https']->value) {
echo "https://";
} else {
echo "http://";
}
$_prefixVariable1=ob_get_clean();
$_smarty_tpl->_assignInScope('link', $_prefixVariable1.((string)$_smarty_tpl->tpl_vars['host']->value)."/Auth/restore?user=".((string)$_smarty_tpl->tpl_vars['user_info']->value->login)."&validate=".((string)$_smarty_tpl->tpl_vars['user_info']->value->generate_sequrity_hash()));?>
Для завершения сброса пройдите по ссылке:<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['link']->value;?>
</a>
<br>
<br>
Если Вы не запрашивали сброс пароля - <b>Ничего делать не надо</b>.
<br>
<br>
<br>

<?php $_smarty_tpl->_subTemplateRender('file:./../../mailer_common/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
