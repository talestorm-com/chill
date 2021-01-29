<?php
/* Smarty version 3.1.33, created on 2020-07-18 19:02:39
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_test_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f131d1f1e49a4_38832205',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fdc0a7932fba12877456a43506fcc3535e6ea222' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_test_menu.tpl',
      1 => 1595088155,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f131d1f1e49a4_38832205 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.visit_counter.php','function'=>'smarty_function_visit_counter',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.display_menu_lent.php','function'=>'smarty_function_display_menu_lent',),));
?>
<div style='color:white'>
    <h1><?php echo smarty_function_visit_counter(array(),$_smarty_tpl);?>
</h1>
<?php echo smarty_function_display_menu_lent(array('assign'=>'items'),$_smarty_tpl);?>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
    <?php echo print_r($_smarty_tpl->tpl_vars['item']->value);?>

<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>

<?php }
}
