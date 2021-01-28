{$OUT->add_css("/assets/css/front/search.css", 0)|void}
<div class="{$controller->MC}Wrapper">
    <div class="{$controller->MC}Warning">
        Уважаемые посетители!<br>
        По техническим причинам поиск некоторое время может работать некорректно.<br>
        Приносим извинения за доставленные неудобства, мы постараемся решить эту проблему в кратчайшие сроки.
    </div>
<div id="ya-site-results" data-bem="{literal}{&quot;tld&quot;: &quot;ru&quot;,&quot;language&quot;: &quot;ru&quot;,&quot;encoding&quot;: &quot;utf-8&quot;,&quot;htmlcss&quot;: &quot;1.x&quot;,&quot;updatehash&quot;: true}{/literal}">
</div>
{literal}
<script type="text/javascript">
    (function (w, d, c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0];s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Results.init();})})(window, document, 'yandex_site_callbacks');
</script>
{/literal}
</div>
