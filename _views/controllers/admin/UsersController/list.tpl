<div class="AdminLayoutPageContentContent {$controller->MC}MainWrapper" id="{$controller->MC}APP">
    {include {$controller->common_templtes("preloader")}}
</div>
<script>
    (function () {
        var CMP = "{$controller->MC}APP";
    {literal}
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                EFO.Com().load("desktop.users").done(window, function (x) {
                    document.getElementById(CMP).innerHTML = '';
                    x.install(CMP);
                }).fail(window, function () {
                    document.getElementById(CMP).innerHTML = "component load error";
                });
            });
    {/literal}
        })();
</script>