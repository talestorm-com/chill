{include './../mailer_common/header.tpl'}
<h3>{$subject}</h3>
Уважаемый менеджер!<br>
Поступил новый запрос на размещение контента в кинотеатре "Chill".
<br>
Информация:<br>
<b>Пользователь:</b>{$contact}<br>
<b>email:</b>{$email}<br>
<b>Наименование:</b>{$common_name}<br>
<b>Наименование (en):</b>{$name}<br>
<b>Год выхода:</b>{$year}<br>
<b>Режиссер:</b>{$director}<br>
<b>Продюсер:</b>{$producer}<br>
<b>Актеры:</b>{$actor}<br>
<b>Аннотация:</b>
<div style="font-family:monospace;background:whitesmoke;padding:1em">{$annotation}</div>
{include './../mailer_common/footer.tpl'}