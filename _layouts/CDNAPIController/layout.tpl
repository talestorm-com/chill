<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Загрузчик видео</title>
        {foreach from=$OUT->assets item=asset}
            {include "./../{$asset->template}.tpl"}
        {/foreach}  
    </head>
    <body>
        <div class="{$controller->MC}FormWrapper" style="display:none">            
            {display_page_content}
        </div>
        <div class="{$controller->MC}loader-wrapper">
            {include {$controller->common_templtes("preloader")}}
        </div>
        <script>
            {literal}
                (function () {
                    window.Eve = window.Eve || {};
                    window.Eve.EFO = window.Eve.EFO || {};
                    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                    window.Eve.EFO.Ready.push(function () {                             
                        jQuery('.{/literal}{$controller->MC}{literal}loader-wrapper').hide();
                        jQuery('.{/literal}{$controller->MC}{literal}FormWrapper').show();
                    });
                    
                })();
            {/literal}
        </script>
    </body>
</html>