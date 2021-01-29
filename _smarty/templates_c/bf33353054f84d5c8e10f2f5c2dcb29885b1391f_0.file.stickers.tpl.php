<?php
/* Smarty version 3.1.33, created on 2020-07-30 14:27:59
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/stickers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f22aebf99acd2_51682098',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf33353054f84d5c8e10f2f5c2dcb29885b1391f' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/stickers.tpl',
      1 => 1596108478,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f22aebf99acd2_51682098 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="sticker_list_wrapper">
    <div id="sticker_list_wrapper-inside">
        <div id="sticker_list_window">
            <div id="close_sticker_list"><i class="mdi mdi-close"></i></div>
            <div class="sticker_list_inner">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['stickers']->value, 'sticker');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['sticker']->value) {
?>
                    <div class="one-sticker-from-list" data-id="<?php echo $_smarty_tpl->tpl_vars['sticker']->value->id;?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['sticker']->value->cdn_url;?>
" data-title="<?php echo $_smarty_tpl->tpl_vars['sticker']->value->name;?>
">
                        <div class="one-sticker-from-list-inner">
                            <img src="//<?php echo $_smarty_tpl->tpl_vars['sticker']->value->cdn_url;?>
" />
                        </div> 
                    </div>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
        </div>
    </div>
</div>
<?php }
}
