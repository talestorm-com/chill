<?php
/* Smarty version 3.1.33, created on 2020-08-10 15:34:01
  from '/var/VHOSTS/site/_layouts/front/balans.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f313eb9516e28_92231408',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e6ddaa518987cf9323c0a024bd8eac63741f2ccd' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/balans.tpl',
      1 => 1597062835,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f313eb9516e28_92231408 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
>
$(document).ready(function(){
$("#search_out_a").click(function(){
    $("#header_search").fadeIn(300);
});
$("#header_search_bg").click(function(){
    $("#header_search").fadeOut(300);
});
var nba = $("#bala").data("auth");
        if (typeof nba != 'undefined' && nba != ''){
        var a = $("#bala").html();
        var ax = Math.round(a);
        var b = a / 6;
        var bx = Math.round(b);
        function declOfNum(n, text_forms) {  
            n = Math.abs(n) % 100; var n1 = n % 10;
            if (n > 10 && n < 20) { return text_forms[2]; }
            if (n1 > 1 && n1 < 5) { return text_forms[1]; }
            if (n1 == 1) { return text_forms[0]; }
            return text_forms[2];
        }
        var nx = declOfNum(bx, ['серия', 'серии', 'серий']);
            if (a < 6) {
                $("#balans_in").html('<a href="/Profile"><img src="/assets/chill/images/00_wallet.svg"> Пополнить</a>')
            } else {
                $("#balans_in").html('<a href="/Profile"><img src="/assets/chill/images/00_wallet.svg"> <span id="balans_in_out_ser">' + bx + '</span> '+nx+'</a>')
            }
        }else{
            $("#balans_in").html('<a id="enter_aa">Вход</a>');
            $("#enter_aa").click(function(){
            return run_authorization_sequence();
            })
        }
        });
(function () {
        window.Eve = window.Eve ||{};
        window.Eve.EFO = window.Eve.EFO ||{};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            window.Eve.EFO.Events.GEM().on("LOGIN_SUCCESS", window, function () {
                location.reload();
            });
        });
    })();
        <?php echo '</script'; ?>
>
<?php }
}
