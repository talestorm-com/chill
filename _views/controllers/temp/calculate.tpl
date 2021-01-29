<div style="font-size:2em">Дорогой менеджер!</div>
<b>{$mail_data.name|strip_tags}</b> просит Вас подготовить расчет:<br>
<b>Количество</b>: {$mail_data.qty|strip_tags}<br>
<b>Бюджет:</b>:{$mail_data.money|strip_tags}<br>
<b>Город:</b>:{$mail_data.city|strip_tags}<br>
<b>Пользователь:</b>:{$mail_data.name|strip_tags}<br>
<b>email:</b>:{$mail_data.email|strip_tags}<br>
<b>телефон:</b>:<a href="tel://{$mail_data.phone_raw}">{$mail_data.phone|strip_tags}</a><br>

