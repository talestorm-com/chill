<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                window.Eve = window.Eve || {};
                window.Eve.EFO = window.Eve.EFO || {};
                window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                window.Eve.EFO.Ready.push(function () {
                    window.Eve.EFO.Events.GEM().on("SYS_LOGIN_SUCCESS", window, function () {
                        window.location.reload(true);
                    });
                    window.Eve.EFO.Com().load('front.login_form').done(function (x) {
                        x.show();
                    });
                });
            });
        })();
    {/literal}
</script>