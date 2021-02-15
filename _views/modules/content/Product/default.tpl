{$OUT->add_script("/assets/js/front/ScrollFix.js", 0, true)|void}
{$OUT->meta->set_metadata($this)|void}
<div class="ProductPageOuter ProductPageOuterCustom{$this->product->properties->get_filtered('css_class',['Strip','Trim','NEString','DefaultEmptyString'])}">
    {assign var="product_block_uuid" value="a{$OUT->get_euid('product_block')}"}
    <div class="ProductPageSideblock ProductPageLeft " id="menu_{$product_block_uuid}">            
        {menu alias="left_side_menu" template="left_side"}
    </div>        
    <div class="ProdycrPageSideblock ProductPageRight " id="block_{$product_block_uuid}">        
        <div class="ProductPageTopBlock">
            <div class="ProductPageBreadcrumbs">
                {foreach $this->breadcrumbs as $breadcrumb}
                    <div class="frontLayoutBreadcrumbBlock">
                        {if $breadcrumb->has_link}<a href="{$breadcrumb->link}">{/if}{$breadcrumb->text}{if $breadcrumb->has_link}</a>{/if}
                    </div>
                {/foreach}
            </div>            
        </div>
        <div class="ProductPageProduct" id="list_{$product_block_uuid}">    
            {include "./product_default.tpl"}            
        </div> 

    </div>
</div>
{if $this->product->has_cross}
    <div class="ProductCrossBlockWrapper BeforeFooterOffset">
        {include "./product_cross.tpl"}            
    </div>
{/if}
{render_last_products}
<script>{literal}
    (function () {
        var uid = '{/literal}{$product_block_uuid}{literal}';
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            var E = window.Eve, EFO = E.EFO, U = EFO.U;
            //var block = jQuery(["#block_", uid].join(''));            
            //var menu = jQuery(["#menu_", uid].join(''));  
            {/literal}{if !$controller->is_device}{literal}
            window.Eve.scroll_fix_ready = window.Eve.scroll_fix_ready || [];
            window.Eve.scroll_fix_ready.push(function () {                
                window.Eve.scroll_fix(["menu_", uid].join(''), jQuery('.BeforeFooterOffset:first').get(0), jQuery('.FrontLayoutPageHeader').get(0), 0, 50);
                // window.Eve.scroll_fix(["info_", uid].join(''), jQuery('.FrontLayoutPageFooter').get(0), jQuery('.FrontLayoutPageHeader').get(0), 0, 16);
            });
            {/literal}{/if}{literal}
        });
    })();
</script>{/literal}
<div style="display:none!important">{*include "./infographics.svg"*}</div>
