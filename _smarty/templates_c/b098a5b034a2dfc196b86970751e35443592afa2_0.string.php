<?php
/* Smarty version 3.1.33, created on 2020-10-23 10:13:22
  from 'b098a5b034a2dfc196b86970751e35443592afa2' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f928292c03ab5_91447882',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f928292c03ab5_91447882 (Smarty_Internal_Template $_smarty_tpl) {
?><Div class="promo_page">
  <h1>Хоррор 404</h1>
  <video id="ssvid" controls poster="/media/media_content_poster/888/bb9587d81def7831592df5d00057ff15.SW_996H_560CF_1.jpg">
    <source src="https://kino-cache.cdnvideo.ru/kinoteatr/soap/886/lent/terreur404_ep01_mapremieremorte%20%281280x720%29.mp4">
  </video>
  <p class="margin_p_und_video">
    Неожиданно простой, но в то же время изобретательный хоррор-альманах на тему роли интернета в жизни современных людей. Рассказывает он о вполне интернациональных страхах, связанных с интернетом вообще и различными мобильными приложениями и сервисами в частности. В одном из эпизодов героиня пробует новый сервис вызова такси, в другом – постояльцы снимают у странной старухи квартиру через условный airbnb, в третьем – не сулит ничего хорошего полуночное свидание с парнем с сайта знакомств. Ужас и юмор, интернет-фобии и реалии настоящей жизни, неожиданные ситуации и ставшие привычными мобильные приложения и сайты.
  </p>
  </div>
<?php echo '<script'; ?>
>
  var ssvid = document.getElementById('ssvid');
  var tick = ssvid.currentTime;

  ssvid.addEventListener('timeupdate', function(e) {
    console.log('currentTime: ' + tick);
    tick++
    vidHandler(tick);
  }, false);


  function vidHandler(time) {
    switch (time) {
      case 2:
        console.log('2 second mark');
        break;
      case 13:
        console.log('13 second mark');
        break;
      case 15:
        console.log('15 second mark');
        break;

      default:
        return false;
    }
  }<?php }
}
