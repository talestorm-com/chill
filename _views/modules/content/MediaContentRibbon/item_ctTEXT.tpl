{*шаблон НОВОСТИ!*}
<div class="lenta_news">    
    <div class="lenta_type">
        Новость
    </div>
    <a href="/News/{$item->content_id}" title="{$item->name}">
        <div class="lenta_news_text">
            {$item->name}
        </div>
        <img src="{$image_url}" alt="{$item->name}"/>
        
    
    </a>
</div>