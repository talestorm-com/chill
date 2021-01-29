{if 0==1}<script>{/if}{literal}
    (function () {
        var EFO = window.Eve.EFO, U = EFO.U;
        var container = U.NEString('{/literal}{$this->params->get_filtered('container',["Strip","Trim","NEString","DefaultNull"])}{literal}', null);
        var lat = U.FloatOr('{/literal}{$this->params->get_filtered('center_lat',["Float",'DefaultNull'])}{literal}', 57.0);
        var lon = U.FloatOr('{/literal}{$this->params->get_filtered('center_lon',["Float",'DefaultNull'])}{literal}', 37.0);
        var zoom = U.IntMoreOr('{/literal}{$this->params->get_filtered('zoom',["IntMore0",'DefaultNull'])}{literal}', 10);
        var markers_url = U.NEString('{/literal}{$this->params->get_filtered('markers_url',["Trim","NEString",'DefaultNull'])}{literal}', null);
        var show_markers = U.anyBool('{/literal}{$this->params->get_filtered('show_markers',["IntMore0",'Default0'])}{literal}', false);
        var marker_color = U.NEString('{/literal}{$this->params->get_filtered('marker_color',["Strip","Trim","NEString","DefaultNull"])}{literal}', "#ff0000");
        var TEMPLATES = {/literal}{$this->create_front_templates('F')}{literal};
        var markers = {};
        if (container) {
            var map = new mapboxgl.Map({
                container: container,
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [lat, lon],
                zoom: zoom
            });
            if (show_markers && markers_url) {
                var popup = new mapboxgl.Popup({
                    closeButton: true, closeOnClick: true,
                    className: "xmxxx_mapbox_gl_pop"
                });

                function pf(n, d) {
                    var dec = U.IntMoreOr(d, -1, 2);
                    var val = parseFloat(EFO.Checks.prepareFloat(n));
                    val = typeof val === 'number' && !isNaN(val) ? val : 0;
                    var e = val.toFixed(dec).toString()
                            , ae = e.split('.')
                            , r = [ae[0].replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,')];
                    return [r].join('.');

                }

                function on_click(e) {
                    e.stopPropagation();
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;
                    var alias = U.NEString(jQuery(this).data('alias'), null);
                    if (alias && U.isObject(markers[alias]) && U.isObject(markers[alias]._xm_props)) {
                        popup.setLngLat(markers[alias].getLngLat());
                        markers[alias]._xm_props.xob.has_image = U.NEString(markers[alias]._xm_props.xob.default_image, null) ? true : false;
                        markers[alias]._xm_props.xob.formated_cost = pf(markers[alias]._xm_props.xob.cost,0);
                        markers[alias]._xm_props.xob.formated_ys = pf(markers[alias]._xm_props.xob.yardage_sqft,2);
                        popup.setHTML(Mustache.render(TEMPLATES.popup_html, markers[alias]._xm_props.xob));
                        popup.addTo(map);
                    }
                }
                jQuery.getJSON(markers_url)
                        .done(function (d) {
                            if (d.status === "ok") {
                                var items = U.safeArray(d.items);
                                var bounds = new mapboxgl.LngLatBounds();
                                if (items && items.length) {
                                    for (var i = 0; i < items.length; i++) {
                                        var mi = items[i];
                                        var lat = U.FloatOr(mi.lat, null);
                                        var lon = U.FloatOr(mi.lon, null);
                                        var title = U.NEString(mi.name, null);
                                        var default_image = U.NEString(mi.default_image, null);
                                        var address = U.NEString(mi.address, null);
                                        var alias = U.NEString(mi.alias, null);
                                        if (lat != null && lon != null && title != null && address != null && alias != null) {
                                            var marker = new mapboxgl.Marker({
                                                draggable: false,
                                                color: marker_color,
                                            }).setLngLat([lat, lon])
                                                    .addTo(map);

                                            marker._xm_props = {
                                                lat: lat, lon: lon, title: title, image: default_image, address: address, alias: alias, xob: mi
                                            };
                                            markers[alias] = marker;
                                            jQuery(marker.getElement()).data('alias', alias);
                                            jQuery(marker.getElement()).on('click', on_click);
                                            bounds.extend(marker.getLngLat());
                                        }
                                    }
                                    map.fitBounds(bounds, {padding: 50});
                                }
                            }
                        });

                /*var marker = new mapboxgl.Marker({
                 draggable: false,
                 color: marker_color
                 })
                 .setLngLat([lat, lon])
                 .addTo(map);*/
            }
        }
        console.log(zoom);
    })();
    {/literal}
    {if 0==1}</script>{/if}