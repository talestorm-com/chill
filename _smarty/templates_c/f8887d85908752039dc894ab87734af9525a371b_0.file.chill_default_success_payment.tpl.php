<?php
/* Smarty version 3.1.33, created on 2020-08-07 15:33:13
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_default_success_payment.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f2d4a09a92481_47982789',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f8887d85908752039dc894ab87734af9525a371b' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/CabinetController/chill_default_success_payment.tpl',
      1 => 1596095471,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f2d4a09a92481_47982789 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/css/lk_eve.css',0));?>

<div class="cabinet-message">
    <div>
        Платеж проведен успешно. Номер транзакции <?php echo $_smarty_tpl->tpl_vars['order_id']->value;?>
<br>
        Через несколько минут средства поступят на Ваш виртуальный счет.<br>
        Спасибо!
    </div>
    <div id="return_to_ser"><a>Вернуться к сериалу</a></div>
    <div id="return_to_ser_il">- или -</div>
    <div>
    <a href="/Profile">Перейти в профиль</a>
    </div>
</div>

<?php echo '<script'; ?>
>
$(document).ready(function(){
var aa = localStorage.getItem("soap");
if (aa != '' && aa != null){
	$("#return_to_ser").fadeIn(0);
    $("#return_to_ser_il").fadeIn(0);
	$("#return_to_ser a").attr("href",aa);
}
});
(function() {
window.dataLayer.push=window.dataLayerpush||[];
dataLayer.push({
'event': 'custom_event',
'event_category': 'balance',
'event_action': 'success',
'event_label: '100' });
}) ()


<?php echo '</script'; ?>
>
<?php }
}
