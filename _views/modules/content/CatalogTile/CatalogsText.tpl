{$OUT->add_css("/assets/css/front/tileblock/tileblock.catalogstext.css", 0)|void}
<div class="TileBlock TileBlock-{$this->tile->template} TileBlock-custom-{$this->tile->css_class}">
    <div class="TileBlockInner">
        {if $this->tile->show_header}
            <div class="TileBlockHeader">{$this->tile->title}</div>
        {/if}
        <div class="TileBlockContent">
            {foreach $this->items as $catalog}
                {if ($catalog->visible || $this->tile->ignore_catalog_visibility)}
                    <div class="TileBlockCatalogTextItem">
                        <a href="/catalog/{$catalog->alias}">{$catalog->name}</a>
                    </div>                    
                {/if}
            {/foreach}
        </div>
    </div>
</div>