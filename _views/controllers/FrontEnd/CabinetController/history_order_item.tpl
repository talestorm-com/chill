<tr>
    <td class="{$controller->MC}HistoryOrderOrderItemCellPP">{$acounter}</td>
    <td class="{$controller->MC}HistoryOrderOrderItemCellArticle">{$item->article}</td>
    <td class="{$controller->MC}HistoryOrderOrderItemCellName">{$item->name}{if $item->color_name} {$item->color_name}{/if}
        {if $item->size}<br>Размер: {$item->size}{/if}
    </td>
    <td class="{$controller->MC}HistoryOrderOrderItemCellPrice">{$item->price|format_float}</td>
    <td class="{$controller->MC}HistoryOrderOrderItemCellQty">{$item->qty}</td>
    <td class="{$controller->MC}HistoryOrderOrderItemCellAmount">{$item->amount|format_float}</td>
</tr>