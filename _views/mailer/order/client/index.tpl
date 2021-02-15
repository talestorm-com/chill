{include './../../mailer_common/header.tpl'}
<h3>{$subject}</h3>
<b>Уважаемый {$order->user_name}!</b><br>
Состав заказа:<br><br>
<table >
    <thead>
        <tr>
            <th>Арт.</th>
            <th>Наименование</th>
            <th>Цвет</th>
            <th>Размер</th>
            <th>Цена</th>
            <th>К-во</th>
            <th>Стоимость</th>
        </tr>
    <tbody>
        {foreach $order->items as $item}
            <tr>
                <td>{$item->article}</td>
                <td>{$item->name}</td>
                <td>{$item->color_name}</td>
                <td>{$item->size}</td>
                <td class="td-text-right">{$item->price|format_float}</td>
                <td class="td-text-center">{$item->qty}</td>
                <td class="td-text-right">{($item->qty * $item->price)|format_float}</td>                
            </tr>
        {/foreach}
        <tr class="table-total">
            <td colspan="6">Всего:</td>
            <td>{$order->amount|format_float}</td>
        </tr>
    </tbody>
</table>
<br><br>
<b>Даные заказа:</b><br>
Покупатель: <b>{$order->user_name}</b><br>
email: <b><a href="mailto:{$order->user_email}">{$order->user_email}</a></b><br>
Телефон: <b><a href="tel:{$order->user_phone|phone_as_link}">{$order->user_phone}</a></b><br>
Адрес доставки: <b>{$order->delivery}</b><br>
Примечания и пожелания: <b>{$order->comment}</b><br><br><br>
Наш менеджер скоро свяжется с Вами для уточнения деталей и согласования срока доставки<br><br><br>
<br><br><br>
<br><br><br><br><br><br>
{include './../../mailer_common/footer.tpl'}