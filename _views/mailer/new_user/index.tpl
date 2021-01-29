{include './../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый Админинстратор!
<br>
Новый пользователь зарегистрировался в системе:<br>
<b>Имя</b>: {$user->name}<br>
<b>Логин</b>: <a href="mailto://{$user->login}">{$user->login}</a><br>
<br><br>
<br>
{include './../mailer_common/footer.tpl'}