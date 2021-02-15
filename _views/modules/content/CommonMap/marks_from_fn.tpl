{if 0==1}<script>{/if}{literal}
    (function () {
        var EFO = window.Eve.EFO, U = EFO.U;
        var container = U.NEString('{/literal}{$this->params->get_filtered('container',["Strip","Trim","NEString","DefaultNull"])}{literal}', null);
        var lat = U.FloatOr('{/literal}{$this->params->get_filtered('center_lat',["Float",'DefaultNull'])}{literal}', 57.0);
        var lon = U.FloatOr('{/literal}{$this->params->get_filtered('center_lon',["Float",'DefaultNull'])}{literal}', 37.0);
        var zoom = U.IntMoreOr('{/literal}{$this->params->get_filtered('zoom',["IntMore0",'DefaultNull'])}{literal}', 10);
        var markers_fn = U.NEString('{/literal}{$this->params->get_filtered('markers_fn',["Trim","NEString",'DefaultNull'])}{literal}', null);
        var map_callback_fn = U.NEString('{/literal}{$this->params->get_filtered('map_callback',["Trim","NEString",'DefaultNull'])}{literal}', null);
        var show_markers = U.anyBool('{/literal}{$this->params->get_filtered('show_markers',["IntMore0",'Default0'])}{literal}', false);
        var marker_color = U.NEString('{/literal}{$this->params->get_filtered('marker_color',["Strip","Trim","NEString","DefaultNull"])}{literal}', "#ff0000");
        var TEMPLATES = {/literal}{$this->create_front_templates('F')}{literal};
        var markers = {};
        if (container) {
            var map = new mapboxgl.Map({
                container: container,
                style: 'mapbox://styles/mapbox/streets-v10',
                center: [lat, lon],
                zoom: zoom
            });
            var lang = new MapboxLanguage({
                defaultLanguage: 'ru'
            });
            map.addControl(lang);

            if (map_callback_fn && U.isCallable(window[map_callback_fn])) {
                window[map_callback_fn](map, null);
            }

            if (show_markers && markers_fn && U.isCallable(window[markers_fn])) {
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
                        markers[alias]._xm_props.xob.stripped_phone = U.NEString(U.NEString(markers[alias]._xm_props.xob.phone, '').replace(/\D/gi, ''), null);
                        markers[alias]._xm_props.xob.stripped_phone=markers[alias]._xm_props.xob.stripped_phone?["+",markers[alias]._xm_props.xob.stripped_phone].join(''):null;
                        markers[alias]._xm_props.xob.formatted_phone = markers[alias]._xm_props.xob.stripped_phone?EFO.Checks.formatPhone(markers[alias]._xm_props.xob.stripped_phone):null;
                        markers[alias]._xm_props.xob.show_phone=markers[alias]._xm_props.xob.stripped_phone?true:false;                        
                        markers[alias]._xm_props.xob.address = U.NEString(markers[alias]._xm_props.xob.address,'').replace(/\n/g,'<br>').replace(/\r/gi,'').replace(/\|/g,'<br>');
                        popup.setHTML(Mustache.render(TEMPLATES.popup_html, markers[alias]._xm_props.xob));
                        popup.addTo(map);
                    }
                }
                try {
                    var items = window[markers_fn]();
                    if (U.isArray(items) && items.length) {
                        var items = U.safeArray(items);
                        var bounds = new mapboxgl.LngLatBounds();
                        if (items && items.length) {
                            for (var i = 0; i < items.length; i++) {
                                var mi = items[i];
                                /*
                                 address: "Улица тимирязевская д5"
                                 email: "aaa@bbb.cc"                                
                                 lat: 37.563635
                                 lon: 55.818486
                                 name: "Магазин на тимирязевской"
                                 phone: "+7 (888) 123 45 67"
                                 id:25
                                 
                                 */

                                var lat = U.FloatOr(mi.lat, null);
                                var lon = U.FloatOr(mi.lon, null);
                                var title = U.NEString(mi.name, null);
                                var address = U.NEString(mi.address, null);
                                var id = U.IntMoreOr(mi.id, 0, null);
                                if (lat != null && lon != null && title != null && address != null && id) {
                                    var marker = new mapboxgl.Marker({
                                        draggable: false,
                                        color: marker_color,
                                    }).setLngLat([lat, lon])
                                            .addTo(map);

                                    marker._xm_props = {
                                        lat: lat, lon: lon, title: title, address: address, xob: mi
                                    };
                                    var alias = ["P", id].join('');
                                    markers[alias] = marker;
                                    jQuery(marker.getElement()).data('alias', alias);
                                    jQuery(marker.getElement()).on('click', on_click);
                                    bounds.extend(marker.getLngLat());
                                }
                            }
                            map.fitBounds(bounds, {padding: 50});
                            map.on('resize', function () {
                                map.fitBounds(bounds, {padding: 50});
                            });
                        }
                    }
                } catch (e) {

                }

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