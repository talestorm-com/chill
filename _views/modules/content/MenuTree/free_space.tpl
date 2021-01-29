<div class="FreeSpaceMenuWrapper">
    <div class="FreeSpaceMenuBody">
        {foreach $menu_nodes as $menu_node}            
            {if $menu_node->visible && $menu_node->url_ok}
                <div class="FreeSpaceMenuItem">
                    <a href="{$menu_node->url}">{$menu_node->name}</a>                    
                </div> 
            {/if}
        {/foreach}
    </div>
</div>