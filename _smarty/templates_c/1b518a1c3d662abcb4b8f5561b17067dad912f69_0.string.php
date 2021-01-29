<?php
/* Smarty version 3.1.33, created on 2020-10-23 10:38:38
  from '1b518a1c3d662abcb4b8f5561b17067dad912f69' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f92887e435943_56705537',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f92887e435943_56705537 (Smarty_Internal_Template $_smarty_tpl) {
?><Div class="promo_page">
  <h1>Хоррор 404</h1>
  <video id="ssvid" controls poster="/media/media_content_poster/888/bb9587d81def7831592df5d00057ff15.SW_996H_560CF_1.jpg">
    <source src="https://kino-cache.cdnvideo.ru/kinoteatr/soap/886/lent/terreur404_ep01_mapremieremorte%20%281280x720%29.mp4">
  </video>
  <p class="margin_p_und_video">
    Неожиданно простой, но в то же время изобретательный хоррор-альманах на тему роли интернета в жизни современных людей. Рассказывает он о вполне интернациональных страхах, связанных с интернетом вообще и различными мобильными приложениями и сервисами в частности. В одном из эпизодов героиня пробует новый сервис вызова такси, в другом – постояльцы снимают у странной старухи квартиру через условный airbnb, в третьем – не сулит ничего хорошего полуночное свидание с парнем с сайта знакомств. Ужас и юмор, интернет-фобии и реалии настоящей жизни, неожиданные ситуации и ставшие привычными мобильные приложения и сайты.
  </p>
  </div>
<link rel="stylesheet" type="text/css" href="/assets/chill/player/plyr/plyr.css?v=a1600877231" data-id="dfdcd450fbe0672efe059f84f28b882f">
<link rel="stylesheet" type="text/css" href="/assets/chill/css/trailer_player_eve.css?v=a1600877231" data-id="92821f0788fc3156900afbc114eff21a">
<?php echo '<script'; ?>
 src="/assets/chill/player/plyr/plyr.min.js?v=a1600877231" data-id="eea597d2d3f88831c9f04858630b83cf"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
  const player = new Plyr('#ssvid');

  player.on('ended', event => {

    $("#login_cover").fadeIn(500);

  });
<?php echo '</script'; ?>
><?php }
}
