{include './../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый {$user->name}
<br>
Получена Ваша заявка:<br>
<b>№№</b>: {$request.id}<br>
<b>Сфера</b>: {$request.profile_name}<br>
<b>Инвойс</b>: {$request.position_name}<br>
<b>Сумма</b>: {$request.position_cost}<br>
<b>НДС, %</b>: {$request.nds_pc}<br>
<b>НДС, &euro;</b>: {$request.nds_eur}<br>
<b>Общая сумма</b>: {$request.position_total}<br>
<b>Статус</b>: <span style="color:{$request.status_color}}">{$request.status_name}</span><br>
<br><br>
<br>
{include './../mailer_common/footer.tpl'}