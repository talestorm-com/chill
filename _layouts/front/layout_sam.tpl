{$OUT->add_script("/assets/js/front/layout_chill.js", 0, true)|void}
{include '../header.tpl'}
<meta name="viewport" content="initial-scale=1, maximum-scale=1" />
{$OUT->meta->render()}
</head>
<body class="{if $controller->is_device}DeviceModeDevice{/if} {if $controller->is_tablet}DeviceModeTablet{/if} {if $controller->is_phone}DeviceModePhone{/if}">
    <div class="FrontLayoutPageOuter">
        <div class="FrontLayoutPageHeader">
            {include "./header_content.tpl}"
        </div>
        <div class="FrontLayoutPageContent">
            {display_page_content}
        </div>
        <div class="FrontLayoutPageFooter BeforeFooterOffset">
            <div class="FrontLayoutPageFooterInner">
                {menu alias='footer' template='footer_menu'}
            </div>
            {content_block alias='copyright'}
        </div>
    </div>
    {include './infographics.tpl'}
    {include './global_loader.tpl'}
</body>
</html>
