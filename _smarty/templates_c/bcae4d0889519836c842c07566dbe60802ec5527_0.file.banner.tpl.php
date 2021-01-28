<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:34:35
  from '/var/VHOSTS/site/_views/controllers/admin/MediaContentController/banner.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed53c2b1a2f42_29647274',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bcae4d0889519836c842c07566dbe60802ec5527' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/admin/MediaContentController/banner.tpl',
      1 => 1586815307,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed53c2b1a2f42_29647274 (Smarty_Internal_Template $_smarty_tpl) {
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
                EFO.Com().load("desktop.BannerList").done(window, function (x) {
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
