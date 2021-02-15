{*шаблон СЕРИИ!*}
<div class="one_film_in_list">

    <a href="{if $is_trailer}#{else}/Soap/{$item->series_soap_id}#serie_{$item->content_id}{/if}" title="{$item->name}">
    <img src="{$image_url}" alr="{$item->name}">
    <div class="one_film_in_list_title">{$item->name},{$item->series_season_name},{$item->series_soap_name}</div>
    <div class="top_one_news_stars top_top_stars">
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star"></i>
    </div>
    </a>
</div>