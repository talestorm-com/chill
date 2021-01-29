{$OUT->add_css('/assets/chill/css/lk_login.css',1000)|void}
{literal}
    <script>
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                EFO.Events.GEM().on('LOGIN_SUCCESS', window, function () {
                    window.location.reload();
                });
                check_ready();
                function check_ready() {
                    if (U.isCallable(window.run_authorization_sequence)) {
                        window.run_authorization_sequence();
                    } else {
                        window.setTimeout(check_ready, 100);
                    }
                }
            });
        })();
    </script>
{/literal}