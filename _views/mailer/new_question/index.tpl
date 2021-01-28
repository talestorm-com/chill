{include './../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый Администратор!<br>
Вам поступил новый вопрос.
<br>
<br>
<b>Пользователь:</b>{$request.name}<br>
<b>email:</b>{$request.email}<br>
<b>Сообщение:</b>
<div style="background:whitesmoke;padding:1.25em;border:1px solid silver;">{$request.message}</div>
<br><br>
{include './../mailer_common/footer.tpl'}