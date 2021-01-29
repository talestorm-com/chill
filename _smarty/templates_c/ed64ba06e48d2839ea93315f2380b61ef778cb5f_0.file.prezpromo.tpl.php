<?php
/* Smarty version 3.1.33, created on 2020-12-17 20:17:06
  from '/var/VHOSTS/site/_layouts/front/prezpromo.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fdb92920b63e1_53909171',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ed64ba06e48d2839ea93315f2380b61ef778cb5f' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/prezpromo.tpl',
      1 => 1608225412,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fdb92920b63e1_53909171 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="prez_promo">
<div id="prez_promo_in">
<div id="treu_11" class="treusy"></div>
<div id="treu_12" class="treusy"></div>
    <div id="prez_promo_1" class="prez_what_page">
        <div class="logo_in_promo">
            <img src="/assets/chill/images/promologo.png">
        </div>
        <div id="promo_prez_text">
        	<div class="head3">Регистрируйся прямо<br>
				сейчас</div>
			<p></p>
		</div>
		<div id="promo_prez_bottom">
			<div id="promo_prez_button">
				Войти
			</div>
		</div>
    </div>
</div>
</div>
<div id="prez_promo_nach" style="display:none;">
<div id="prez_promo_nach_in">
<div id="treu_21" class="treusy"></div>
<div id="treu_22" class="treusy"></div>
<div id="prez_promo_2">
<div class="logo_in_promo">
            <img src="/assets/chill/images/promologo.png">
        </div>
        <div id="promo_prez_text_2">
        	<div class="head3">Промокод активирован!</div>
			<p></p>
		</div>
		<form method="POST" action="/Cabinet/promo_promo_promo" id="promoform_out" onsubmit="return false">
                   
                                <input type="text" id="cabinet_promo_2" value="chillpromo25" name="promocode" placeholder="" />
                                <input type='hidden' id='promo_token_2' name='promo_token_2' value='<?php echo $_smarty_tpl->tpl_vars['controller']->value->mk_csrf('promo');?>
' />
		<div id="promo_prez_bottom_2">
			<button id="promo_prez_button_2">
				+ 6 серий
			</button>
		</div>
        </form>
    </div>
</div>
</div>

<?php echo '<script'; ?>
>
var hash = window.location.hash;
var hashTag = "#promo";
var hashSave = localStorage.getItem("promo");
var hashSave2 = localStorage.getItem("promo2");

if(hash === hashTag && hashSave != "off"){
    $("#prez_promo").fadeIn(0);
    $("#noty_body").fadeOut(0);
    localStorage.setItem("cookie","true");
    $("#prez_what").fadeOut(0);
    localStorage.setItem("prez","off");
    //$("#promo_prez_button").click(function(){
    	$("#prez_promo").fadeOut(300);
    	$("#chill_signup_block").fadeIn(0);
    	$("#close_login_signup_signup").fadeOut(0);
    	$("#close_login_signup").fadeOut(0);
    	localStorage.setItem("promo","off")
    //});
}
if(hash === hashTag && hashSave === "off" && hashSave2 != "off"){
$("#noty_body").fadeOut(0);
    localStorage.setItem("cookie","true");
$("#prez_promo_nach").fadeIn(0);
    $("#prez_promo").fadeOut(0);
    $("#prez_what").fadeOut(0);
    
}
if(hashSave2 === "off"){
$("#noty_body").fadeOut(0);
    localStorage.setItem("cookie","true");
    $("#prez_promo_nach").fadeOut(0);
    $("#prez_promo").fadeOut(0);
}
$(document).ready(function(){
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
$('#promo_prez_button_2').click(function (e) {
$("#prez_promo_nach").fadeOut(300);
        localStorage.setItem("promo2","off");
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    var promo_value = U.NEString(jQuery('#cabinet_promo_2').val(), null);
                    var csrf = U.NEString(jQuery('#promo_token_2').val(), null);
                    try {
                        promo_value ? 0 : U.Error("Введите промокод");
                        promo_value.length > 100 ? U.Error('Такого длинного промокода не бывает!') : 0;
                        jQuery.post('/Cabinet/API', {action: 'apply_promo', value: promo_value, token: csrf})
                                .done(function (d) {
                                    if (U.isObject(d)) {
                                        if (d.status === 'ok') {
                                            jQuery('#balans').val(d.money);
                                            var b = d.money / 6;
                                            var bx = Math.round(b);
                                            function declOfNum(n, text_forms) {
                                                n = Math.abs(n) % 100;
                                                var n1 = n % 10;
                                                if (n > 10 && n < 20) {
                                                    return text_forms[2];
                                                }
                                                if (n1 > 1 && n1 < 5) {
                                                    return text_forms[1];
                                                }
                                                if (n1 == 1) {
                                                    return text_forms[0];
                                                }
                                                return text_forms[2];
                                            }
                                            var nx = declOfNum(bx, ['серия', 'серии', 'серий']);

                                            $("#balans_in").html('<a href="/Profile"><img src="/assets/chill/images/00_wallet.svg"> <span id="balans_in_out_ser">' + bx + '</span> ' + nx + '</a>');

                                            try {
                                                window.dataLayer = window.dataLayer || [];
                                                window.dataLayer.push({event: 'custom_event', event_category: 'balance', event_action: 'promocode', event_label: promo_value});

                                            } catch (e) {

                                            }
                                            //alert("Промокод применен!\n Ваш баланс пополнен.");
                                            window.Eve.EFO.Alert().set_text("Ваш баланс пополнен.").set_title("Промокод применен").set_close_btn(true)
                                                    .set_style("green").set_timeout(3000).set_callback(window, function () {
                                            }).show();
                                            return;
                                            
                                        }
                                        if (d.status === 'error') {
                                            if (d.error_info.message === 'not found' || d.error_info.message === 'not_found') {
                                                //alert("Промокод не найден!\nПроверьте правильность написания!");
                                                window.Eve.EFO.Alert().set_text("Проверьте правильность написания!").set_title("Промокод не найден!").set_close_btn(true)
                                                        .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                }).show();
                                                return;
                                            } else if (d.error_info.message === 'alredy_used') {
                                                //alert("Вы уже использовали этот промокод!\nПромокод не может быть использован повторно.");
                                                window.Eve.EFO.Alert().set_text("Промокод не может быть использован повторно.").set_title("Вы уже использовали этот промокод!").set_close_btn(true)
                                                        .set_style("red").set_timeout(3000).set_callback(window, function () {
                                                }).show();
                                                return;

                                            }
                                            //alert(d.error_info.message);
                                            window.Eve.EFO.Alert().set_text(d.error_info.message).set_title("Ошибка").set_close_btn(true)
                                                    .set_style("red").set_timeout(3000).set_callback(window, function () {
                                            }).show();
                                            return;
                                        }
                                    }
                                    //alert("Ошибка связи с сервером!");
                                    window.Eve.EFO.Alert().set_text("Ошибка связи с сервером!").set_title("Ошибка").set_close_btn(true)
                                            .set_style("red").set_timeout(3000).set_callback(window, function () {
                                    }).show();
                                })
                                .fail(function () {
                                    //alert("Ошибка сети!");
                                    window.Eve.EFO.Alert().set_text("Ошибка сети!").set_title("Ошибка").set_close_btn(true)
                                            .set_style("red").set_timeout(3000).set_callback(window, function () {
                                    }).show();
                                });
                    } catch (e) {
                        //alert(e.message);
                        window.Eve.EFO.Alert().set_text(e.message).set_title("Ошибка").set_close_btn(true)
                                .set_style("red").set_timeout(3000).set_callback(window, function () {
                        }).show();
                    }
                    
                });
                });
                });
                $(document).ready(function(){
                var x = location.hash;
                var b = $("#logaaa").text();
                console.log(x);
                    if (x === '#login_open' && b != '1'){
                        $("#login_cover").fadeIn(0);
                    }
                });
<?php echo '</script'; ?>
>
<?php }
}
