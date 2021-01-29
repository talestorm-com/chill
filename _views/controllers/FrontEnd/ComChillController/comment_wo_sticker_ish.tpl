<div class="{$controller->MC}_comment_without_sticker">
    <div class="{$controller->MC}_comment_without_sticker_inner">        
        <div class="comment-panel">
            <div class="comment-header">{$comment->datum->format('d.m.Y')} {$comment->author}</div>
            <div class="comment-body">{$comment->content}</div>
        </div>
        <div class="votepanel">
            <a href="#" class="comment-vote-minus" data-id="{$comment->id}">Голос минус</a>
            {$comment->rating} 
            <a href="#" class="comment-vote-plus" data-id="{$comment->id}">голос плюс</a>
        </div>
    </div>
</div>