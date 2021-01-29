<?php
/* Smarty version 3.1.33, created on 2020-07-10 14:40:20
  from '31060c86eecd7984c6f5d68f2c0900c50e971127' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f0853a4a48116_15833167',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f0853a4a48116_15833167 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_last_contents.php','function'=>'smarty_function_get_last_contents',),));
echo smarty_function_get_last_contents(array(),$_smarty_tpl);?>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->items, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
if ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctCOLLECTION') {?>
<div class="chill-lenta-item-new chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
 col s12 l4">
  <div class="lenta_collection">
    <a href="/collection/<?php echo $_smarty_tpl->tpl_vars['item']->value->content_id;?>
" title="<?php echo $_smarty_tpl->tpl_vars['item']->value->name;?>
">

      <div class="chill_main_lent_block" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['image_urla']->value;?>
)">
      </div>
    </a>
  </div>
</div>
<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
