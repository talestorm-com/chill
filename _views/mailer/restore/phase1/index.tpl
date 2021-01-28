{include './../../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый {$user_info->name} {$user_info->eldername}!
<br><br>
Для Вашего личного кабинета на сайте {$host} была запрошена процедура сброса пароля.<br>
<br>
<br>
{assign var='link' value="{if $https}https://{else}http://{/if}{$host}/Auth/restore?user={$user_info->login}&validate={$user_info->generate_sequrity_hash()}"}
Для завершения сброса пройдите по ссылке:<a href="{$link}">{$link}</a>
<br>
<br>
Если Вы не запрашивали сброс пароля - <b>Ничего делать не надо</b>.
<br>
<br>
<br>

{include './../../mailer_common/footer.tpl'}