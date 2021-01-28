<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:20:02
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/PageController/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4e4627351e4_85541298',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b1bce631cac7b06bdf3258ff4af194f9ac53fe3e' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/PageController/page.tpl',
      1 => 1564558792,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed4e4627351e4_85541298 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="CommonPageWrapper <?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
PageWrapper Infopage_<?php echo $_smarty_tpl->tpl_vars['page']->value->properties->get('css');?>
 <?php if (!($_smarty_tpl->tpl_vars['page']->value->properties->get_filtered('system',array('Boolean','DefaultFalse')))) {?> CommonInfoPage <?php }?>">    
    <?php echo $_smarty_tpl->tpl_vars['page']->value->render_content();?>

</div><?php }
}
