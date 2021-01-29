<?php
/* Smarty version 3.1.33, created on 2020-08-18 22:54:43
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/form.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f3c3203089542_85139597',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1676c577248a677530c3cb44bbfa10f27d69f451' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/form.tpl',
      1 => 1597780352,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f3c3203089542_85139597 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="form_com_chill">
    <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
form">
        <div class="row">
            <div class="col s12">
                <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
formcellbody"><textarea id="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
text" placeholder="Напишите отзыв"></textarea></div>
            </div>
            <div class="col s6 l3">
                <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
formfooterbutton" id="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
send">Отправить</div>
            </div>
            <div class="col s3 offset-s3 l2 offset-l1 right-align">
                <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
formcellbody" id="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
sticker_place">
                    <i class="mdi-tooltip-image-outline mdi"></i>
                </div>
                <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
sticker_field">
                <input type='hidden' id='<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
token' value='<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('comchill');?>
' />
            </div>
        </div>
    </div>
</div><?php }
}
