{if 0==1}<script>{/if}{literal}
    (function () {
        var EFO = window.Eve.EFO, U = EFO.U;
        var container = U.NEString('{/literal}{$this->params->get_filtered('container',["Strip","Trim","NEString","DefaultNull"])}{literal}', null);

        var zoom = U.IntMoreOr('{/literal}{$this->params->get_filtered('zoom',["IntMore0",'DefaultNull'])}{literal}', 14);
        var marker_color = U.NEString('{/literal}{$this->params->get_filtered('marker_color',["Strip","Trim","NEString","DefaultNull"])}{literal}', "#ff0000");
        var event_id = U.NEString('{/literal}{$this->params->get_filtered('event_id',["Strip","Trim","NEString","DefaultNull"])}{literal}', null);
        var marker_lat = U.FloatOr('{/literal}{$this->params->get_filtered('_lat',["Float",'DefaultNull'])}{literal}', null);
        var marker_lon = U.FloatOr('{/literal}{$this->params->get_filtered('_lon',["Float",'DefaultNull'])}{literal}', null);
        var lat = U.FloatOr('{/literal}{$this->params->get_filtered('center_lat',["Float",'DefaultNull'])}{literal}', U.FloatOr(marker_lon, 55.7516));
        var lon = U.FloatOr('{/literal}{$this->params->get_filtered('center_lon',["Float",'DefaultNull'])}{literal}', U.FloatOr(marker_lat, 37.61973));
        var marker = null;
        if (container) {
            var map = new mapboxgl.Map({
                container: container,
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [lon, lat],
                zoom: zoom
            });
            function create_marker(la, lo) {
                marker = new mapboxgl.Marker({
                    draggable: true,
                    color: marker_color
                }).setLngLat([la, lo])
                        .addTo(map);
                marker.on('dragend', onDragEnd);
            }
            if (marker_lat !== null && marker_lon !== null) {                
                create_marker(marker_lat, marker_lon);
            }

            function onDragEnd() {

                var lngLat = marker.getLngLat();
                if (event_id) {
                    window.Eve.EFO.Events.GEM().run(event_id, {lat: lngLat.lat, lon: lngLat.lng});
                }
            }


            map.on('click', function (e) {
                if (!marker) {
                    create_marker(e.lngLat.lng, e.lngLat.lat);
                } else {
                    marker.setLngLat([e.lngLat.lng, e.lngLat.lat]).addTo(map);
                }
                onDragEnd();
            });
            map.setZoom(14);
        }

    })();
    {/literal}
    {if 0==1}</script>{/if} 