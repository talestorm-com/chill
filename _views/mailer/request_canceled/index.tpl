{include './../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый Админинстратор!
<br>
Пользователь отменил заявку:<br>
<b>№№</b>: {$request.id}<br>
<b>Сфера</b>: {$request.profile_name}<br>
<b>Инвойс</b>: {$request.position_name}<br>
<b>Сумма</b>: {$request.position_cost}<br>
<b>Контрагент</b>: {$request.company_name}<br>
<br><br>
<br>
{include './../mailer_common/footer.tpl'}