<div style="background:white;color:black;padding: 1em">
    Контроллер дает на выход следующие переменные:<br>
    <b>items</b> - массив новостей:
    <pre style="font-family: monospace">
        {if count($items) }
            {$items[0]|var_dump} 
        {/if}
    </pre>
    Поля новости не отличаются от полей на полной новости, только их меньше<br>.
    и доступ к ним - как к ключам массива, а не полям объекта, тоесть:<br>
    <b>{literal}{$items[0].default_poster}{/literal}</b>: {if count($items) }{$items[0].default_poster}{/if}<br><br><br><br>
    <b>total,page,perpage</b> - данные для построения пагинатора:
    <ul>
        <li>{literal}{$total}{/literal}:{$total}</li>
        <li>{literal}{$perpage}{/literal}:{$perpage}</li>
        <li>{literal}{$page}{/literal}:{$page}</li>
    </ul>
    <br><br><br><br>
    <b>paginator</b> - уже построеный пагинатор, если нужен:
    <pre>{$paginator|var_dump}</pre><br><br>
    Дополнительные параметры:<br>
    Для отладки пагинатора и вообще можно передать GET <b>perpage</b><br>
    Для замены лэйаута можно передать параметр <b>sys_render_layout</b><br>
    Для замены виева можно передать параметр <b>sys_render_template</b><br>
    <br><br><br><br><br>
    Адрес страницы с новостями (поскольку news/xx уже занято) - /newslist или newslist/[page]<br><br>
    пример: <a href="/newslist?perpage=2&sys_render_layout=raw&sys_render_template=json">/newslist?perpage=2&sys_render_layout=raw&sys_render_template=json</a><br><br>
    <a href="/newslist/2?perpage=1">/newslist/2?perpage=1</a>
    <div>
        ПРоверка транслятора:
        <div>russian_language={TT l='ru' t='russian_language'}</div>
        <div>current_language={TT  t='current_language'}</div>
        <div>engilish_language={TT l='en' t='engilish_language'}</div>
        ПРоверка транслятора - 2:
        <div>current_language={$T->T('current_language')}</div>
        <div>russian_language={$T->T('russian_language','ru')}</div>
        <div>engilsh_language={$T->T('english_language','en')}</div>
        <div>engilsh_language={$T->T('english_language')}</div>
        <div>engilsh_language={$T->T('english_language','ru')}</div>
        <div>engilsh_language={$T->T('english_language','es')}</div>
    </div>
    <div>
        {if {get_user_auth_status}}authorized{else}not authorized{/if}
    </div>
</div>
    
    