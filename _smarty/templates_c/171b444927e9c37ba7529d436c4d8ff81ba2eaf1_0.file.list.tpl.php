<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:50:05
  from '/var/VHOSTS/site/_views/controllers/admin/UsersController/list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4eb6d768e39_50024716',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '171b444927e9c37ba7529d436c4d8ff81ba2eaf1' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/admin/UsersController/list.tpl',
      1 => 1555922318,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed4eb6d768e39_50024716 (Smarty_Internal_Template $_smarty_tpl) {
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
                EFO.Com().load("desktop.users").done(window, function (x) {
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
