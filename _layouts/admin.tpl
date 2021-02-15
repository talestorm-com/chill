{include './header.tpl'}
</head>
<body>
    <div class="AdminLayoutBodyInner">
        <div class="AdminLayoutMainMenuWrapper">{include './admin_menu.tpl'}</div>
        <div class="AdminLayoutPageContentWrapper">
            {$OUT->get('page_content')}
        </div>
        <div class="AdminLayoutFooter">
            <div class="AdminLayoutFooterInner">
                Development & support by <a href="https://inclu.work/" target="_blank">FRONTON&TRADE;</a> {$controller->get_current_year()}
            </div>
        </div>
    </div>
</body>
</html>
