{$OUT->add_css("/assets/css/front/map/map.default.css", 0)|void}
<div class="{$controller->MC}Container">
    <div class="{$controller->MC}ContainerInner">
        <div class="{$controller->MC}Header">
            <div class="{$controller->MC}HeaderItem {if $map_controller_mode eq "offline"}{$controller->MC}HeaderItemactive{/if}">
                <div class="{$controller->MC}HeaderItemInnerLink">Фирменные магазины LARRO</div>
                <a href="/Map">Фирменные магазины LARRO</a>
            </div>
            <div class="{$controller->MC}HeaderItem {if $map_controller_mode eq "partners"}{$controller->MC}HeaderItemactive{/if}">
                <div class="{$controller->MC}HeaderItemInnerLink">Магазины-партнеры</div>
                <a href="/Map/Partners">Магазины-партнеры</a>
            </div>
        </div>
    </div>
    <div class="{$controller->MC}ContainerContent">
        {include "./{$map_controller_mode}.tpl"}
    </div>
</div>