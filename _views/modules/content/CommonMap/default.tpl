{if 0==1}<script>{/if}{literal}
    (function () {
        var U = window.Eve.EFO.U;
        var container = U.NEString('{/literal}{$this->params->get_filtered('container',["Strip","Trim","NEString","DefaultNull"])}{literal}', null);
        var lat = U.FloatOr('{/literal}{$this->params->get_filtered('center_lat',["Float",'DefaultNull'])}{literal}', 57.0);
        var lon = U.FloatOr('{/literal}{$this->params->get_filtered('center_lon',["Float",'DefaultNull'])}{literal}', 37.0);
        var zoom = U.IntMoreOr('{/literal}{$this->params->get_filtered('zoom',["IntMore0",'DefaultNull'])}{literal}', 10);
        var show_marker = U.anyBool('{/literal}{$this->params->get_filtered('show_marker',["IntMore0",'Default0'])}{literal}', false);
        if (container) {
            var map = new mapboxgl.Map({
                container: container,
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [lat, lon],
                zoom: zoom
            });
            if (show_marker) {
                var marker_color=U.NEString('{/literal}{$this->params->get_filtered('marker_color',["Strip","Trim","NEString","DefaultNull"])}{literal}', "#ff0000");
                var marker = new mapboxgl.Marker({
                    draggable: false,
                    color:marker_color
                })
                        .setLngLat([lat, lon])
                        .addTo(map);
            }
        }
        console.log(zoom);
    })();
    {/literal}
    {if 0==1}</script>{/if}