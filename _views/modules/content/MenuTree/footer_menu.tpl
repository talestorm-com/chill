<div class="FooterMenuWrapper">
    <div class="FooterMenuBody">
        {foreach $menu_nodes as $menu_node}            
            {if $menu_node->visible}
                <div class="FooterMenuColumn FooterMenuColumnCustom-{$menu_node->css_class}">
                    <div class="FooterMenuCoumnHeader">{$menu_node->name}</div>
                    <div class="FooterMenuColumnBody">
                        {foreach $menu_node->childs as $child}
                            {if $child->visible}
                                {if $child->is_content_block}
                                    {content_block alias=$child->content_block_alias}
                                {else}
                                    <div class="FooterMenuColumnRow FooterMenuColumnRowCustom-{$child->css_class}">
                                        <a href="{$child->url}" title="{$child->name}">{$child->name}</a>
                                    </div>
                                {/if}                                
                            {/if}                        
                        {/foreach}
                    </div>
                </div> 
            {/if}
        {/foreach}
    </div>
</div>