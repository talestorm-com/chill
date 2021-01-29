<div class="{$controller->MC}_price_item">
    <div class="{$controller->MC}_price_item_inner">
        <div class="{$controller->MC}_price_item_image">
            {if $package.default_image}
                <img src="/media/package/{$package.id}/{$package.default_image}.SW_250H_250CF_1.jpg" />
            {else}
                <img src="/media/fallback/1/package.SW_250H_250CF_1.jpg" />
            {/if}
        </div>
        
        <div class="{$controller->MC}_price_item_texts">
            <div class="{$controller->MC}_price_item_text_name">{$package.name}</div>
            <div class="{$controller->MC}_price_item_priceblock">
                <div class="{$controller->MC}_price_item_cost">{$package.price|number_format:2:'.':' '} RUR</div>
                <div class="{$controller->MC}_price_item_cost_rigth">
                    <div class="{$controller->MC}_price_item_cost_rigth_row">{$package.days} дней</div>
                    <div class="{$controller->MC}_price_item_cost_rigth_row">{$package.usages} занятий</div>
                </div>
            </div>
        </div>
        <div class="{$controller->MC}_price_item_bp">
            <a class="{$controller->MC}_price_button {if !$canbye}{$controller->MC}_price_button_disabled{/if}" {if $canbye}href="/Cabinet/create_order?id={$package.id}"{else}href="#"{/if}>Купить</a>
        </div>
    </div>
</div>
