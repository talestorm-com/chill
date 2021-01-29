{$OUT->add_css("/assets/css/front/tileblock/tileblock.default.css", 0)|void}
<div class="TileBlock TileBlock-{$this->tile->template} TileBlock-custom-{$this->tile->css_class}">
    <div class="TileBlockInner">
        {if $this->tile->show_header}
            <div class="TileBlockHeader">{$this->tile->title}</div>
        {/if}
        <div class="TileBlockContent">
            {foreach $this->items as $catalog}
                {if ($catalog->visible || $this->tile->ignore_catalog_visibility) && $catalog->has_image}
                    <div class="TileBlockItemOuter">
                        <div class="TileBlockItemInner">
                            <div class="TileBlockItemHeader"><a href="/catalog/{$catalog->alias}">{$catalog->display_name}</a></div>
                            <div class="TileBlockItemImage">
                                <a href="/catalog/{$catalog->alias}">
                                    <img src="/media/{if $catalog->image_id}catalog_tile/{$this->tile->id}/{$catalog->image_id}{else}product_group/{$catalog->id}/{$catalog->default_image}{/if}.SW_900H_1350{if !$this->tile->crop}C_0{/if}{if $this->tile->crop_fill}CF_1{/if}{if $this->tile->background}B_{$this->tile->background_url}{/if}.jpg" alt="" />
                                </a>                    
                            </div>
                        </div>
                    </div>
                {/if}
            {/foreach}
        </div>
    </div>
</div>