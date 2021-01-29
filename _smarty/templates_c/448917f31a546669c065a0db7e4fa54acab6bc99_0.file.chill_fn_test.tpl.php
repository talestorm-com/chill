<?php
/* Smarty version 3.1.33, created on 2020-07-10 20:12:18
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_fn_test.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f08a172ccdbd1_97211184',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '448917f31a546669c065a0db7e4fa54acab6bc99' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_fn_test.tpl',
      1 => 1587860490,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f08a172ccdbd1_97211184 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_emoji_list.php','function'=>'smarty_function_get_emoji_list',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_genre_list.php','function'=>'smarty_function_get_genre_list',),2=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_last_contents.php','function'=>'smarty_function_get_last_contents',),));
?>
<div style='color:white'>
<?php echo smarty_function_get_emoji_list(array('assign'=>'emoji_list'),$_smarty_tpl);?>
       
<?php echo smarty_function_get_genre_list(array('assign'=>'genre_list'),$_smarty_tpl);?>

emohis:
<ul>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['emoji_list']->value, 'emo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['emo']->value) {
?>
        <li><?php echo $_smarty_tpl->tpl_vars['emo']->value['id'];?>
. <?php echo $_smarty_tpl->tpl_vars['emo']->value['name'];?>
</li>     <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>

genres:
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['genre_list']->value, 'gen');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['gen']->value) {
?>
    <li><?php echo $_smarty_tpl->tpl_vars['gen']->value['id'];?>
. <?php echo $_smarty_tpl->tpl_vars['gen']->value['name'];?>
</li>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>


<?php echo smarty_function_get_last_contents(array('assign'=>'content_list','q'=>3,'ct'=>'ctCOLLECTION'),$_smarty_tpl);?>

contents - 1:
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['content_list']->value, 'co');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['co']->value) {
?>
    <li><?php echo $_smarty_tpl->tpl_vars['co']->value->id;?>
. <?php echo $_smarty_tpl->tpl_vars['co']->value->name;?>
 (<?php echo $_smarty_tpl->tpl_vars['co']->value->content_type;?>
)</li><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>


<?php echo smarty_function_get_last_contents(array('assign'=>'content_list','q'=>3,'ct'=>'ctCOLLECTION,ctSEASON'),$_smarty_tpl);?>

contents - 2:
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
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>


<?php echo smarty_function_get_last_contents(array('assign'=>'content_list','q'=>10,'ct'=>'ctTEXT,ctSEASON'),$_smarty_tpl);?>

contents - 3:
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
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php }
}
