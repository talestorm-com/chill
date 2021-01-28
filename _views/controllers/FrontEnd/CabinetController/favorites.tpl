<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">Избранное</div>
    <div class="{$controller->MC}Favorites">        
        {if count($products)}
            <div class="{$controller->MC}FavoritesInner">    
                {if $controller->is_device}
                    {assign var="image_specification" value="SW_450H_682CF_1"}
                {else}
                    {assign var="image_specification" value="SW_300H_455CF_1"}
                {/if}
                {foreach $products as $product}
                    <div class="{$controller->MC}FavoriteItem">                    
                        {include "./../../../modules/content/common_templates/product_tile.tpl"}
                        <div class="{$controller->MC}RemoveFavorite" data-command="remove_favorite" data-id="{$product->id}">
                            <svg ><use xlink:href="#global_cross" /></svg>
                        </div>
                    </div>
                {/foreach}
            </div>

            {if $paginator}
                <div class="CabinetPaginator">    
                    <ul>
                        {foreach $paginator as $pi}
                            {if $pi}
                                <li class="{if $pi.current}PaginatorElementCurrent{/if}"><a href="/Cabinet/Favorite{if $pi.page>0}?p={$pi.page}{/if}">{$pi.value}</a></li>
                                {else}
                                <li><a href="#" style="pointer-events: none">...</a></li>
                                {/if}
                            {/foreach}
                    </ul>
                </div>
            {/if}
        {else}
            <div class="CabinetFavoritesListEmpty">
                <div class="CabinetFavoritesListEmptyText">
                    Ваш список избранного пуст
                </div>                
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
                var handle = jQuery(['.', key, 'Favorites'].join(''));
                handle.on('click', '.ProductSmallTileButton', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                });

                handle.on('click', '[data-command=remove_favorite]', function () {
                    var t = jQuery(this);
                    var id = U.IntMoreOr(t.data('id'), 0, null);
                    if (id) {
                        try {
                            window.show_global_loader();
                        } catch (ee) {

                        }
                        jQuery.getJSON("/Auth/API", {action: "remove_favorite", favorite_id: id})
                                .done(function (s) {
                                    if (U.isObject(s)) {
                                        if (s.status === "ok") {
                                            window.location.reload(true);
                                            return;
                                        }

                                    }
                                })
                                .always(function () {
                                    try {
                                        window.hide_global_loader();
                                    } catch (ee) {

                                    }
                                });
                    }
                });
            });
        })();
    {/literal}
</script>