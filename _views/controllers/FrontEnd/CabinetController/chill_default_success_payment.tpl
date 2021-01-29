{$OUT->add_css('/assets/chill/css/lk_eve.css',0)|void}
<div class="cabinet-message">
    <div>
        Платеж проведен успешно. Номер транзакции {$order_id}<br>
        Через несколько минут средства поступят на Ваш виртуальный счет.<br>
        Спасибо!
    </div>
    <div id="return_to_ser"><a>Вернуться к сериалу</a></div>
    <div id="return_to_ser_il">- или -</div>
    <div>
    <a href="/Profile">Перейти в профиль</a>
    </div>
</div>

<script>
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


</script>
