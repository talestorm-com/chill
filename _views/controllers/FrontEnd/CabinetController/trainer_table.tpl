<table>
    <thead>
        <tr>
            <th>№№</th>
            <th>Дата</th>
            <th>Время</th>
            <th>Место</th>
            <th>Курсант</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>                
        {foreach $items as $item}                    
            <tr data-id="{$item.id}">
                <td class="{$controller->MC}HistoryCellId">{$item.id}</td>
                <td class="{$controller->MC}HistoryCellDatum">{$item.date_fmt}</td>
                <td class="{$controller->MC}HistoryCellTime">{$item.time}</td>
                <td class="{$controller->MC}HistoryCellPlace">{$item.place_name}</td>
                <td class="{$controller->MC}HistoryCellTrainer">{$item.client_name}</td>
                <td class="{$controller->MC}HistoryCellSatus {$controller->MC}HistoryCellSatus_{$item.state}">
                {if $item.state==0}Ожидает{elseif $item.state==1}Принят{elseif $item.state==2}Отменен{else}{$item.state}{/if}        
            </td>    
        </tr>
    {/foreach}
</tbody>
</table>
