{assign var='PAGE_UUID' value=$OUT->get_uuid()}

<div class="row">

    {assign var='absolute_index' value=$this->get_index_remainder(47)-1}{*47 сюда переехало из условия индекса*}
    {assign var='index' value=-1}
    {foreach from=$this->items item='item'}
        {assign var='index' value=$index+1}
        {assign var='absolute_index' value=$absolute_index+1}
        {if $index>47}
            {assign var='index' value=1}
        {/if}
        {if $absolute_index>47}
            {assign var='absolute_index' value=1}
        {/if}
        {if $this->get_debug_enabled()}
            {if $this->inset_exists($absolute_index)}
                {include $this->get_inset_path($absolute_index)}
            {/if}
        {else}
            {if $index === 4 || $index === 9 || $index === 14 || $index === 25 || $index === 36 || $index === 41}
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
            {if $index === 4}
                <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                    <div class="author_block_main">
                        <a href="/profile/#referuu">
                            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/chill_12.jpg)">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
            {if $index === 4 ||$index === 14 || $index === 25}
                <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                    <div class="author_block_main">
                        <a href="/page/for_authors">
                            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/chill_1.gif)">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
            {if $index === 41}
                <div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
                    <div class="emo_block_main">
                        <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/1307_emo.gif)">
                        </div>
                    </div>
                </div>
            {/if}
            <!-- комментируй -->
            {if  $index === 25}
                <div class="chill-lenta-item-new chill-lenta-item-new-ctBANNER col s12 l4">
                    <div class="banner_collection">
                        <a href="/comments" target="_blank" class="ribbon_link_out">
                            <div class="chill_main_lent_block" style="background-image:url(/media/media_content_poster/362/309fb22b9b24f4bdc2e75dace7336d54.SW_600H_600CF_1.jpg)">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
            <!-- каталог -->
            {if  $index === 14 || $index === 30}
                <div class="chill-lenta-item-new chill-lenta-item-new-ctBANNER col s12 l4">
                    <div class="banner_collection">
                        <a href="https://catalog.chillvision.ru" target="_blank" class="ribbon_link_out">
                            <div class="chill_main_lent_block" style="background-image:url(/media/media_content_poster/361/4633255c3286158f8a1b6210ebdf6fc6.SW_600H_600CF_1.jpg)">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
            {if  $index === 46}
                <div class="chill-lenta-item-new chill-lenta-item-new-ctBANNER col s12 l4">
                    <div class="banner_collection">
                        <a href="https://digitalreporter.ru/?ref=chillvision.ru" target="_blank" class="ribbon_link_out">
                            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/dr.jpg)">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
            {if  $index === 46}
                <div class="chill-lenta-item-new chill-lenta-item-new-ctBANNER col s12 l4">
                    <div class="banner_collection">
                        <a href="https://velvetmusic.ru/?ref=chillvision.ru" target="_blank" class="ribbon_link_out">
                            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/velvet.jpg)">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
        {/if}


        {if $item===null }
            <div class="col s12 l3 hide-on-med-and-down">
                <div id="empty_block"></div>
            </div>
        {else}    
            {assign var='image_url' value="/media/{$item->get_image_url()}.SW_600H_400CF_1.jpg"}
            {assign var='image_urla' value="/media/{$item->get_image_url()}.SW_600H_600CF_1.jpg"}
            {assign var='image_url_sq' value="/media/{$item->get_image_url()}.SW_400H_400CF_1.jpg"}
            {assign var='image_url_qq' value="/media/{$item->get_image_url()}.SW_400H_520CF_1.jpg"}

            {if $item->content_type==='ctSEASON'}
                <div class="col s12 l3">
                    <div class="one_film_in_list chill-lenta-item-new-{$item->content_type}" id="{$PAGE_UUID}_{$item->id}">
                        {if $this->get_debug_enabled()}<div style="color:red;font-size:14px;position:absolute;z-index:22;background:white;top:0;left:0">{$absolute_index}</div><div style="color:blue;font-size:14px;position:absolute;z-index:22;background:white;top:0;left:20px;">{$index}</div>{/if}
                            {if $item->lent_mode==='poster'}
                            <div class="film_left">
                                {if $item->age_restriction_name ==='0+'}
                                    <div class="age_age">{$item->age_restriction_name}</div>
                                {/if}
                                <a href='/Soap/{$item->id}' title="{$item->name}">
                                    <div class="lent_omg_in">
                                        {if $item->lent_image_name}
                                            <img loading="lazy" src="/media/lent_poster/{$item->id}/{$item->lent_image_name}.SW_400H_520CF_1.jpg" alt="{$item->name}" class="lazyload">
                                        {else}
                                            <img loading="lazy" src="/media/fallback/1/media_content_poster.SW_400H_520CF_1PR_sq.jpg" />
                                        {/if}
                                    </div>
                                </a>
                            </div>

                        {else if $item->lent_mode==='gif'}

                            <div class="film_left one_gih">
                                {if $item->age_restriction_name ==='0+'}
                                    <div class="age_age">{$item->age_restriction_name}</div>
                                {/if}
                                <a href='/Soap/{$item->id}' title="{$item->name}">
                                    <div class="gif_load">
                                        <div class="lent_omg_in">
                                            <!-- <img src="/assets/chill/images/gif_sign.png" class="gif_sign" alt="Загрузка">-->
                                            <img src="/media/lent_poster/{$item->id}/{$item->lent_image_name}.SW_400H_520CF_1.jpg" class="gif_img" alt="{$item->name}" style="display:none;"> 
                                            <img loading="lazy" src="https://{$item->gif_cdn_url}" class="gif_gif" alt="{$item->name}">
                                        </div>
                                    </div>
                                </a>
                            </div>

                        {else if $item->lent_mode==='video'}
                            <div class="film_left">
                                {if $item->age_restriction_name ==='0+'}
                                    <div class="age_age">{$item->age_restriction_name}</div>
                                {/if}
                                <div class="run_trailer" data-id='{$item->id}' data-srca='/Soap/{$item->id}' data-video_url="{$item->video_cdn_url}">
                                    <div class="film_left_text_box">
                                        <a class="film_left_text_box_box">

                                            <i class="mdi mdi-play"></i> {$item->lent_message}

                                        </a>

                                    </div>
                                    <div class="lent_omg_in">
                                        <img loading="lazy" src="/media/lent_poster/{$item->id}/{$item->lent_image_name}.SW_400H_520CF_1.jpg" class="gif_img" alt="{$item->name}">
                                    </div>

                                </div>
                            </div>
                        {else}

                            <div class="film_left">
                                {if $item->age_restriction_name ==='0+'}
                                    <div class="age_age">{$item->age_restriction_name}</div>
                                {/if}
                                <a href='/Soap/{$item->id}' title="{$item->name}">
                                    <div class="lent_omg_in">
                                        {if $item->image}
                                            <img loading="lazy" src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg">
                                        {else}
                                            <img loading="lazy" src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                                        {/if}
                                    </div>
                                </a>
                            </div>

                        {/if}

                        <div class="film_right">
                            <a href='/Soap/{$item->id}' title="{$item->name}">
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
                                    {if $item->origin_language !=''}<div class="one_film_prop one_film_prop_duo">{$item->origin_language|replace:'|':'<br>'}</div>{else}
                                        {if $item->origin_country_name and $item->genre_name}<div class="one_film_prop">{if $item->origin_country_name}<span>{$item->origin_country_name}</span>, {/if}{if $item->genre_name}<span>{$item->genre_name}</span>{/if}</div>{/if}
                                        {if $item->seasons_count and $item->series_count}<div class="one_film_prop"><span>{if $item->seasons_count}{$item->seasons_count} <span class="seas_count_sl" data-seas="{$item->seasons_count}"></span>{/if} {if $item->series_count}({$item->series_count} <span class="series_count_sl" data-ser="{$item->series_count}"></span>){/if} </span></div>{/if}
                                    {/if}
                                </div>
                        </div>
                        </a>
                    </div>

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
                        <a {if $item->banner_url !=''}href="{$item->banner_url}" target="_blank"{/if} class="ribbon_link_out">
                            <div class="chill_main_lent_block" style="background-image:url({$image_urla})">
                            </div>
                        </a>
                    </div>
                </div>
            {else if $item->content_type==='ctCOLLECTION'}
                <div class="chill-lenta-item-new chill-lenta-item-new-{$item->content_type} col s12 l4"  id="{$PAGE_UUID}_{$item->id}">
                    <div class="lenta_collection">
                        <a href="/collection/{$item->content_id}" title="{$item->name}">

                            <div class="chill_main_lent_block" style="background-image:url({$image_urla})">
                            </div>
                        </a>
                    </div>
                </div>
            {/if}
        {/if}
    {/foreach}
    <script>

        {literal}
            (function () {
                window.global_season_counter_ribbon = window.global_season_counter_ribbon || 0;
                window.global_collection_slot_number = window.global_collection_slot_number || 0;
                var items = [];
                var ids_to_monitor = [];
                var monitorable_data = {};
        {/literal}
        {foreach from=$this->items item='item'}
            {if $item->content_type==='ctSEASON' || $item->content_type==='ctCOLLECTION'}
                {literal}
                        ids_to_monitor.push('{/literal}{$PAGE_UUID}_{$item->id}{literal}');
                {/literal}{if $item->content_type==='ctSEASON'}{literal}
                                window.global_season_counter_ribbon++;
                                monitorable_data['{/literal}{$PAGE_UUID}_{$item->id}{literal}'] = {
                                            'name': '{/literal}{$item->name}{literal}',
                                            'id': '{/literal}{$item->id}{literal}',
                                            'price': '0', // стоимость
                                            'brand': '{/literal}{$item->origin_country_name}{literal}',
                                            'category': '{/literal}{$item->genre_name}{literal}',
                                            'list': 'Lenta',
                                            'position': window.global_season_counter_ribbon
                                        };
                {/literal}{else}{literal}
                                        window.global_collection_slot_number++;
                                        monitorable_data['{/literal}{$PAGE_UUID}_{$item->id}{literal}'] = {
                                                    'name': '{/literal}{$item->name}{literal}',
                                                    'id': '{/literal}{$item->id}{literal}',
                                                    'creative': '{/literal}{$item->name}{literal}',
                                                    'position': window.global_collection_slot_number
                                                };

                {/literal}{/if}{literal}
                {/literal}
            {/if}

        {/foreach}
        {literal}                
                        function is_in_viewport(n) {
                            var r = n.getBoundingClientRect();
                            return (
                                    r.top <= (window.innerHeight || document.documentElement.clientHeight)
                                    //r.top >= 0 &&
                                    //r.left >= 0 
                                    //&& r.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                                    //r.right <= (window.innerWidth || document.documentElement.clientWidth)
                                    );
                        }
                        if (ids_to_monitor.length) {
                            var o = {};
                            o['scroll_{/literal}{$PAGE_UUID}{literal}'] = function (e) {
                                // check who is in viewport
                                // debugger;
                                var items_in_viewport = [];
                                var collections_in_viewport = [];
                                var nmi = [];
                                for (var i = 0; i < ids_to_monitor.length; i++) {
                                    var node = document.getElementById(ids_to_monitor[i]);
                                    if (!node) {
                                        console.log('no node:' + ids_to_monitor[i]);
                                        continue;
                                    }
                                    //debugger;
                                    if (is_in_viewport(node)) {
                                        var dta = monitorable_data[ids_to_monitor[i]];
                                        if (dta && (typeof (dta) === 'object') && dta.hasOwnProperty('creative')) {
                                            collections_in_viewport.push(monitorable_data[ids_to_monitor[i]]);
                                        } else {
                                            items_in_viewport.push(monitorable_data[ids_to_monitor[i]]);
                                        }
                                    } else {
                                        nmi.push(ids_to_monitor[i]);
                                    }
                                }
                                ids_to_monitor = nmi;
                                // console.log(ids_to_monitor);
                                if (!ids_to_monitor.length) {
                                    document.removeEventListener('scroll', o['scroll_{/literal}{$PAGE_UUID}{literal}']);
                                    monitorable_data = null;
                                }

                                if (items_in_viewport.length) {
                                    window.dataLayer = window.dataLayer || [];
                                    window.dataLayer.push({event: 'gtm-ee-event', "gtm-ee-event-category": 'Enhanced Ecommerce',
                                        "gtm-ee-event-action": 'Product Impressions', "gtm-ee-event-non-interaction": 'True',
                                        ecommerce: {currencyCode: 'RUB', impressions: items_in_viewport}});
                                    console.log('ga_posted_items', items_in_viewport);
                                }
                                if (collections_in_viewport.length) {
                                    window.dataLayer = window.dataLayer || [];
                                    window.dataLayer.push({
                                        'ecommerce': {
                                            'promoView': {
                                                'promotions': collections_in_viewport
                                            }
                                        },
                                        'event': 'gtm-ee-event', 'gtm-ee-event-category': 'Enhanced Ecommerce', 'gtm-ee-event-action': 'Promotion Impressions', 'gtm-ee-event-non-interaction': 'True'
                                    });
                                    console.log('ga_posted_collections', collections_in_viewport);
                                }
                            };
                            o['scroll_{/literal}{$PAGE_UUID}{literal}']();
                            document.addEventListener('scroll', o['scroll_{/literal}{$PAGE_UUID}{literal}']);
                        }
                    })();
        {/literal}
    </script>
</div>