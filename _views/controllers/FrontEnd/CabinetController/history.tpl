<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">История заказов</div>
    <div class="{$controller->MC}History">        
        <table>
            <thead>
                <tr>
                    <th>№№</th>
                    <th>Дата</th>
                    <th>Заказ</th>
                    <th>Статус</th>
                    <th>Поз</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>                
                {foreach $orders as $order}                    
                    {include "./history_order.tpl"}
                {/foreach}
            </tbody>
        </table>
        {if $paginator}
            <div class="CabinetPaginator">    
                <ul>
                    {foreach $paginator as $pi}
                        {if $pi}
                            <li class="{if $pi.current}PaginatorElementCurrent{/if}"><a href="/Cabinet/History{if $pi.page>0}?p={$pi.page}{/if}">{$pi.value}</a></li>
                            {else}
                            <li><a href="#" style="pointer-events: none">...</a></li>
                            {/if}
                        {/foreach}
                </ul>
            </div>
        {/if}
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
                handle.on('click','table>tbody>tr',function(e){
                   var id = U.IntMoreOr(jQuery(this).data('id'),0,null);
                   if(id){
                       window.location.href=("/Cabinet/Order?o="+id);
                   }
                });
            });
        })();
    {/literal}
</script>