{*шаблон НОВОСТИ!*}
<div class="lenta_news">    
    <div class="lenta_type">
        Новость
    </div>
    <a href="/News/{$item->content_id}">
        <div class="lenta_news_text">
            {$item->name}
        </div>
        <img src="{$image_url}" />
    </a>
</div>