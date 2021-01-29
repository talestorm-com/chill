<div style='color:white'>
{* Тут просто как тапочка - assign - имя пременной в которую надо поместить список *}
{get_emoji_list assign='emoji_list'}       
{get_genre_list assign='genre_list'}
emohis:
<ul>
    {foreach from=$emoji_list item='emo'}
        <li>{$emo.id}. {$emo.name}</li> {* АХТУНГ! - в этих списках жоступ к ключу массива (через точку) *}
    {/foreach}
</ul>

genres:
{foreach from=$genre_list item='gen'}
    <li>{$gen.id}. {$gen.name}</li>
{/foreach}


{* тут хитрее 
    - assign - имя переменной в которую будет помещен спсиок 
    - q - количество элементов для извлечения (default = 5)
    - ct - список типов контента, подлежащих извлечению
         - можно несколько через запятую 
         - по дефолту - не найдет ничего!!!!!! 
    *}
{get_last_contents assign='content_list' q=3 ct='ctCOLLECTION'}
contents - 1:
{foreach from=$content_list item='co'}
    <li>{$co->id}. {$co->name} ({$co->content_type})</li>{* Здесь доступ к полю объекта (->) *}
{/foreach}


{get_last_contents assign='content_list' q=3 ct='ctCOLLECTION,ctSEASON'}
contents - 2:
{foreach from=$content_list item='co'}
    <li>{$co->id}. {$co->name} ({$co->content_type})</li>
{/foreach}


{get_last_contents assign='content_list' q=10 ct='ctTEXT,ctSEASON'}
contents - 3:
{foreach from=$content_list item='co'}
    <li>{$co->id}. {$co->name} ({$co->content_type})</li>
{/foreach}
</div>
