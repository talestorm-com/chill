<div class="row">


    {assign var='index' value=-1}
    {foreach from=$this->items item='item'}
        {assign var='index' value=$index+1}
        {if $index>25}
            {assign var='index' value=1}
        {/if}




        {if $index === 18}
            <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                <div class="author_block_main">
                    {if {get_user_auth_status}}
                        <a href="/page/menu">
                            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/youchill.jpg);background-color:#ffce14">
                            </div>
                        </a>
                    {else}
                        <a href="/Profile">
                            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/pl_a.gif)">
                            </div>
                        </a>
                    {/if}
                </div>
            </div>
        {/if}

        {if $index === 11}
            <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                <div class="emo_block_main">
                    <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/1307_emo.gif)">
                    </div>
                </div>
            </div>
        {/if}
     <!--   {if $index === 9}
            <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                <div class="emo_block_main">
                    <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/1307_emo.gif)">
                    </div>
                </div>
            </div>
        {/if}-->
        {if $index === 24}
            <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                <div class="author_block_main">
                    <a href="/page/for_authors">
                        <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/chill_1.gif)">
                        </div>
                    </a>
                </div>
            </div>
        {/if}



        {assign var='image_url' value="/media/{$item->get_image_url()}.SW_600H_400CF_1.jpg"}
        {assign var='image_urla' value="/media/{$item->get_image_url()}.SW_600H_600CF_1.jpg"}
        {assign var='image_url_sq' value="/media/{$item->get_image_url()}.SW_400H_400CF_1.jpg"}
        {assign var='image_url_qq' value="/media/{$item->get_image_url()}.SW_400H_520CF_1.jpg"}
        {if $item->content_type==='ctSEASON'}
            <div class="col s12 l3">

                <div class="one_film_in_list chill-lenta-item-new-{$item->content_type}">
                    {if $item->lent_mode==='poster'}
                        <a href='/Soap/{$item->id}-{$item->translit_name}' title="{$item->name}">
                            <div class="film_left">
                                {if $item->lent_image_name}
                                    <img src="/media/lent_poster/{$item->id}/{$item->lent_image_name}.SW_400H_520CF_1.jpg" alt="{$item->name}">
                                {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1PR_sq.jpg" />
                                {/if}

                            </div>
                        </a>
                    {else if $item->lent_mode==='gif'}
                        <a href='/Soap/{$item->id}-{$item->translit_name}' title="{$item->name}">
                            <div class="film_left one_gih">
                                <div class="gif_load">
                                    <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">
                                    <img src="/media/lent_poster/{$item->id}/{$item->lent_image_name}.SW_400H_520CF_1.jpg" class="gif_img" alt="{$item->name}">
                                    <img src="https://{$item->gif_cdn_url}" class="gif_gif" alt="{$item->name}">

                                </div>
                            </div>
                        </a>
                    {else if $item->lent_mode==='video'}
                        <div class="film_left">

                            <div class="run_trailer" data-id='{$item->id}' data-srca='/Soap/{$item->id}-{$item->translit_name}' data-video_url="{$item->video_cdn_url}">
                                <div class="film_left_text_box">
                                    <a class="film_left_text_box_box">
                                        <i class="mdi mdi-play"></i> {$item->lent_message}
                                    </a>

                                </div>
                                <!--<img src="/assets/chill/images/play_sign.png" class="gif_sign" alt="Загрузка">-->
                                <img src="/media/lent_poster/{$item->id}/{$item->lent_image_name}.SW_400H_520CF_1.jpg" class="gif_img" alt="{$item->name}">

                            </div>
                        </div>
                    {else}
                        <a href='/Soap/{$item->id}-{$item->translit_name}' title="{$item->name}">
                            <div class="film_left">

                                {if $item->image}
                                    <img src="/media/media_content_poster/{$item->id}/{$item->image}.SW_400H_520CF_1.jpg">
                                {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                                {/if}

                            </div>
                        </a>
                    {/if}
                    <a href='/Soap/{$item->id}-{$item->translit_name}' title="{$item->name}">
                        <div class="film_right">
                            <div class="film_right_in">
                                <div class="in_film_right">
                                    {if $item->free}
                                        <div class="film_right_free">
                                            <span>Free</span>
                                        </div>
                                    {/if}
                                    {if $item->track_language_name}
                                        <div class="film_right_lang ru_lang">
                                            <span>ru</span>
                                        </div>
                                        {if $item->track_language_name !="ru"}
                                        <div class="film_right_lang {$item->track_language_name}_lang">
                                            <span>{$item->track_language_name}</span>
                                        </div>
                                        {/if}
                                    {/if}
                                </div>    
                                <div class="one_film_in_list_title_a" {if $item->origin_country_name !='' and $item->genre_name !=''}{else}style="max-height: 96px;"{/if}>{$item->name}</div>
                                {if $item->origin_country_name and $item->genre_name}<div class="one_film_prop">{if $item->origin_country_name}<span>{$item->origin_country_name}</span>, {/if}{if $item->genre_name}<span>{$item->genre_name}</span>{/if}</div>{/if}
                                {if $item->seasons_count and $item->series_count}<div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count} {TT  t='season'}{/if} {if $item->series_count}({$item->series_count} {TT  t='series_lent'}){/if} </span></div>{/if}
                            </div>
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
                            <div class="in_film_right">
                                {if $item->free}
                                    <div class="film_right_free">
                                        <span>Free</span>
                                    </div>
                                {/if}
                                {if $item->track_language_name}
                                    <div class="film_right_lang {$item->track_language_name}_lang">
                                        <span>{$item->track_language_name}</span>
                                    </div>
                                {/if}
                            </div>
                            <div class="one_film_in_list_title_a">{$item->name}</div>
                            <div class="one_film_prop">Страна: <span>{if $item->origin_country_name}{$item->origin_country_name}{/if}</span></div>
                            <div class="one_film_prop">Жанр: <span>{if $item->genre_name}{$item->genre_name}{/if}</span></div>
                            <div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count}{/if} сезон ({if $item->series_count}{$item->series_count}{/if} серий)</span></div>
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
                            <div class="in_film_right">
                                {if $item->free}
                                    <div class="film_right_free">
                                        <span>Free</span>
                                    </div>
                                {/if}
                                {if $item->track_language_name}
                                    <div class="film_right_lang {$item->track_language_name}_lang">
                                        <span>{$item->track_language_name}</span>
                                    </div>
                                {/if}
                            </div>
                            <div class="one_film_in_list_title_a">{$item->name}</div>
                            <div class="one_film_prop">Страна: <span>{if $item->origin_country_name}{$item->origin_country_name}{/if}</span></div>
                            <div class="one_film_prop">Жанр: <span>{if $item->genre_name}{$item->genre_name}{/if}</span></div>
                            <div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count}{/if} сезон ({if $item->series_count}{$item->series_count}{/if} серий)</span></div>
                        </div>

                    </div>
                </div>
            </div>

        {else if $item->content_type==='ctBANNER'}
            <div class="chill-lenta-item-new chill-lenta-item-new-{$item->content_type} col s12 l4">
                <div class="banner_collection">
                    <a {if $item->banner_url !=''}href="{$item->banner_url}?ref=chillvision.ru" target="_blank"{/if} class="ribbon_link_out">
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
    <script>
        {literal}
            (function () {

                window.global_season_counter_ribbon = window.global_season_counter_ribbon || 0;
                var items = [];
        {/literal}
        {foreach from=$this->items item='item'}
            {if $item->content_type==='ctSEASON'}
                {literal}
                        window.global_season_counter_ribbon++;
                        items.push({
                            'name': '{/literal}{$item->name}{literal}',
                            'id': '{/literal}{$item->id}{literal}',
                            'price': '0', // стоимость
                            'brand': '{/literal}{$item->origin_country_name}{literal}',
                            'category': '{/literal}{$item->genre_name}{literal}',
                            'list': 'Lenta',
                            'position': window.global_season_counter_ribbon
                        });
                {/literal}
            {/if}
        {/foreach}
        {literal}
                if (items.length) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({event: 'gtm-ee-event', "gtm-ee-event-category": 'Enhanced Ecommerce',
                        "gtm-ee-event-action": 'Product Impressions', "gtm-ee-event-non-interaction": 'True',
                        ecommerce: {currencyCode: 'RUB', impressions: items}});
                }
            })();
        {/literal}
    </script>
</div>