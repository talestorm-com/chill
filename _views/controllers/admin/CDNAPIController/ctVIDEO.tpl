<div class="{$controller->MC}FormInner">
    <div class="{$controller->MC}FileSelector">
        <div class="{$controller->MC}FileSelectorInner">
            <input type="file" accept="video/*" name="file" />
            <div class="{$controller->MC}FileSelectorInnerText">
                Перетащите файл на эту площадку или нажмите чтобы выбрать.
            </div>
            <div class="{$controller->MC}FileSelectorDisabler"></div>
        </div>
    </div>
    <div class="{$controller->MC}CheckWrapper">
        <div class="{$controller->MC}CheckRow">
            <input type="checkbox" id="{$controller->MC}autoenable" checked="checked" />
            <label for="{$controller->MC}autoenable">Автоматически включить по завершении</label>
        </div>
        <div class="{$controller->MC}CheckRow">
            <input type="checkbox" id="{$controller->MC}autoclose" checked="checked" />
            <label for="{$controller->MC}autoclose">Закрыть лоадер по завершении</label>
        </div>
    </div>
    <div class="{$controller->MC}progress-wrapper">
        <div class="{$controller->MC}progress-content">
            <div class="{$controller->MC}progress">
                <div class="{$controller->MC}progressscale"></div>
            </div>
        </div>
    </div>
</div>
{literal}
    <script>
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var MC = '{/literal}{$controller->MC}{literal}';
                var progress = jQuery('.' + MC + 'progressscale');
                var file = jQuery('.' + MC + 'FileSelectorInner input[type=file]');
                var disabler = jQuery('.' + MC + 'FileSelectorDisabler');
                var autoenable = jQuery('#' + MC + "autoenable");
                var autoclose = jQuery('#' + MC + "autoclose");
                file.on('change', function (e) {
                    if (U.NEString(jQuery(this).val(), null)) {
                        run();
                    }
                });
                function extract_name(x) {
                    var y = x.replace(/\\/g, '/').split('/');
                    return y[y.length - 1];
                }
                function do_done(r) {
                    debugger;
                }
                function do_error(r) {
                    debugger;
                }
                function do_progress(e) {
                    var total = U.IntMoreOr(e.total, 0, 0);
                    var loaded = U.IntMoreOr(e.loaded, 0, 0);
                    var pc1 = U.IntMoreOr(e.total / 100, 0, 1);
                    var pc_result = (loaded / pc1).toFixed(5);
                    progress.css({"width": pc_result + "%"});
                }
                function run() {
                    disabler.show();
                    console.log(disabler.outerWidth(true));
                    disabler.addClass('active');
                    var formdata = new FormData();
                    var name = extract_name(file.val());
                    formdata.append("name", "/VIDEO/{/literal}{$content_id}{literal}/" + name);
                    formdata.append("file", file.get(0).files[0]);
                    formdata.append("private", "true");
                    formdata.append("autoencoding", "true");
                    formdata.append("del_original", "true");
                    jQuery.getJSON('/admin/CDNAPI/API', {action: "request_url"})
                            .done(function (d) {
                                var request = new XMLHttpRequest();
                                request.onload = function () {
                                    do_done(request);
                                };

                                request.onerror = function () {
                                    do_error(request);
                                };

                                request.onprogress = function (event) { // запускается периодически
                                    do_progress(event);
                                };
                                request.upload.onprogress = function(e){
                                    do_progress(event);
                                };
                                request.open("POST", d.url);
                                request.send(formdata);
                            });
                }
            });
        })();
    </script>
{/literal}