<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">Заказ № {$order->id}</div>
    <div class="{$controller->MC}HistoryOrder">        
        <table>
            <thead>
                <tr>
                    <th>ПП</th>
                    <th>Артикул</th>
                    <th>Наименование</th>
                    <th class="{$controller->MC}OrderHeaderAlignRight">Цена</th>
                    <th class="{$controller->MC}OrderHeaderAlignCenter">К-во</th>
                    <th class="{$controller->MC}OrderHeaderAlignRight">Сумма</th>
                </tr>
            </thead>
            <tbody>       
                {assign var="acounter" value=0}
                {foreach $order as $item}                    
                    {assign var="acounter" value=$acounter+1}
                    {include "./history_order_item.tpl"}
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Всего</td>
                    <td colspan="2" class="{$controller->MC}OrderInfoAmountCell">{$order->amount|format_float}
                </tr>
            </tfoot>
        </table>    
        <div class="{$controller->MC}OrderInfoSummary">    
            <div class="{$controller->MC}OrderInfoSummaryBlock">    
                <div class="{$controller->MC}OrderInfoSummaryBlockHeader">Покупатель</div>    
                <div class="{$controller->MC}OrderInfoSummaryBlockContent">
                    <div class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRow">
                        <span class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRowLabel">Имя:</span>
                        <span class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRowValue">{$order->user_name}</span>
                    </div>
                    <div class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRow">
                        <span class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRowLabel">Email:</span>
                        <span class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRowValue">{$order->user_email}</span>
                    </div>
                    <div class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRow">
                        <span class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRowLabel">Телефон:</span>
                        <span class="{$controller->MC}OrderInfoSummaryBlockContentCustomerRowValue">{$order->user_phone}</span>
                    </div>
                </div>
            </div>
            <div class="{$controller->MC}OrderInfoSummaryBlock">    
                <div class="{$controller->MC}OrderInfoSummaryBlockHeader">Доставка</div>    
                <div class="{$controller->MC}OrderInfoSummaryBlockContent">{$order->delivery}</div>
            </div>
            <div class="{$controller->MC}OrderInfoSummaryBlock">    
                <div class="{$controller->MC}OrderInfoSummaryBlockHeader">Комментарии и пожелания</div>    
                <div class="{$controller->MC}OrderInfoSummaryBlockContent">{$order->comment}</div>
            </div>
        </div>
    </div>    
</div>
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var key = '{/literal}{$controller->MC}{literal}';
                var handle = jQuery(['.', key, 'History'].join(''));
            });
        })();
    {/literal}
</script>