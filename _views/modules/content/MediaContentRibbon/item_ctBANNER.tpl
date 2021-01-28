{*шаблон ВСТАВКИ!*}
<div class="lenta_info_1" style="background:{$item->banner_background_color}">
    <a href="{$item->banner_url}">
        {if $item->banner_text}<div class="lenta_text">{$item->banner_text}</div>{/if}
        <img src="{$image_url}" alt="{$item->banner_text}">
    </a>
</div>
