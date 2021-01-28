{literal}
<script>
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
        </script>
{/literal}