<div class="{$controller->MC}Filter">
    <div class="{$controller->MC}FilterInner">
        <label for="town_{$controller->MC}">Город</label>
        <div class="{$controller->MC}SelectWrapper">
            <select id="town_{$controller->MC}">
                <option value=""></option>
                {foreach $shops->townlist as $key=>$v}
                    <option value="{$key}">{$v}</option>
                {/foreach}
            </select>
            <div class="{$controller->MC}SelectPimp">
                <svg><use xlink:href="#offline_icon_play"/></svg>
            </div>
        </div>
    </div>
</div>
<div class="{$controller->MC}ShopList">
    {foreach $shops as $shop}
        {include "./offline_partner_item.tpl"}
    {/foreach}
</div>
<div style="display:none!important">
    {include "./arrow_down.svg"}
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
                var handle = jQuery(['.', key, 'ShopList'].join(''));
                var trigger = jQuery(['#town_', key].join(''));
                trigger.on('change', function (e) {
                    var v = U.NEString(trigger.val(), null);
                    if (v) {
                        handle.find(['.', key, 'offline_shop_item'].join('')).each(function () {
                            var t = jQuery(this);
                            var p = U.NEString(t.data('town'), null);
                            if (v === p) {
                                t.show();
                            } else {
                                t.hide();
                            }
                        });
                    } else {
                        handle.find(['.', key, 'offline_shop_item'].join('')).show();
                    }
                });
                handle.on('click', '[data-command="on_map"]', function (e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    var id = U.IntMoreOr(jQuery(this).data('id'), 0, null);
                    if (id) {
                        try {
                            window.show_global_loader();
                        } catch (eee) {

                        }
                        EFO.Com().load('front.shop_map_view')
                                .done(window, function (x) {
                                    x.show().load(id);
                                })
                                .always(window, function () {
                                    try {
                                        window.hide_global_loader();
                                    } catch (eee) {

                                    }
                                });
                    }
                });
            });
        })();
    {/literal}
</script>