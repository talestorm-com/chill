<?php
/* Smarty version 3.1.33, created on 2020-07-10 14:44:22
  from '8497f2c312a947127dc58aad62efc2772a258c33' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f08549689ac64_92565991',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f08549689ac64_92565991 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_last_contents.php','function'=>'smarty_function_get_last_contents',),));
echo smarty_function_get_last_contents(array('assign'=>'content_list','q'=>3,'ct'=>'ctSEASON'),$_smarty_tpl);?>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['content_list']->value, 'co');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['co']->value) {
?>
<li><?php echo $_smarty_tpl->tpl_vars['co']->value->id;?>
. <?php echo $_smarty_tpl->tpl_vars['co']->value->name;?>
 (<?php echo $_smarty_tpl->tpl_vars['co']->value->content_type;?>
)</li>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
