<div class="FrontLayoutLeftSideMenu">
    {foreach $menu_nodes as $menu_node}
        {if $menu_node->visible}
            <div class="FrontLayoutLeftSideMenuItem">
                {if $menu_node->is_catalog_tree}
                    <div class="FrontLayoutLeftSideMenuItemHeader">{$menu_node->name}</div>
                    <div class="FrontLayoutLeftSideMenuItemBody">    
                        {foreach $menu_node->get_catalog()->childs as $node}
                            {if $node->visible}
                                {if $node->has_visible_childs}
                                    <div class="FrontLayoutLeftSideMenuItemL2 LeftSideMenuItemL2Childable LeftSideMenuItemL2opened">
                                        <div class="LeftSideMenuItemL2Header">{$node->name}
                                            <div class="LeftSideMenuItemL2HeaderOpenIndicator">
                                                <svg><use xlink:href="#global_menu_arrow" /></svg>                                                    
                                            </div>
                                        </div>
                                        <div class="LeftSideMenuItemL2BodyItems">
                                            {foreach $node->childs as $child_node}
                                                <div class="FrontLayoutLeftSideMenuItemL3">
                                                    <a href="/catalog/{$child_node->alias}">{$child_node->name}</a>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                {else}
                                    <div class="FrontLayoutLeftSideMenuItemL2">
                                        <a href="/catalog/{$node->alias}">{$node->name}</a>
                                    </div>
                                {/if}
                            {/if}
                        {/foreach}
                    </div>
                {else}        
                    <div class="FrontLayoutLeftSideMenuItemHeader">
                        {if $menu_node->url_ok}<a href="{$menu_node->url}">{/if}{$menu_node->name}{if $menu_node->url_ok}</a>{/if}
                    </div>
                    {if $menu_node->has_childs}
                        <div class="FrontLayoutLEftSideMenuItemBody">                            
                            {foreach $menu_node->childs as $node}
                                {if $node->visible && $node->url_ok}
                                    <div class="FrontLayoutLeftSideMenuItemL2">
                                        <a href="{$node->url}">{$node->name}</a>
                                    </div>
                                {/if}
                            {/foreach}                            
                        </div>
                    {/if}
                {/if}
            </div> 
        {/if}
    {/foreach}
</div>
<script>{literal}
    (function () {
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(ready);

        function ready() {
            var U = window.Eve.EFO.U;
            jQuery('.LeftSideMenuItemL2Header').on('click', function (e) {
                e.stopPropagation();
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                jQuery(this).parent().toggleClass('LeftSideMenuItemL2opened');
            });
        }
    })();
</script>{/literal}