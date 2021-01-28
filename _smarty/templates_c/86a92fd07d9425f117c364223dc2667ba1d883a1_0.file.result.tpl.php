<?php
/* Smarty version 3.1.33, created on 2020-06-03 22:21:03
  from '/var/VHOSTS/site/_views/controllers/MediaAPI/ImageFlyController/result.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed7f81f077e75_58212805',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '86a92fd07d9425f117c364223dc2667ba1d883a1' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/MediaAPI/ImageFlyController/result.tpl',
      1 => 1557145528,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed7f81f077e75_58212805 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
>
    (function () {
        var upload_log = <?php echo json_encode($_smarty_tpl->tpl_vars['OUT']->value->getOpt('upload_log',array()));?>
;
        var upload_error = <?php echo json_encode($_smarty_tpl->tpl_vars['OUT']->value->getOpt('upload_error',array()));?>
;
        var list = <?php echo json_encode($_smarty_tpl->tpl_vars['OUT']->value->getOpt('list',array()));?>
;
        var callback_name = '<?php echo $_smarty_tpl->tpl_vars['OUT']->value->get('callback_name');?>
';
        try {
            window.opener[callback_name](JSON.stringify(upload_log), JSON.stringify(upload_error), JSON.stringify(list));
        } catch (ee) {
            try {
                window.parent[callback_name](JSON.stringify(upload_log), JSON.stringify(upload_error), JSON.stringify(list));
            } catch (eee) {

            }
        }
    })();
<?php echo '</script'; ?>
><?php }
}
