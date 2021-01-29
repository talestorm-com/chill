<?php
/* Smarty version 3.1.33, created on 2020-06-01 12:32:42
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/login_cab.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4f56aaef3f2_42630075',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '01ca36eead21061630b0fb46a0743b2afe482091' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/login_cab.tpl',
      1 => 1587338134,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed4f56aaef3f2_42630075 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/css/lk_login.css',1000));?>


    <?php echo '<script'; ?>
>
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                EFO.Events.GEM().on('LOGIN_SUCCESS', window, function () {
                    window.location.reload();
                });
                check_ready();
                function check_ready() {
                    if (U.isCallable(window.run_authorization_sequence)) {
                        window.run_authorization_sequence();
                    } else {
                        window.setTimeout(check_ready, 100);
                    }
                }
            });
        })();
    <?php echo '</script'; ?>
>
<?php }
}
