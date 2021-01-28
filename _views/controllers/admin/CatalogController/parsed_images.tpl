<div class="AdminLayoutPageContentContent {$controller->MC}MainWrapper" id="{$controller->MC}APP">
    <div class="CommonAdminLayoutForm">
        <a href="#" id="{$controller->MC}link">start</a>
        <div>
            <textarea id="{$controller->MC}log" style="min-height: 20em;width:100%;"></textarea>
        </div>
    </div>
</div>
<script>

    (function () {
        var CMP = "{$controller->MC}";
    {literal}
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};

            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;

                var runned = false;
                var log = jQuery(['#', CMP, 'log'].join(''));
                jQuery(["#", CMP, 'link'].join('')).on('click', function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    if (!runned) {
                        run();
                    }
                });
                function run() {
                    runned = true;
                    log.val('');
                    jQuery.getJSON('/admin/Catalog/API', {action: "UpdateImagesFromParser", offset: 0})
                            .done(run_responce)
                            .fail(run_fail);
                }

                function logg(x) {
                    log.val([log.val(), x].join("\n"));
                    log.scrollTop(Number.MAX_SAFE_INTEGER);
                }
                function run_responce(d) {
                    if (U.isObject(d)) {
                        if (d.status === "ok") {
                            logg(d.log);
                            if (d.action === "redirect") {
                                jQuery.getJSON('/admin/Catalog/API', {action: "UpdateImagesFromParser", offset: d.next_offset,records_to_check:d.records_to_check})
                                        .done(run_responce)
                                        .fail(run_fail);
                                return;
                            } else if (d.sction === "done") {
                                logg("done");
                                runned = false;
                                return;
                            }
                        }
                        if (d.status === "error") {
                            logg(d.error_info.message);
                            runned = false;
                            return;
                        }
                    }
                    logg("invalid server responce");
                    runned = false;
                    return;
                }

                function run_fail() {
                    logg("network error");
                    runned = false;
                }

            });
    {/literal}
        })();
</script>