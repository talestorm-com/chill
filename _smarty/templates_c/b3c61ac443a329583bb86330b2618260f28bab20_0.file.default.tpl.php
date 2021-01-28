<?php
/* Smarty version 3.1.33, created on 2020-06-01 11:49:57
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed4eb65461ef7_39637110',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b3c61ac443a329583bb86330b2618260f28bab20' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/AuthController/default.tpl',
      1 => 1555679886,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed4eb65461ef7_39637110 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
Content">
    <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
ContentInner">
        <?php echo '<?xml ';?>version="1.0" encoding="UTF-8" standalone="no"<?php echo '?>';?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="100px" height="100px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M59.6 0h8v40h-8V0z" fill="#000000" fill-opacity="1"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(30 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(60 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(90 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(120 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#b2b2b2" fill-opacity="0.3" transform="rotate(150 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#999999" fill-opacity="0.4" transform="rotate(180 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#7f7f7f" fill-opacity="0.5" transform="rotate(210 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#666666" fill-opacity="0.6" transform="rotate(240 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#4c4c4c" fill-opacity="0.7" transform="rotate(270 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#333333" fill-opacity="0.8" transform="rotate(300 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#191919" fill-opacity="0.9" transform="rotate(330 64 64)"/><animateTransform attributeName="transform" type="rotate" values="0 64 64;30 64 64;60 64 64;90 64 64;120 64 64;150 64 64;180 64 64;210 64 64;240 64 64;270 64 64;300 64 64;330 64 64" calcMode="discrete" dur="960ms" repeatCount="indefinite"></animateTransform></g></svg>
    </div>
</div>
<?php echo '<script'; ?>
>
    window.JQR = window.JQR || [];
    window.JQR.push(function () {
    
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                window.Eve.EFO.Events.GEM().on("SYS_LOGIN_SUCCESS", window, function () {
                    var return_url = '<?php echo $_smarty_tpl->tpl_vars['OUT']->value->get('login_return_url');?>
';
                    window.location.href = return_url;
                });
                window.Eve.EFO.Com().load('system.login').done(function (x) {
                    x.show();
                });
            });
    
        });
<?php echo '</script'; ?>
><?php }
}
