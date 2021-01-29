<div class="{$controller->MC}_comment_with_sticker">
    <div class="{$controller->MC}_comment_with_sticker_inner">
        <div class="comment-header">
            <div class="row">
                <div class="col s6">
                    <p class="com-aut">{$comment->author}</p>
                </div>
                <div class="col s6">
                    <p class="com-date">{$comment->datum->format('d.m.Y')}</p>
                </div>
            </div>
        </div>
        <div class="comment-body">{$comment->content}</div>
        <div class="votepanel">
            <a href="#" class="comment-vote-minus" data-id="{$comment->id}"><i class="mdi mdi-heart-broken-outline"></i></a>
            <span>{$comment->rating} </span>
            <a href="#" class="comment-vote-plus" data-id="{$comment->id}"><i class="mdi mdi-heart"></i></a>
        </div>
    </div>
</div>
{if $comment->r!=''}
<div class="comment_res">
<div class="comment_logo">
<img src="/assets/chill/images/logo_grad_bg.png">
</div>
<div class="comment_res_in">
<h4>Ответ от Chill</h4>
<div class="comment_res_in_text">
{$comment->r}
</div>
</div>
</div>
{/if}