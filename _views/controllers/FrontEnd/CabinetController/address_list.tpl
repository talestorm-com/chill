<div class="{$controller->MC}ContentContent">
    <div class="{$controller->MC}Header">Адреса доставки</div>
    <div class="{$controller->MC}Addresses">        
        <table>
            <thead>
                <tr>
                    <th class="{$controller->MC}addresslistcellpp">ПП</th>
                    <th class="{$controller->MC}addresslistcelllabel">Метка</th>
                    <th class="{$controller->MC}addresslistcelladdress">Адрес</th>                    
                    <th class="{$controller->MC}addresslistcellcontrol"></th>
                </tr>
            </thead>
            <tbody>     
                {if count($items)}
                    {assign var='acounter' value=0}
                    {foreach $items as $item}                    
                        {assign var='acounter' value=$acounter+1}
                        {include "./address_row.tpl"}
                    {/foreach}
                {else}
                    <tr class="{$controller->MC}address_list_empty"><td colspan="4">Вы пока не добавляли адресов доставки</td></tr>
                {/if}
            </tbody>
        </table>
        <div class="{$controller->MC}_add_new_address_wrap">
            <a href="/Cabinet/AddressEdit?n=1&i=">Добавить новый адрес</a>
        </div>

    </div>    
</div>
<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var key = '{/literal}{$controller->MC}{literal}';
                var handle = jQuery(['.', key, 'Addresses'].join(''));

                handle.on('click', ['.', key, 'addresslistremovebth'].join(''), function (e) {
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    e.stopPropagation();
                    var id = U.NEString(jQuery(this).data('id'), null);
                    if (id) {
                        try {
                            window.show_global_loader();
                        } catch (e) {

                        }
                        EFO.simple_confirm()
                                .set_style("blue")
                                .set_icon("?")
                                .set_image("?")
                                .set_title("Подтверждение")
                                .set_text("Удалить адрес доставки?")
                                .set_close_btn(true)
                                .set_buttons(["Отмена", "Удалить"])
                                .set_callback(window, function (x, y, z) {
                                    if (y === 2) {
                                        jQuery.getJSON('/Cabinet/API', {action: "remove_address", uid: id})
                                                .done(on_remove_ok)
                                                .fail(on_remove_fail)
                                                .always(function () {
                                                    try {
                                                        window.hide_global_loader();
                                                    } catch (e) {

                                                    }
                                                });
                                    }
                                })
                                .show();

                    }
                });


                function on_remove_fail(x) {
                    U.TError(U.NEString(x, "Ошибка"));
                }

                function on_remove_ok(d) {
                    if (U.isObject(d) && d.status === 'ok') {
                        window.location.reload(true);
                        return;
                    }
                    if (U.isObject(d) && d.status === 'error') {
                        if (d.error_info.message === 'login_required') {
                            window.location.reload(true);
                            return;
                        }
                        on_remove_fail(d.error_info.message);
                        return;
                    }
                    on_remove_fail();
                }

                handle.on('click', 'table>tbody>tr', function (e) {
                    var id = U.NEString(jQuery(this).data('id'), null);
                    if (id) {
                        window.location.href = ("/Cabinet/AddressEdit?i=" + id);
                    }
                });
            });
        })();
    {/literal}
</script>