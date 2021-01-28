{$OUT->add_css('/assets/chill/css/trailer_player_eve.css',100)|void}
{assign var="trailer_player_uuid" value="a{$OUT->get_euid('trailer_player')}"}
<div class="trailer_backdrop" id="{$trailer_player_uuid}" style="display:none">
            <div class="trailer-close-btn"><div class="trailer-close-btn-inner"></div></div>

<div class="go_to_cinema">
<a>Перейти к сериалу</a>
</div>
    <div class="trailer-backdrop-flex">
        <div class="trailer-window">
            <div class="trailer-content">
                <!-- <div class="trailers-list">
                    <div class="trailers-list-content" id="{$trailer_player_uuid}list">
                    </div>
                </div> -->
                <div class="trailer-video">
                    <div class="trailer-video-content">
                        <video id="{$trailer_player_uuid}video"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="template/mustache" id="{$trailer_player_uuid}_template">
    {literal}<div class="trailer-player-preview-list-item" data-index="{{index}}" title="{{title}}"><div class="trailer-player-preview-list-item-inner"><img src="{{poster}}" /></div></div>{/literal}
</script>
{literal}
    <script>
                (function () {



                    var IDP = '{/literal}{$trailer_player_uuid}{literal}';
                    window.Eve = window.Eve || {};
                    window.Eve.EFO = window.Eve.EFO || {};
                    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                    window.Eve.EFO.Ready.push(jQuery(ready));
                    function ready() {
                        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
                        var handle = jQuery(['#', IDP].join(''));
                        var list = jQuery(['#', IDP, 'list'].join(''));
                        var player_node = jQuery(['#', IDP, 'video'].join(''));
                        var player = new Plyr(player_node.get(0), {
                            debug: true
                        });
                        
                        var current_trailer_list = [];
                        var o = {
                            show: function () {
                                handle.appendTo('body');
                                handle.show();
                                jQuery('html').addClass('TrailerPlayerVisibleNow');
                                
                            },
                            hide: function () {
                                try {
                                    player.stop();
                                } catch (e) {

                                }
                                handle.hide();
                                jQuery('html').removeClass('TrailerPlayerVisibleNow');
                            },
                            parse_trailer: function (x) {
                                try {
                                    x = U.safeObject(x);
                                    var ro = {
                                        id: U.IntMoreOr(x.id, 0, null),
                                        title: U.NEString(x.name, 0, null),
                                        posters: [],
                                        videos: [],
                                        valid: false
                                    };
                                    if (U.NEString(x.default_poster, null)) {
                                        ro.posters.push(["/media/media_content_trailer/", ro.id, "/", x.default_poster, '.SW_1200CF_1PR_hposter.jpg'].join(''));
                                    }
                                    var files = U.safeArray(x.files);
                                    for (var i = 0; i < files.length; i++) {
                                        try {
                                            var file = U.safeObject(files[i]);
                                            var info = U.safeObject(JSON.parse(U.NEString(file.info, '{}')));
                                            if (U.NEString(file.cdn_id, null) && U.NEString(file.content_type, null) && U.NEString(file.size, null) && U.IntMoreOr(file.size, 0, null) && U.NEString(info.id, null) && U.NEString(info.id, null) === U.NEString(file.cdn_id, null)) {
                                                var posters = U.safeArray(info.previews);
                                                for (var pc = 0; pc < posters.length; pc++) {
                                                    var pp = U.NEString(posters[pc], null);
                                                    if (pp) {
                                                        ro.posters.push("//" + pp);
                                                    }
                                                }
                                                var video = U.NEString(info.cdn_url, null);
                                                if (video) {
                                                    ro.videos.push({
                                                        content_type: U.NEString(file.content_type, null),
                                                        size: U.IntMoreOr(file.size, 0, null),
                                                        url: "//" + video
                                                    })
                                                }
                                            }
                                        } catch (ee) {

                                        }
                                    }
                                    if (ro.posters.length) {
                                        ro.poster = ro.posters[0];
                                    } else {
                                        ro.poster = null;
                                    }
                                    if (ro.poster && ro.id && ro.videos.length) {
                                        ro.valid = true;
                                        return ro;
                                    }

                                } catch (e) {

                                }
                                return null;
                            },
                            setup_trailers_ctSEASON: function (x) {
                                var trailer_list = [];
                                var it = U.safeArray(x.trailers);
                                for (var i = 0; i < it.length; i++) {
                                    var trailer = this.parse_trailer(it[i]);
                                    if (trailer && trailer.valid) {
                                        trailer_list.push(trailer);
                                    }
                                }
                                var sl = U.safeArray(x.seasons);
                                for (var l = 0; l < sl.length; l++) {
                                    var season = U.safeObject(sl[l]);
                                    var trailers = U.safeArray(season.trailers);
                                    for (var i = 0; i < trailers.length; i++) {
                                        var trailer = this.parse_trailer(trailers[i]);
                                        if (trailer && trailer.valid) {
                                            trailer_list.push(trailer);
                                        }
                                    }
                                }
                                if (trailer_list.length) {
                                    this.setup_trailer_list(trailer_list);
                                }
                            },
                            setup_trailers_ctTRAILER: function (x) {
                                var id = U.IntMoreOr(x.id, 0, null);
                                if (id) {
                                    var self = this;
                                    jQuery.getJSON('/Info/API', {action: "get_trailer_data", id: id})
                                            .done(function (data) {
                                                if (U.isObject(data)) {
                                                    if (data.status === 'ok') {
                                                        if (U.isObject(data.trailer_data)) {                                                            
                                                            self.setup_trailer_list([self.parse_trailer(data.trailer_data)]);
                                                        }
                                                    }
                                                }
                                            });
                                }
                            },
                            setup_trailer_list: function (xa) {
                                try {
                                    var xa = U.safeArray(xa);
                                    if (xa.length) {
                                        for (var i = 0; i < xa.length; i++) {
                                            xa[i].index = i;
                                        }
                                        current_trailer_list = xa;
                                        if (xa.length === 1) {
                                            list.hide();
                                        } else {
                                            list.show();
                                        }
                                        list.html(Mustache.render(document.getElementById([IDP, '_template'].join('')).innerHTML, {list: current_trailer_list}));
                                        this.setup_trailer_index(0);
                                    } else {
                                        this.hide();
                                    }
                                } catch (e) {
                                    U.TError(e);
                                }
                                return this;
                            },

                            setup_trailer_index: function (x) {
                                x = U.IntMoreOr(x, 0, 0);
                                if (U.isArray(current_trailer_list) && current_trailer_list.length > x) {
                                    var trailer = current_trailer_list[x];
                                    var sources = [];
                                    for (var i = 0; i < trailer.videos.length; i++) {
                                        sources.push({
                                            src: "//" + trailer.videos[i].url,
                                            type: trailer.videos[i].content_type,
                                            size: trailer.videos[i].size
                                        });
                                    }
                                    player.source = {
                                        type: 'video',
                                        title: trailer.title,
                                        sources: sources,
                                        poster: trailer.poster
                                    };
                                    this.show();
                                    player.play();
                                }
                            }
                        };


                    

                        function setup_trailers(x) {
                            var ct = U.NEString(U.safeObject(x).content_type, null);
                            if (ct) {
                                var fn = "setup_trailers_" + ct;
                                if (U.isCallable(o[fn])) {
                                    o[fn](x);
                                }
                            }
                        }

                        handle.on('click', '.trailer-player-preview-list-item', function (e) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                            o.setup_trailer_index(U.IntMoreOr(jQuery(this).data('index'), 0, 0));
                        });

                        handle.on('click', '.trailer-close-btn', function (e) {
                            e.stopPropagation();
                            e.preventDefault ? e.preventDefault() : e.returnValue = false;
                            o.hide();
                        });


                        window.run_trailers_player = function (x) {
                            setup_trailers(x);
                        };

                    }
                })();
               
    </script>
{/literal}