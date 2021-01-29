{*шаблон СЕЗОНА!*}
<div class="one_film_in_list">
    <a href="{if $is_trailer}#{else}/Soap/{$item->season_soap_id}#season_{$item->content_id}{/if}" title="{$item->name}">
    <img src="{$image_url}" alt="{$item->name}">
    <div class="one_film_in_list_title">{if $is_trailer}Трейлер:{$item->trailed_season_name},{$item->trailed_season_soap_name}{else}{$item->name}{/if}</div>
    <div class="top_one_news_stars top_top_stars">
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star"></i>
    </div>
    </a>
</div>