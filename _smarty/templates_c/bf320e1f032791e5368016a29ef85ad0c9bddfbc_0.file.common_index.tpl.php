<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:01:35
  from '/var/VHOSTS/site/_views/controllers/admin/common_index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed5346fd4dd97_11557488',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf320e1f032791e5368016a29ef85ad0c9bddfbc' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/admin/common_index.tpl',
      1 => 1559126736,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed5346fd4dd97_11557488 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="AdminLayoutPageContentContent <?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
MainWrapper" id="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
APP">
    <?php ob_start();
echo $_smarty_tpl->tpl_vars['controller']->value->common_templtes("preloader");
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_subTemplateRender($_prefixVariable1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
</div>
<?php echo '<script'; ?>
>
    (function () {
        var CMP = "<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
APP";
    
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                EFO.Com().load("<?php echo $_smarty_tpl->tpl_vars['controller']->value->get_desktop_component_id();?>
").done(window, function (x) {
                    document.getElementById(CMP).innerHTML = '';
                    x.install(CMP);
                }).fail(window, function () {
                    document.getElementById(CMP).innerHTML = "component load error";
                });
            });
    
        })();
<?php echo '</script'; ?>
><?php }
}
