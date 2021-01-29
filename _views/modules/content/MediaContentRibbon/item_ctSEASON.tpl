{*шаблон СЕРИАЛА!*}
<div class="one_film_in_list">
{$item|var_dump}

    <a href="{if $is_trailer}#{else}/Soap/{$item->content_id}{/if}" title="{$item->name}">
    <img src="{$image_url}" alt="{$item->name}">
    <div class="one_film_in_list_title">{if $is_trailer}Трейлер:{$item->trailed_soap_name}{else}{$item->name}{/if}</div>
    <div class="top_one_news_stars top_top_stars aga-ratestars-{$item->ratestars}">
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star active_star"></i>
        <i class="mdi mdi-star"></i>
    </div>
    </a>
</div>