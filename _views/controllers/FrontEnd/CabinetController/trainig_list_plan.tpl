<div class="{$controller->MC}_training_list_page">
    <div class="{$controller->MC}_training_list_header">План тренировок</div>
    <div class="{$controller->MC}_training_list_body">{include './training_list_table.tpl'}</div>
    {if $paginator}
    <div class="CabinetPaginator">    
        <ul>
            {foreach $paginator as $pi}
                {if $pi}
                    <li class="{if $pi.current}PaginatorElementCurrent{/if}"><a href="/Cabinet/MyPlan{if $pi.page>0}?page={$pi.page}{/if}">{$pi.value}</a></li>
                    {else}
                    <li><a href="#" style="pointer-events: none">...</a></li>
                    {/if}
                {/foreach}
        </ul>
    </div>
{/if}    
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var key = '{/literal}{$controller->MC}{literal}';
                var handle = jQuery(['.', key, '_training_list_body'].join(''));
                handle.on('click', 'table>tbody>tr', function (e) {
                    var id = U.IntMoreOr(jQuery(this).data('id'), 0, null);
                    if (id) {
                        window.location.href = ("/Cabinet/MyTraining?id=" + id);
                    }
                });
            });
        })();
    {/literal}
</script>
</div>