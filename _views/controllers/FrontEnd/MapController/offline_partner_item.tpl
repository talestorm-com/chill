<div class="{$controller->MC}offline_shop_item" data-town="{$shop->town_key}">
    <div class="{$controller->MC}offline_shop_item_content">
        <div class="{$controller->MC}offline_shop_itemTown">{$shop->town}</div>
        <div class="{$controller->MC}offline_shop_itemName">{$shop->name}</div>
        <div class="{$controller->MC}offline_shop_itemAddress">{$shop->address|pipe_replace}</div>
        {if $shop->phone || $shop->phone_alter}
        <div class="{$controller->MC}offline_shop_itemPhone">Телефон: {if $shop->phone}<a href="tel:{$shop->phone|phone_as_link}">{$shop->phone}</a>{/if} {if $shop->phone_alter}<a href="tel:{$shop->phone_alter|phone_as_link}">{$shop->phone_alter}</a>{/if}</div>
        {/if}
        <div class="{$controller->MC}offline_shop_itemWorks">Часы работы: {$shop->works}</div>
        <div class="{$controller->MC}offline_shop_itemOnMap"><a href="#" data-command="on_map" data-id="{$shop->id}">На карте</a></div>
    </div>
</div>