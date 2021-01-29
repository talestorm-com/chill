<div class="{$controller->MC}offline_shop_item">
    <div class="{$controller->MC}offline_shop_item_content">
        <div class="{$controller->MC}offline_shop_itemName">{$shop->name}</div>
        <div class="{$controller->MC}offline_shop_itemAddress">{$shop->address|pipe_replace}</div>
        {if $shop->phone || $shop->phone_alter}
        <div class="{$controller->MC}offline_shop_itemPhone">Телефон: {if {$shop->phone}}<a href="tel:{$shop->phone|phone_as_link}">{$shop->phone}</a>{/if} {if {$shop->phone_alter}}<a href="tel:{$shop->phone_alter|phone_as_link}">{$shop->phone_alter}</a>{/if}</div>
        {/if}
        <div class="{$controller->MC}offline_shop_itemWorks">Часы работы: {$shop->works}</div>
    </div>
</div>