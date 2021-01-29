{include './../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый {$trainer->name} {$trainer->eldername}!<br>
Вам поступил новый запрос на тренировку. Пожалуйста подтвердите его.
<br>
Информация о заказе:<br>
<b>Пользователь:</b>{$client->family} {$client->name} {$client->eldername}<br>
<b>Телефон:</b>{$client->phone}<br>
<b>email:</b>{$client->login}<br>
<b>Место:</b>{$training->place_name}<br>
<b>Дата:</b>{$training->start_moment->format('d.m.Y H:i')}<br>
{assign var="link1" value="{if $https}https://{else}http://{/if}{$host}/Info/confirm_training?id={$training->id}&validate={$training->create_confirm_hash()}"}
{assign var="link2" value="{if $https}https://{else}http://{/if}{$host}/Info/reject_training?id={$training->id}&validate={$training->create_confirm_hash()}"}
<br><br><br>
Чтобы подтвердить тренировку, перейдите по <a href="{$link1}">ссылке</a>
<br><br><br>
Чтобы отменить тренировку, перейдите по другой <a href="{$link2}">ссылке</a>
<br><br>
{include './../mailer_common/footer.tpl'}