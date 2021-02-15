{if $menu_node->visible}
<div class="DefaultMenuItem">
    <a href="{$menu_node->url}">{$menu_node->name}</a>
    {if $menu_node->has_childs}
        <div class="DefaultMenuItemChildList">
            {foreach from=$menu_node->childs item='menu_node'}
                {include "{$menu_item_template}"}
            {/foreach}
        </div>
    {/if}
</div>
{/if}