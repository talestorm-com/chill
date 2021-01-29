<tr data-id="{$order.id}">
    <td class="{$controller->MC}HistoryCellId">{$order.id}</td>
    <td class="{$controller->MC}HistoryCellCreatd">{$order.created}</td>
    <td class="{$controller->MC}HistoryCellInfo">
        {if $order.reserve==0}
            Заказ в интернет-магазине
        {elseif $order.reserve==1}
            Резерв в магазине "{$order.shop_name}"
        {elseif $order.reserve=2}
            Предзаказ            
        {/if}        
    </td>
    <td class="{$controller->MC}HistoryCellSatus">
        {if $order.status==0}Новый{elseif $order.status==1}В работе{elseif $order.status==2}На доставке{elseif $order.status==3}Завершен{elseif $order.status==4}Отменен{else}{$order.status}{/if}        
    </td>
    <td class="{$controller->MC}HistoryCellPos">{$order.position}</td>
    <td class="{$controller->MC}HistoryCellAmount">{$order.amount|format_float}</td>
</tr>