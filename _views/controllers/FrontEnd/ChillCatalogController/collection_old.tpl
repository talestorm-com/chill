<div id="podbor">
    <div id="podbor_header" style="background-image:url(/media/media_content_poster/{$collection->id}/{$collection->default_poster}.SW_1400CF_1PR_hposter.jpg)" class="valign-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h1 id="podbor_header_title">{$collection->name}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="films_list_a">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        {foreach from=$collection item='item'}
                        <div class="col s6 m4 l3">
                            <a href="/Soap/{$item->id}-{$item->translit_name}">
                                <div class="one_film_in_list">
                                    {if $item->default_poster}
                                    <img src="/media/media_content_poster/{$item->id}/{$item->default_poster}.SW_400CF_1PR_vposter.jpg" />
                                    {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                    {/if}
                                    <div class="one_film_in_list_title">{$item->name}</div>
                                     <div class="top_one_news_stars top_top_stars aga-ratestars-{$item->ratestars}">
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
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
</div>