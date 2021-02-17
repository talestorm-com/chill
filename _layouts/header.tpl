<!DOCTYPE html>
<html lang="ru">
    <head>
        {foreach from=$OUT->assets item=asset}
            {include "./{$asset->template}.tpl"}
        {/foreach}
