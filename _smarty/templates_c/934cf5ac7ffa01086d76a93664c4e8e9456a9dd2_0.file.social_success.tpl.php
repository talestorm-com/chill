<?php
/* Smarty version 3.1.33, created on 2020-09-07 17:44:53
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/social_success.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f56476594e735_61329992',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '934cf5ac7ffa01086d76a93664c4e8e9456a9dd2' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/social_success.tpl',
      1 => 1599489737,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f56476594e735_61329992 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
    <head>
        <?php echo '<script'; ?>
>
            try {
               window.opener.postMessage('SOCIAL_LOGIN_SUCCESS_<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['created']->value;?>
', '*');                
            } catch (e) {
                console.log(e);
                try {
                    localStorage.setItem('SOCIAL_LOGIN_SUCCESS', '<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['created']->value;?>
');
                    localStorage.setItem('SOCIAL_LOGIN_SUCCESS', '*');
                } catch (e) {
                    console.log(e);
                }
            }
            window.close();
        <?php echo '</script'; ?>
>
    </head>
    <body>
        Авторизация успешна!
    </body>
</html><?php }
}
