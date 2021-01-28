<?php
/* Smarty version 3.1.33, created on 2020-09-17 23:26:03
  from '/var/VHOSTS/site/_views/modules/content/RibbonLent/insets/inc/menu_profile.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f63c65b368975_35701818',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '912c9173fc17c7a9ab6309931b52911b02f25556' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/RibbonLent/insets/inc/menu_profile.tpl',
      1 => 1600372221,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f63c65b368975_35701818 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_user_auth_status.php','function'=>'smarty_function_get_user_auth_status',),));
?>
<div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
    <div class="author_block_main">
        <?php ob_start();
echo smarty_function_get_user_auth_status(array(),$_smarty_tpl);
$_prefixVariable2 = ob_get_clean();
if ($_prefixVariable2) {?>
            <a href="/page/menu">
                <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/youchill.jpg);background-color:#ffce14">
                </div>
            </a>
        <?php } else { ?>
            <a href="/Profile">
                <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/pl_a.gif)">
                </div>
            </a>
        <?php }?>
    </div>
</div><?php }
}
