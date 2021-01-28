<?php
/* Smarty version 3.1.33, created on 2020-10-29 18:46:39
  from '99344d7a708235c8c2545d8baa238c68305f7dee' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f9ae3df279606_64680144',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f9ae3df279606_64680144 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="promo_page">
  <h1>Хоррор 404</h1>
  <p class="promo_page_p">Первая серия</p>
  <div id="promo_page_video">
    <video id="ssvid" controls poster="/media/media_content_poster/888/bb9587d81def7831592df5d00057ff15.SW_996H_560CF_1.jpg">
      <source src="https://kino-cache.cdnvideo.ru/kinoteatr/soap/886/lent/terreur404_ep01_mapremieremorte%20%281280x720%29.mp4">
    </video>
  </div>
  <p class="margin_p_und_video">
    Неожиданно простой, но в то же время изобретательный хоррор-альманах на тему роли интернета в жизни современных людей. Рассказывает он о вполне интернациональных страхах, связанных с интернетом вообще и различными мобильными приложениями и сервисами в частности. В одном из эпизодов героиня пробует новый сервис вызова такси, в другом – постояльцы снимают у странной старухи квартиру через условный airbnb, в третьем – не сулит ничего хорошего полуночное свидание с парнем с сайта знакомств. Ужас и юмор, интернет-фобии и реалии настоящей жизни, неожиданные ситуации и ставшие привычными мобильные приложения и сайты.
  </p>
</div>

<div id="promo_see_more">
  <h2>Смотрите еще в жанре <b>хоррор</b> на Chill</h2>
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
    var a = "https://chillvision.ru/Public/API?action=genre&id=13"
    var b = "https://chillvision.ru/Public/API?action=genre&id=9"
    $.getJSON(b)
      .done(function(data) {
      $.each(data.list.soap, function( i, item ) {
        var c = item.id;
        var l = item.image;
        var n = item.name;
        $("#promo_films").append("<div class='one_promo_film chill-season-series-list-item' data-merge='2'><a href='/Soap/"+c+"'><img src='https://chillvision.ru/media/media_content_poster/"+c+"/"+l+".SW_200H_260CF_1.jpg'></a><h3><a href='/Soap/"+c+"'>"+n+"</a></h3></div>");
      });
    });
    $.getJSON(a)
      .done(function(data) {
      $.each(data.list.soap, function( i, item ) {
        var c = item.id;
        var l = item.image;
        var n = item.name;
        $("#promo_films").append("<div class='one_promo_film chill-season-series-list-item' data-merge='2'><a href='/Soap/"+c+"'><img src='https://chillvision.ru/media/media_content_poster/"+c+"/"+l+".SW_200H_260CF_1.jpg'></a><h3><a href='/Soap/"+c+"'>"+n+"</a></h3></div>");
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
    $("body").prepend('<div id="top_barnding"></div><div id="top_barnding_gap"></div>');
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

  #top_barnding {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: #272727;
    background-image: url('/media/landing/horror-404-branding.jpg');
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: center top;
  }

  #top_barnding_gap {
    width: 100%;
    height: calc(100vw / 5);
  }
</style><?php }
}
