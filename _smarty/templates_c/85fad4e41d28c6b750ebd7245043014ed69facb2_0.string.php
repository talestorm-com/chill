<?php
/* Smarty version 3.1.33, created on 2020-11-11 16:26:21
  from '85fad4e41d28c6b750ebd7245043014ed69facb2' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fabe67d3d6db0_61863066',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fabe67d3d6db0_61863066 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="promo_page">
  <div id="top_barnding_gap"></div>
  <h1 id="main_header">В яблочко! Парни-лучники</h1>
  <p class="promo_page_p">Яркая корейская комедия для тех, кто любит залипать в TikTok</p>
  <div id="promo_page_video">
    <video id="ssvid" controls poster="/media/media_content_poster/1217/f2b87858f6b9ab85ff03b6e6a63f3760.SW_996H_560CF_1.jpg">
      <source src="https://kino-cache.cdnvideo.ru/kinoteatr/soap/886/lent/terreur404_ep01_mapremieremorte%20%281280x720%29.mp4">
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
  <div id="promo_films" class="owl-carousel"></div>
</div>

<link rel="stylesheet" type="text/css" href="/assets/chill/player/plyr/plyr.css?v=a1600877231" data-id="dfdcd450fbe0672efe059f84f28b882f">
<link rel="stylesheet" type="text/css" href="/assets/chill/css/trailer_player_eve.css?v=a1600877231" data-id="92821f0788fc3156900afbc114eff21a">

<?php echo '<script'; ?>
 src="/assets/chill/player/plyr/plyr.min.js?v=a1600877231" data-id="eea597d2d3f88831c9f04858630b83cf"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
  const player = new Plyr('#ssvid');
  var aaa = '<div id="promo_page_video_bg_out"><div id="promo_page_video_bg"><a href="/Soap/886#login_open">Зарегистрируйся на CHILL, <br>смотри следующие серии бесплатно.</a></div></div>';
  $("#promo_page_video .plyr").append(aaa);
  player.on('timeupdate', event => {
    var a = player.currentTime;
    console.log(a);
    if (a > 509 && a < 550) {
      $("#promo_page_video_bg_out").fadeIn(0);
    } else {
      $("#promo_page_video_bg_out").fadeOut(0);
    }
  });
  player.on('ended', event => {

    $("#promo_page_video_bg_out").fadeIn(0);

  });
  $(document).ready(function() {
    var a = "https://chillvision.ru/Public/API?action=genre&id=356"
    var b = "https://chillvision.ru/Public/API?action=genre&id=9"
    $.getJSON(b)
      .done(function(data) {
      $.each(data.list.soap, function( i, item ) {
        var c = item.id;
        var l = item.image;
        var n = item.name;
        if (c !='886'){
          $("#promo_films").append("<div class='one_promo_film chill-season-series-list-item' data-merge='2'><a href='/Soap/"+c+"'><img src='https://chillvision.ru/media/media_content_poster/"+c+"/"+l+".SW_200H_260CF_1.jpg'></a><h3><a href='/Soap/"+c+"'>"+n+"</a></h3></div>");
        }
      });
    });
    $.getJSON(a)
      .done(function(data) {
      $.each(data.list.soap, function( i, item ) {
        var c = item.id;
        var l = item.image;
        var n = item.name;
        if(c !='886'){
          $("#promo_films").append("<div class='one_promo_film chill-season-series-list-item' data-merge='2'><a href='/Soap/"+c+"'><img src='https://chillvision.ru/media/media_content_poster/"+c+"/"+l+".SW_200H_260CF_1.jpg'></a><h3><a href='/Soap/"+c+"'>"+n+"</a></h3></div>");
        }
      });

      $("#promo_films").owlCarousel({

        loop:false,
        margin:10,
        merge:true,
        responsive:{
          0:{
            items:5
          },
          600:{
            items:7
          },
          1000:{
            items:9
          }
        }
      });
    });
  });


  $(document).ready(function(){
    $("body").prepend('<div id="top_branding"><div id="top_branding_image"></div></div>');
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
    max-width: 2000px;
    height: 100vh;
    margin: 0 auto;
    background-color: #242424;
    background-image: url('/media/landing/v_yablochko_v1.jpg');
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: center top;
  }
  #top_barnding_gap {
    width: 100%;
    height: calc(100vw / 3.8);
  }

  #main_header {
    color: #f6be02;
    font-weight: bold;
    font-size: 3.5rem;
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
