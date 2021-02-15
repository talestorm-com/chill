{*шаблон подборки!*}
<div class="lenta_collection">
    <div class="lenta_type">
        Подборка
    </div>
    <a href="/collection/{$item->content_id}" title="{$item->name}">
        <div class="lenta_collection_text">
            {$item->name}
        </div>
        <img src="{$image_url}" alt="{$item->name}" />
    </a>
</div>