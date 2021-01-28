<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:49:57
  from '/var/VHOSTS/site/_layouts/login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4eb65469276_85003849',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7f3fd2527c66ca1c36624e3750ad52845fbaf832' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/login.tpl',
      1 => 1587114695,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./header.tpl' => 1,
  ),
),false)) {
function content_5ed4eb65469276_85003849 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/layout.css',0));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/css/efo.css',0));?>

<?php echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_script('/assets/js/efo.js',0,true));?>

<?php $_smarty_tpl->_subTemplateRender('file:./header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['OUT']->value->get('page_content');?>

</body>
</html>
<?php }
}
