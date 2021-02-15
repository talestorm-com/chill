{include './../../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый {$user_info->name} {$user_info->eldername}!
<br><br>
Пароль от Вашего личного кабинета на сайте {$host} был сброшен.<br>
Ваш новый пароль:<b style="font-size:1.1em">{$new_password}</b><br>
<br>
Вы можете изменить пароль на более удобный в Вашем <a href="{if $https}https://{else}http://{/if}{$host}/Profile">личном кабинете</a>
<br>
<br>
<br>

{include './../../mailer_common/footer.tpl'}