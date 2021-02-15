<div class="ProductSmallTileItemOuter ProductTileItem" data-id="{$product->id}">
    {if {product_has_discount product=$product} }
        <div class="ProductSmallTileRedLabelContainer"><div class="ProductSmallTileRedLabel">{product_discount product=$product}%</div></div>
    {/if}
    <div class="ProductSmallTileItemInner {if {product_has_discount product=$product}||{product_has_price product=$product old=true} }ProductSmallTileItemInnerRedPrice{/if}">                                    
        <a href="/product/{$product->alias}">
            <div class="ProductSmallTileImage">
                {if $product->default_image}
                    <img src="/media/product/{$product->id}/{$product->default_image}.{$image_specification}.jpg" alt="" />
                {else}
                    <img src="/media/fallback/1/product.{$image_specification}.jpg" alt="" />
                {/if}
            </div>
            <div class="ProductSmallTileColors">
                {if $product->colors->length && count($product->colors)>1}
                    {foreach $product->colors as $color}
                        <div class="ProductSmallTileColor">
                            <div class="ProductSmallTileColordisplay" style="background:{$color->html_color}" title="{$color->name}"></div>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <div class="ProductSmallTileProductName">{$product->name}</div>
            <div class="ProductSmallTilePriceBlock">
                <div class="ProductSmallTilePriceContainer">
                    {if {product_has_price product=$product}}
                        {if {product_has_price product=$product old=true}}
                            <div class="ProductSmallTilePriceRow ProductSmallTilePriceOld"><div class="ProductSmallTilePriceValue">{product_price product=$product old=true}</div></div>
                            {/if}
                        <div class="ProductSmallTilePriceRow"><div class="ProductSmallTilePriceValue">{product_price product=$product}</div> <span class="ProductSmallTilePriceValute">руб</span></div>
                    {else}
                        <div class="ProductSmallTilePriceRow">по запросу</div>
                    {/if}
                </div>
                <div class="ProductSmallTileButton" data-product-id="{$product->id}" data-color-count="{count($product->colors)}" data-size-count="{count($product->sizes)}" data-command="add_product_basket">
                    <svg><use xlink:href="#global_cart_filled" /></svg>
                </div>                                                
            </div>
        </a>
    </div>
</div>