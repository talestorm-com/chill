<?php
/* Smarty version 3.1.33, created on 2020-08-31 17:30:10
  from '/var/VHOSTS/site/_views/modules/content/MenuLent/item_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f4d09724a3261_60922512',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bc9e97f0e5343056ad8f8f784b7b080b43a2c882' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/MenuLent/item_list.tpl',
      1 => 1598884209,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f4d09724a3261_60922512 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="row">
<!--<div class="col s6 m4 l3 chill-lenta-item-new chill-lenta-item-new-static div_kv">
                                <div id="what_chill">
                                </div>
                            </div>-->
                            <div class="col s6 m4 l3 chill-lenta-item-new chill-lenta-item-new-static div_kv">
                            <a href="/profile">
                                <div id="what_chill_iz" style="background-image:url(/assets/chill/images/wicz_aa.jpg)">
                                </div>
                                </a>
                            </div>
                            <div class="col s6 m4 l3 chill-lenta-item-new chill-lenta-item-new-static div_kv">
                            <a href="/page/for_authors">
                                <div id="what_chill_iz">
                                </div>
                                </a>
                            </div>

    <?php $_smarty_tpl->_assignInScope('index', -1);?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this']->value->items, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
    <?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['index']->value+1);?>
    <?php if ($_smarty_tpl->tpl_vars['index']->value > 14) {?>
    <?php $_smarty_tpl->_assignInScope('index', 1);?>
    <?php }?>
    


<!--
  <?php if ($_smarty_tpl->tpl_vars['index']->value === 2) {?>
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s6 m4 l3 div_kv">
        <div class="author_block_main">
            
            <a href="/Profile">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/pl_a.gif)">
            </div>
            </a>

        </div>
    </div>
    <?php }?>
-->
<?php if ($_smarty_tpl->tpl_vars['index']->value === 3) {?>
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s6 m4 l3 div_kv">
        <div class="emo_block_main">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/1307_emo.gif)">
            </div>
        </div>
    </div>
    <?php }?>




    <?php $_smarty_tpl->_assignInScope('image_url', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_600H_400CF_1.jpg");?>
    <?php $_smarty_tpl->_assignInScope('image_urla', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_300H_300CF_1.jpg");?>
    <?php $_smarty_tpl->_assignInScope('image_url_sq', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_400H_400CF_1.jpg");?>
    <?php $_smarty_tpl->_assignInScope('image_url_qq', "/media/".((string)$_smarty_tpl->tpl_vars['item']->value->get_image_url()).".SW_400H_520CF_1.jpg");?>
    <?php if ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctBANNER') {?>
    <?php if ($_smarty_tpl->tpl_vars['item']->value->id != 213) {?>
    <div class="chill-lenta-item-new chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
 col s6 m4 l3 div_kv">
    <div class="banner_collection">
        <a <?php if ($_smarty_tpl->tpl_vars['item']->value->banner_url != '') {?>href="<?php echo $_smarty_tpl->tpl_vars['item']->value->banner_url;?>
" target="_blank"<?php }?> class="ribbon_link_out">
        <div class="chill_main_lent_block" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['image_urla']->value;?>
)">
            </div>
            </a>
     
          
         
        </div>
    </div>
    <?php }?>
    <?php } elseif ($_smarty_tpl->tpl_vars['item']->value->content_type === 'ctCOLLECTION') {?>
    <div class="chill-lenta-item-new chill-lenta-item-new-<?php echo $_smarty_tpl->tpl_vars['item']->value->content_type;?>
 col s6 m4 l3 div_kv">
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
    <?php }?>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div><?php }
}
