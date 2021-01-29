<div class="{$controller->MC}Map" id="a{$controller->MC}map"></div>
<div class="{$controller->MC}ShopList">
    {foreach $shops as $shop}
        {include "./offline_shop_item.tpl"}
    {/foreach}
</div>
<script>
    {literal}
        (function () {
            var points = {/literal}{$marshaled_shops|json_encode}{literal};
            var cis = "{/literal}{$controller->MC}{literal}";
            var fn_name = ["marks_", cis].join('');
            var cb_name = ["callback_", cis].join('');
            var map = null;
            window[fn_name] = function () {
                return points;
            };
            window[cb_name] = function (x) {
                map = x;
            };

            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                
            });
        })();
    {/literal}
</script>
{common_map container="a{$controller->MC}map" template="marks_from_fn" markers_fn="marks_{$controller->MC}" map_callback_fn="callback_{$controller->MC}" show_markers=1 marker_color="#FF0000"}