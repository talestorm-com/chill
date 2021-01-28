<style type='text/css'>
    div#aaa,div#aaa *{
        font-size: 14px;
        font-family: monospace;
        background: white;
        color:black;
    }
</style>
<div id='aaa'>
    {assign var='last_x_items' value=$controller->last_contents()}{* можно $controller->last_contents(100500) *}
    <ul>
    {foreach from=$last_x_items item='item'}
            <li>
                {$item->name}
                <img src='/media/media_content_poster/{$item->id}/{$item->image}.SW_200CF_1PR_sq.jpg' />
                {if $item->content_type==='ctSEASON'}
                    серал
                {/if}
                {if $item->content_type==='ctVIDEO'}
                    видос
                {/if}
            </li>
    {/foreach}
    </ul>
</div>