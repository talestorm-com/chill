<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:50:05
  from '/var/VHOSTS/site/_layouts/admin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4eb6d76c854_40357093',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '078c49e32279168ecb2164e05a250469b18da45e' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/admin.tpl',
      1 => 1571742324,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./header.tpl' => 1,
    'file:./admin_menu.tpl' => 1,
  ),
),false)) {
function content_5ed4eb6d76c854_40357093 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender('file:./header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</head>
<body>
    <div class="AdminLayoutBodyInner">
        <div class="AdminLayoutMainMenuWrapper"><?php $_smarty_tpl->_subTemplateRender('file:./admin_menu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?></div>
        <div class="AdminLayoutPageContentWrapper">
            <?php echo $_smarty_tpl->tpl_vars['OUT']->value->get('page_content');?>

        </div>
        <div class="AdminLayoutFooter">
            <div class="AdminLayoutFooterInner">
                Development & support by <a href="https://inclu.work/" target="_blank">FRONTON&TRADE;</a> <?php echo $_smarty_tpl->tpl_vars['controller']->value->get_current_year();?>

            </div>
        </div>
    </div>
</body>
</html>
<?php }
}
