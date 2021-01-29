<?php
/* Smarty version 3.1.33, created on 2020-09-05 18:40:29
  from '/var/VHOSTS/site/_layouts/front/preload.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f53b16dd9d255_25984261',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5a320b017acfca4c919fd0b1e320165552473845' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/preload.tpl',
      1 => 1599320428,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f53b16dd9d255_25984261 (Smarty_Internal_Template $_smarty_tpl) {
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
