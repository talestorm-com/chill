{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module.css',0)|void}
{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0)|void}
<div id="tag_result">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s10">
                            <h1>{$this->tag_name}</h1>
                        </div>
                        <div class="col s2">
                            <div class="right-align">
                                <a href="javascript:history.back()" class="back_back">Назад</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if (count($this->gifs))}
    <div class="tag_list">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="owl-carousel" id="owl-tag-gifs">
                        {foreach from=$this->gifs item='gif'}
                        <div class="one_gih">
                            {if $gif->image}
                            <a class="gif_load">
                                <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">
                                <img src="/media/media_content_poster/{$gif->id}/{$gif->image}.SW_400CF_1PR_sq.jpg" class="gif_img" alt="{$gif->name}">
                                <img data-src="https://{$gif->gif_cdn_url}" src="/assets/chill/images/logo.png" class="gif_gif" alt="{$gif->name}">
                            </a>
                            {else}
                            <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                            {/if}
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
    {if (count($this->soap))}
    <div class="tag_list">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h2>Сериалы</h2>
                    <div class="owl-carousel" id="owl-tag-soap">
                        {foreach from=$this->soap item='soap'}
                        <a href='/Soap/{$soap->id}-{$soap->translit_name}' title="{$soap->name}">
                            <div class="one_film_in_list">
                                {if $soap->image}
                                <img src="/media/media_content_poster/{$soap->id}/{$soap->image}.SW_400CF_1PR_vposter.jpg"  alt="{$soap->name}">
                                {else}
                                <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                {/if}
                                <div class="one_film_in_list_title">{$soap->name}</div>
                                <div class="top_one_news_stars top_top_stars aga-ratestars-{$soap->ratestars}">
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                    <i class="mdi mdi-star"></i>
                                </div>
                            </div>
                        </a>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
    {if (count($this->news))}
    <div class="tag_list">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h2>Новости</h2>
                    <div class="owl-carousel" id="owl-tag-news">
                        {foreach from=$this->news item='new'}
                        <div class="one_news">
                            <div class="one_news_date">
                                {$new->news_post_date_string} <span>{$new->news_post_time_string}</span>
                            </div>
                            <a href="/News/{$new->id}" title="{$new->name}">
                                <div class="one_news_main">
                                    {if $new->image}
                                    <img src="/media/media_content_poster/{$new->id}/{$new->image}.SW_400CF_1PR_sq.jpg"  alt="{$new->name}">
                                    {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                    {/if}
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-{$new->ratestars}">
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star "></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                    </div>
                                    <div class="one_news_title">
                                        {$new->name}
                                    </div>
                                </div>
                            </a>
                        </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
</div>
<script>
$('#owl-tag-news').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
});
$('#owl-tag-soap').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 2
        },
        600: {
            items: 3
        },
        1000: {
            items: 4
        }
    }
});
$('#owl-tag-gifs').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
});
$(".gif_load").each(function() {
            $(this).click(function() {
                $(this).find(".gif_sign").toggle(0);
                $(this).find(".gif_img").toggle(0);
                var a = $(this).find(".gif_gif").data("src");
                $(this).find(".gif_gif").attr("src",a);
                $(this).find(".gif_gif").toggle(0);
            });
        });
</script>