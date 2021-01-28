<div class="row">
    {assign var='index' value=-1}
    {foreach from=$this->items item='item'}
    {assign var='index' value=$index+1}
    {if $index>28}
    {assign var='index' value=1}
    {/if}
    
    {if $index === 4 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="emo_block_main">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_emo.jpg)">
            </div>
        </div>
    </div>
    {/if}
    {if $index === 5 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="lang_block_main">
        <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_lang.gif)">
            </div>
        </div>
    </div>
    {/if}
    {if $index === 9 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="author_block_main">
            <a href="/page/for_authors">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_sozd.jpg)">
            </div>
            </a>
        </div>
    </div>
    {/if}
    {if $index === 10 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="janr_block_main">
        <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_janr_2.gif)">
            </div>
        </div>
    </div>
    {/if}

    {if $index === 18 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="emo_block_main">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_emo.jpg)">
            </div>
        </div>
    </div>
    {/if}
    {if $index === 19 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="lang_block_main">
        <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_lang.gif)">
            </div>
        </div>
    </div>
    {/if}
    {if $index === 23 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="author_block_main">
            <a href="/page/for_authors">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_sozd.jpg)">
            </div>
            </a>
        </div>
    </div>
    {/if}
    {if $index === 24 }
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
        <div class="janr_block_main">
        <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/lent_janr_2.jpg)">
            </div>
        </div>
    </div>
    {/if}
    
    {assign var='image_url' value="/media/{$item->get_image_url()}.SW_600H_400CF_1.jpg"}
    {assign var='image_urla' value="/media/{$item->get_image_url()}.SW_600H_600CF_1.jpg"}
    {assign var='image_url_sq' value="/media/{$item->get_image_url()}.SW_400H_400CF_1.jpg"}
    {if $item->content_type==='ctSEASON'}
    <div class="col s12 l3">
    <a href='/Soap/{$item->id}' title="{$item->name}">
        <div class="one_film_in_list chill-lenta-item-new-{$item->content_type}">
            <div class="film_left">
            
                
                    {if $item->image}
                    <img src="/media/media_content_poster/{$item->id}/{$item->image}.SW_400H_400CF_1PR_sq.jpg">
                    {else}
                    <img src="/media/fallback/1/media_content_poster.SW_400H_400CF_1PR_sq.jpg" />
                    {/if}
                
            </div>
            <div class="film_right">
                <div class="one_film_in_list_title_a">{$item->name}</div>
                <div class="one_film_prop">Страна: <span>{if $item->origin_country_name}{$item->origin_country_name}{/if}</span></div>
                <div class="one_film_prop">Жанр: <span>{if $item->genre_name}{$item->genre_name}{/if}</span></div>
                <div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count}{/if} сезон ({if $item->series_count}{$item->series_count}{/if} серий)</span></div>
            </div>
            {if $item->id===219}
            <div class="film_right_free">
                <span>Free</span>
            </div>
            {/if}
            <div class="film_right_lang">
                <span>{if $item->track_language_name}{$item->track_language_name}{/if}</span>
            </div>
        </div>
        </a>
    </div>
    {else if $item->content_type==='ctGIF'}
    <div class="col s12 l3">
    <a href="{$item->gif_target_url}">
        <div class="one_film_in_list chill-lenta-item-new-{$item->content_type}">
            <div class="film_left one_gih">
                <div class="gif_load">
                    <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">
                    <img src="{$image_url_sq}" class="gif_img" alt="{$item->name}">
                    
                        <img src="https://{$item->gif_cdn_url}" class="gif_gif" alt="{$item->name}">
                    
                </div>
            </div>
            <div class="film_right">
                <div class="one_film_in_list_title_a">{$item->name}</div>
                <div class="one_film_prop">Страна: <span>{if $item->origin_country_name}{$item->origin_country_name}{/if}</span></div>
                <div class="one_film_prop">Жанр: <span>{if $item->genre_name}{$item->genre_name}{/if}</span></div>
                <div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count}{/if} сезон ({if $item->series_count}{$item->series_count}{/if} серий)</span></div>
            </div>
            <div class="film_right_lang">
                <span>{if $item->track_language_name}{$item->track_language_name}{/if}</span>
            </div>
        </div>
        </a>
    </div>
    {else if $item->content_type==='ctTRAILER'}
<div class="col s12 l3">
<div class="run_trailer" data-id='{$item->id}' data-srca='{$item->trailer_target_url}'>
        <div class="one_film_in_list chill-lenta-item-new-{$item->content_type}">
            <div class="film_left">
                    <img src="/assets/chill/images/play_sign.png" class="gif_sign" alt="Загрузка">
                    <img src="{$image_url_sq}" class="gif_img" alt="{$item->name}">
                </div>
            
            <div class="film_right">
                <div class="one_film_in_list_title_a">{$item->name}</div>
                <div class="one_film_prop">Страна: <span>{if $item->origin_country_name}{$item->origin_country_name}{/if}</span></div>
                <div class="one_film_prop">Жанр: <span>{if $item->genre_name}{$item->genre_name}{/if}</span></div>
                <div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count}{/if} сезон ({if $item->series_count}{$item->series_count}{/if} серий)</span></div>
            </div>
            <div class="film_right_lang">
                <span>{if $item->track_language_name}{$item->track_language_name}{/if}</span>
            </div>
        </div>
        </div>
    </div>

    {else if $item->content_type==='ctBANNER'}
    <div class="chill-lenta-item-new chill-lenta-item-new-{$item->content_type} col s12 l4">
    <div class="banner_collection">
        <a href="{$item->banner_url}" target="_blank" class="ribbon_link_out">
        <div class="chill_main_lent_block" style="background-image:url({$image_urla})">
            </div>
            </a>
        </div>
    </div>
    {else if $item->content_type==='ctCOLLECTION'}
    <div class="chill-lenta-item-new chill-lenta-item-new-{$item->content_type} col s12 l4">
        <div class="lenta_collection">
            <a href="/collection/{$item->content_id}" title="{$item->name}">
                
                <div class="chill_main_lent_block" style="background-image:url({$image_urla})">
            </div>
            </a>
        </div>
    </div>
    {/if}
    {/foreach}
</div>