{include './../../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый {$user_info->name} {$user_info->eldername}!
<br>
Для Вашего удобства Вам создана учетная запись.<br>
Для входа в <a href="{if $https}https://{else}http://{/if}{$host}/Cabinet">личный кабинет</a> Вам потребуются следующие данные:<br>
<b>Логин/email:</b> {$user_info->login}<br>
<b>Пароль:</b> {$password}<br>
<br>
<br>

<br>
<br>
{include './../../mailer_common/footer.tpl'}