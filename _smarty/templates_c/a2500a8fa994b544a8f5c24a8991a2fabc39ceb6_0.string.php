<?php
/* Smarty version 3.1.33, created on 2020-11-13 10:02:01
  from 'a2500a8fa994b544a8f5c24a8991a2fabc39ceb6' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fae2f69154255_43392195',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fae2f69154255_43392195 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="/assets/chill/player/plyr/plyr.css?v=a1600877231" data-id="dfdcd450fbe0672efe059f84f28b882f">
<link rel="stylesheet" type="text/css" href="/assets/chill/css/trailer_player_eve.css?v=a1600877231" data-id="92821f0788fc3156900afbc114eff21a">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&display=swap" rel="stylesheet">


<div class="promo_page">
  <div id="top_barnding_gap"></div>
  <h1 id="main_header">В яблочко! Парни-лучники</h1>
  <div id="main_subheader">Яркая корейская комедия для тех, кто любит залипать в TikTok</div>
  <div id="promo_page_video">
    <video id="ssvid" controls poster="/media/media_content_poster/1217/f2b87858f6b9ab85ff03b6e6a63f3760.SW_996H_560CF_1.jpg">
      <source src="https://kino-cache.cdnvideo.ru/kinoteatr/soap/1215/lent/matching%20boys%20archery_001_15mb%20%281280x720%29.mp4">
    </video>
  </div>
  <p class="margin_p_und_video">
    Главная героиня этой зажигательной истории — талантливая, однако пока неуспешная художница комиксов Хон Шин А. Но однажды она находит идеальный источник вдохновения — команду веселых парней-лучников, которые и становятся героями ее новых работ. Проекты из Южной Кореи всегда отличают яркая картинка, оригинальный юмор и самобытный взгляд на мир.
  </p>
</div>

<div class="row">
  <div class="col s12"> 
    <div id="full_970" style="margin-top: 20px;">
      <a class="landing-link" href="https://chillvision.ru/#login_open">
        <img class="landing-img-btn" width="100%" style="max-width: 400px;" src="/assets/chill/images/banners/horror.jpg">
      </a>
    </div>
  </div>
</div>

<div id="promo_see_more">
  <h2>Смотрите больше корейских веб-сериалов на CHILL</h2>
  <div id="promo_films" class="owl-carousel">
    <div class='one_promo_film chill-season-series-list-item' data-merge='2'><a href='/Soap/2027'><img src='https://chillvision.ru/media/media_content_poster/2027/7d5368fe484745053288d223afe10d0b.SW_200H_260CF_1.jpg'></a><h3><a href='/Soap/2027'>Обычная любовь</a></h3></div>
  </div>
</div>



<?php echo '<script'; ?>
 src="/assets/chill/player/plyr/plyr.min.js?v=a1600877231" data-id="eea597d2d3f88831c9f04858630b83cf"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>




  $(document).ready(function(){

    $("body").prepend('<div id="top_branding"><div id="top_branding_image"></div></div>');


    $.getJSON('https://chillvision.ru/Public/API?action=collection&id=356').done(function(data){

      $.each(data.collection.items, function(i, item){
        console.log(item);
        var item_id = item.content_id;
        var item_image = item.default_poster;
        var item_name = item.common_name;

        $("#promo_films").append("<div class='one_promo_film chill-season-series-list-item' data-merge='2'><a href='/Soap/" + item_id + "'><img src='https://chillvision.ru/media/media_content_poster/" + item_id + "/" + item_image + ".SW_200H_260CF_1.jpg'></a><h3><a href='/Soap/" + item_id + "'>" + item_name + "</a></h3></div>");
      });
    });

    $('#promo_films').owlCarousel();
    
  });

<?php echo '</script'; ?>
>

<style>

  div#page_body #promo_films .one_promo_film.chill-season-series-list-item h3 {
    position: relative;
    left: 0;
    background: 0 0;
    bottom: auto;
    height: auto;
    line-height: 20px;
    top: auto;
    font-size: 14px;
    font-weight: normal;
    color: #fff;
  }

  #promo_films .one_promo_film.chill-season-series-list-item h3 a {
    color: #fff;
  }

  div#page_body div#promo_see_more h2 {
    margin: 0;
    margin-bottom: 20px;
    margin-top: 40px;
    font-size: 17px;
    color: #18fef1;
  }
  div#vid_vid_out {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: auto;
    overflow: hidden;
    background-color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  video#myVideo {
    width: 100%;
    opacity: 0.5;
  }

  #top_branding {
    position: absolute;
    top: 52px;
    left: 0;
    width: 100%;
    height: 100vh;
  }

  #top_branding_image {
    width: 100%;
    max-width: 1920px;
    height: 100vh;
    margin: 0 auto;
    background-color: #242424;
    background-image: url('/media/landing/v_yablochko_v3.jpg');
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: center top;
  }
  #top_barnding_gap {
    width: 100%;
    height: calc(100vw / 3.8);
  }

  #main_header {
    color: #f75f97;
    font-weight: bold;
    font-size: 3rem;
    text-align: center;

    -webkit-text-stroke-width: 2px;
    -webkit-text-stroke-color: #ffffff;
    -webkit-text-fill-color: #f75f97;
    font-family: 'Montserrat', sans-serif;

    text-shadow: 1px 1px 2px rgba(0, 0, 0, 1);
  }

  #main_subheader {
    color: #ffffff;
    font-weight: bold;
    font-size: 1rem;
    text-align: center;
    font-family: 'Montserrat', sans-serif;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 1);
    margin-bottom: 10px;
  }

  .owl-nav {
    display:block!important;
  }


  @media all and (min-width: 1px) and (max-width: 640px)
  {
    #main_header {
      font-size: 2rem;
    }
  }

  @media all and (min-width: 641px) and (max-width: 800px)
  {
    #main_header {
      font-size: 2.5rem;
    }
  }

</style><?php }
}
