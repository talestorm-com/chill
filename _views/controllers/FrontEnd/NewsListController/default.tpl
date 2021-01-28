<div id="all_news">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l10">
                            <h1>Новости</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="cat_slide">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div id="slider_cat" class="owl-carousel">
                        {foreach from=$items item='new' name=foo}
  {if $smarty.foreach.foo.index % 2 == 0}
                        <div>
                            <a href="/News/{$new.id}" title="{$new.name}">
                                <div class="one_film_janr">
                                    {if $new.default_poster}
                                    <img src="/media/media_content_poster/{$new.id}/{$new.default_poster}.SW_1200H_400CF_1PR_hposter.jpg" alt="{$new.name}">
                                    {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_1200H_400CF_1PR_hposter.jpg" />
                                    {/if}
                                    <div class="one_film_janr_title">{$new.name}</div>
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-{$new.ratestars}">
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="list_news">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        {foreach from=$items item='new' name=foo}
  {if $smarty.foreach.foo.index % 2 == 0}
  {else}
                        <div class="col s12 m6 l4">
                            <div class="one_news">
                                <div class="one_news_date">
                                    {$new.news_post_date_string} <span>{$new.news_post_time_string}</span>
                                </div>
                                <a href="/News/{$new.id}" title="{$new.name}">
                                    <div class="one_news_main">
                                        {if $new.default_poster}
                                        <img src="/media/media_content_poster/{$new.id}/{$new.default_poster}.SW_400CF_1PR_sq.jpg" alt="{$new.name}">
                                        {else}
                                        <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                        {/if}
                                        <div class="one_news_stars aga-ratestars-{$new.ratestars}">
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                            <i class="mdi mdi-star"></i>
                                        </div>
                                        <div class="one_news_title">
                                            {$new.name}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#slider_cat').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    items: 1
});
</script>