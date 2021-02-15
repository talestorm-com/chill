<style type="text/css">
    div#aaa,div#aaa *{
        background:white!important;
        color:black!important;
        font-size: 14px!important;
        font-family: monospace!important;
        margin:0!important;
    }
</style>

<div id="aaa">
    Список стран:<br>
    {foreach from=$country_list item='c'}
        {$c.id}. {$c.name}<br>
    {/foreach}
    <br>
    Список emoji:<br>
    {foreach from=$emoji_list item='c'}
        {$c.id}. {$c.tag} {$c.name}  <img src='/media/emojirenderer/{$c.id}/emoji.SW_45H_45CF_1B_ffffff.jpg' /><br>
    {/foreach}
    <br>
    В отношении картинок эмодзи - нужно <b>всегда</b> указывать высоту и ширину и всегда <b>указывать их равными</b>! А также всегда указывать <b>CF_1<b>.
    <br>
    <br>
    Список жанров:<br>
    {foreach from=$genre_list item='c'}
        {$c.id}. {$c.name}<br>
    {/foreach}
    <br>
    
    Список контента по жанрам:
    {foreach from=$rows item='row'}
        {if count($row->soap) || count($row->video)}
            <h3>{$row->genre_id} {$row->genre_name}</h3>
            {if count($row->soap) }
                <h4>Сериалы</h4>
            <ul>
                {foreach from=$row->soap item='item'}
                    <li>{$item->name}</li>
                {/foreach}
            </ul>
            {/if}
            {if count($row->video) }
                <h4>Видео</h4>
            <ul>
                {foreach from=$row->video item='item'}
                    <li>{$item->name}</li>
                {/foreach}
            </ul>
            {/if}
        {/if}
    {/foreach}
    
    
</div>