<?php
/* Smarty version 3.1.33, created on 2021-01-29 09:19:21
  from '/data/_layouts/front/preload.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_601370a9e6f023_76110198',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c432ec95d99b58dd0df6a2b03aab9f57e9bdff9d' => 
    array (
      0 => '/data/_layouts/front/preload.tpl',
      1 => 1611292660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_601370a9e6f023_76110198 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="preloader">
  <div class="preloader__image">
    <img src="/assets/chill/images/logo.png">
  </div>
</div>
<?php echo '<script'; ?>
>
  window.onload = function () {
    document.body.classList.add('loaded_hiding');
    window.setTimeout(function () {
      document.body.classList.add('loaded');
      document.body.classList.remove('loaded_hiding');
    }, 500);
  }
<?php echo '</script'; ?>
>

<?php }
}
