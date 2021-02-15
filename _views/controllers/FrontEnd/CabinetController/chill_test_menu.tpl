<div style='color:white'>
    <h1>{visit_counter}</h1>
{* Тут просто как тапочка - assign - имя пременной в которую надо поместить список *}
{display_menu_lent assign='items'}
{foreach from=$items item='item'}
    {$item|print_r}
{/foreach}
</div>

