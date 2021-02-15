{$OUT->add_css('/assets/css/layout.css',0)|void}
{$OUT->add_css('/assets/css/efo.css',0)|void}
{$OUT->add_script('/assets/js/efo.js',0,true)|void}
{include './header.tpl'}
</head>
<body>
    {$OUT->get('page_content')}
</body>
</html>
