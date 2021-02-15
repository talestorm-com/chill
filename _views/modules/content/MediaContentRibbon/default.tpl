{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module.css',0)|void}
{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0)|void}
<div id="lenta">
    <div class="container">
        <div class="row">
            <div class="col s12 l10 offset-l1">   
                <div class="chill-lenta-content">
                    <div class="chill-lenta-content-inner">                        
                        {assign var='index' value=-1}
                        {foreach from=$this->items item='item'}                            
                            {assign var='index' value=$index+1}
                            {if $index>5}
                                {assign var='index' value=0}
                            {/if}
                            {if ($index>=0 && $index<=3)}
                                {assign var='image_url' value="/media/{$item->get_image_url()}.SW_526CF_1PR_sq.jpg"}
                                {assign var='item_class' value="item_sq"}
                            {else}
                                {assign var='image_url' value="/media/{$item->get_image_url()}.SW_1088CF_1PR_hposter.jpg"}
                                {assign var='item_class' value="item_hz"}
                            {/if}      
                            <div class="chill-lenta-item chill-lenta-item-{$item_class}">
                                {if $item->has_tag}
                                    <div class="lenta_hash">
                                        <a href="/search/by_tag/{$item->tag_id}">{$item->tag_name}</a>
                                    </div>
                                {/if}
                                {assign var='is_trailer' value=$item->content_type === 'ctTRAILER'}
                                {if $item->content_type === 'ctTRAILER'}
                                    {include "./item_{$item->trailed_content_type}.tpl"}
                                {else}
                                    {include "./item_{$item->content_type}.tpl"}
                                {/if}
                            </div>
                        {/foreach}
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<script>
$(".gif_load").each(function() {
            $(this).click(function() {
            $(".gif_sign").fadeIn(0);
            $(".gif_img").fadeIn(0);
            $(".gif_gif").fadeOut(0);
                $(this).find(".gif_sign").fadeOut(0);
                $(this).find(".gif_img").fadeOut(0);
                var a = $(this).find(".gif_gif").data("src");
                $(this).find(".gif_gif").attr("src",a);
                $(this).find(".gif_gif").fadeIn(0);
            });
        });
</script> 

<!-- <script>
$(".gif_load").each(function() {
            $(this).hover(function() {
            $(this).find(".gif_sign").fadeOut(0);
            $(this).find(".gif_img").fadeOut(0);
            var a = $(this).find(".gif_gif").data("src");
                $(this).find(".gif_gif").attr("src",a);
                $(this).find(".gif_gif").fadeIn(0);
            }, function() {
                $(this).find(".gif_sign").fadeIn(0);
            $(this).find(".gif_img").fadeIn(0);
            $(this).find(".gif_gif").fadeOut(0);
            });
        });
</script> -->
