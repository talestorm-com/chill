<script>
    {literal}
        (function () {
            window.Eve = window.Eve || {};
            window.Eve.EFO = window.Eve.EFO || {};
            window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
            window.Eve.EFO.Ready.push(function () {
                var Y = window.Eve.EFO.Com();
                window.Eve.EFO.Promise.waitForArray([
                    Y.js("https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.js"),
                    Y.css("https://api.tiles.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.css"),
                    Y.js("https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-language/v0.10.1/mapbox-gl-language.js")
                ]).done(function () {
                    mapboxgl.accessToken = '{/literal}{$this->api_key}{literal}';
                    {/literal}{include "./{$this->template}.tpl"}{literal}
                });
            });
        })();
    {/literal}
</script>