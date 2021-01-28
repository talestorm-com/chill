<?php
/* Smarty version 3.1.33, created on 2020-11-06 20:35:59
  from '/var/VHOSTS/site/_views/modules/content/MediaContentObject/trailer_player.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fa5897fc45b49_26053638',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '468fce3a96ef144145679de0245a2a260c9ce114' => 
    array (
      0 => '/var/VHOSTS/site/_views/modules/content/MediaContentObject/trailer_player.tpl',
      1 => 1604684158,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fa5897fc45b49_26053638 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/modifier.void.php','function'=>'smarty_modifier_void',),));
echo smarty_modifier_void($_smarty_tpl->tpl_vars['OUT']->value->add_css('/assets/chill/css/trailer_player_eve.css',100));?>

<?php $_smarty_tpl->_assignInScope('trailer_player_uuid', "a".((string)$_smarty_tpl->tpl_vars['OUT']->value->get_euid('trailer_player')));?>
<div class="trailer_backdrop" id="<?php echo $_smarty_tpl->tpl_vars['trailer_player_uuid']->value;?>
" style="display:none">
    <div class="trailer-close-btn"><i class="mdi mdi-close"></i></div>

    
    <div class="trailer-backdrop-flex">
        <div class="trailer-window">
            <div class="trailer-content">
                <!-- <div class="trailers-list">
                    <div class="trailers-list-content" id="<?php echo $_smarty_tpl->tpl_vars['trailer_player_uuid']->value;?>
list">
                    </div>
                </div> -->
                <div class="trailer-video">
                    <div class="trailer-video-content">

                        <video id="<?php echo $_smarty_tpl->tpl_vars['trailer_player_uuid']->value;?>
video"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
 language="template/mustache" id="<?php echo $_smarty_tpl->tpl_vars['trailer_player_uuid']->value;?>
_template">
    <div class="trailer-player-preview-list-item" data-index="{{index}}" title="{{title}}"><div class="trailer-player-preview-list-item-inner"><img src="{{poster}}" /></div></div>
<?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
>
                (function () {


                    var HLS_OPEN_SRC_T = '<?php echo $_smarty_tpl->tpl_vars['controller']->value->get_preference('HLS_TEMPLATE_O',null);?>
';
                    var IDP = '<?php echo $_smarty_tpl->tpl_vars['trailer_player_uuid']->value;?>
';
                    window.Eve = window.Eve || {};
                    window.Eve.EFO = window.Eve.EFO || {};
                    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                    window.Eve.EFO.Ready.push(function () {
                        jQuery(ready);
                    });
                    function ready() {
                        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
                        var handle = jQuery(['#', IDP].join(''));
                        var list = jQuery(['#', IDP, 'list'].join(''));
                        var player_node = jQuery(['#', IDP, 'video'].join(''));
                        var player = new Plyr(player_node.get(0), {
                            //debug: true;
                            i18n: {
                                speed: 'Скорость',
                                normal: 'Нормальная'
                            }
                        });
                        var current_trailer_is_vertical = false;

                        function check_need_show_fuck() {
                            try {
                                if (U.isMobile()) {//если мобилька
                                    if (player.playing) { // если плеер играет
                                        if (current_trailer_is_vertical) { // если есть флаг vertical
                                            if (window.innerWidth > window.innerHeight) {// если горизонтальный
                                                jQuery('#bg_bg_vert').show(); //показать стаб
                                                try {
                                                    player.fullscreen.exit(); //выбить плеер из фуллскрина
                                                } catch (ee) {

                                                }
                                                return; // и выйти
                                            }
                                        }
                                    }
                                }
                            } catch (e) {

                            }
                            jQuery('#bg_bg_vert').hide(); // скрыть стаб
                        }
                        function go_to_ass_film() {
                        

                            var aaa = U.NEString($(".go_to_cinema a").attr("href"), null);
                            if (aaa) {
                                window.location.href = aaa+'#login_open';
                            }
                        }
                        player.on('playing', check_need_show_fuck);
                        player.on('pause', check_need_show_fuck);
                        player.on('ended', go_to_ass_film);
                        window.addEventListener('resize', check_need_show_fuck);
                        window.addEventListener('orientationchanged', check_need_show_fuck);



                        var current_trailer_list = [];
                        var o = {
                            show: function () {
                                handle.appendTo('body');
                                handle.show();
                                jQuery('html').addClass('TrailerPlayerVisibleNow');
                                $('.plyr__video-wrapper').append('<div id="logo_in_tr"><img src="/assets/chill/images/logo_grad.png"></div>');
                                var aaa = window.location.pathname;
                                if (aaa === '/' || aaa === ''){
                                $('.plyr__video-wrapper').append('<div class="go_to_cinema" style="display:block"><a class="promo-btn" href="">Зарегистрируйся на CHILL, смотри следующие серии</a></div>');
                                }
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
                                        valid: false,
                                        vertical: false
                                    };
                                    if (U.NEString(x.default_poster, null)) {
                                        ro.posters.push(["/media/media_content_trailer/", ro.id, "/", x.default_poster, '.SW_1200CF_1PR_hposter.jpg'].join(''));
                                    }
                                    ro.vertical = U.anyBool(x.vertical, false);
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
                                                        url: "//" + video,
                                                        cdn_id: U.NEString(file.cdn_id, null)
                                                    });
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
                                    trailer_list = this.prepare_trailer_players_soap(trailer_list);
                                    // this.setup_trailer_list(trailer_list);
                                }
                            },
                            prepare_trailer_players_soap: function (trailer_list) {
                                var group_id_block = {};
                                for (var i = 0; i < trailer_list.length; i++) {
                                    var key = ["A", trailer_list[i].id].join('');
                                    group_id_block[key] = U.safeArray(group_id_block[key]);
                                    for (var j = 0; j < trailer_list[i].videos.length; j++) {
                                        group_id_block[key].push(trailer_list[i].videos[j].cdn_id);
                                    }
                                }
                                var self = this;
                                jQuery.getJSON("/Info/API", {action: "players_ids", vilg: group_id_block})
                                        .done(function (d) {
                                            if (d.status === 'ok') {
                                                var vu = U.safeObject(d.players_ids);
                                                for (var i = 0; i < trailer_list.length; i++) {
                                                    var key = ["A", trailer_list[i].id].join('');
                                                    if (vu.hasOwnProperty(key)) {
                                                        trailer_list[i].videos = [{url: ["//video.platformcraft.ru/vod/", vu[key], "/playlist.m3u8"].join(''), type: null, size: null}]
                                                    }
                                                }
                                                self.setup_trailer_list(trailer_list);
                                            }
                                        });
                                return trailer_list;
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
                            setup_trailers_ctRAWURL: function (x) {
                                var url = U.NEString(x.url);
                                this.setup_trailer_list([{videos: [{url: url, type: null, size: null}], title: U.NEString(x.title, ''), poster: null}]);
                            },
                            setup_trailers_ctRAWID: function (x) {
                                var id = U.NEString(x.url, null);
                                if (id) {
                                    var self = this;
                                    jQuery.getJSON('/Info/API', {action: 'player_id', vil: [id]}).done(function (d) {
                                        if (d.status === 'ok') {
                                            var player_id = U.NEString(d.player_id, null);
                                            if (player_id) {
                                                self.setup_trailer_list([{videos: [{url: ["//video.platformcraft.ru/vod/", player_id, "/playlist.m3u8"].join(''), type: null, size: null}], title: U.NEString(x.title, ''), poster: null}]);
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
                                    //U.TError(e);
                                }
                                return this;
                            },

                            prepare_hls_url: function (mp_url) {
                                if (/\.m3u8$/i.test(mp_url)) {
                                    return mp_url;
                                }
                                mp_url = U.NEString(mp_url, '');
                                var m = /kino-cache\.cdnvideo\.ru\/kinoteatr\/(.{1,})$/i.exec(mp_url);
                                if (m) {
                                    var path = m[1];
                                    var value = HLS_OPEN_SRC_T.replace('%PATH%', path);
                                    return value;
                                }
                                return null;
                            },
                            setup_trailer_index: function (x) {
                                try {
                                    if (U.isCallable(window.onerror)) {
                                        this.backport_error = window.onerror;
                                        window.onerror = null;
                                    }
                                    x = U.IntMoreOr(x, 0, 0);
                                    if (U.isArray(current_trailer_list) && current_trailer_list.length > x) {
                                        var trailer = current_trailer_list[x];
                                        var sources = [];
                                        var HLS = null;
                                        if (window.Hls && window.Hls.isSupported()) {
                                            if (window.Hls.is_native()) {
                                                for (var i = 0; i < trailer.videos.length; i++) {
                                                    var asrc = this.prepare_hls_url("//" + trailer.videos[i].url);
                                                    var src = asrc ? asrc : "//" + trailer.videos[i].url;
                                                    sources.push({
                                                        src: src,
                                                        type: trailer.videos[i].content_type,
                                                        size: trailer.videos[i].size
                                                    });
                                                }
                                            } else {
                                                for (var i = 0; i < trailer.videos.length; i++) {
                                                    var asrc = this.prepare_hls_url("//" + trailer.videos[i].url);
                                                    var src = asrc ? asrc : "//" + trailer.videos[i].url;
                                                    sources.push({
                                                        src: src,
                                                        type: trailer.videos[i].content_type,
                                                        size: trailer.videos[i].size
                                                    });
                                                    if (asrc) {
                                                        HLS = new window.Hls();
                                                        HLS.loadSource(src);
                                                    }
                                                    break;
                                                }
                                            }
                                        } else {
                                            for (var i = 0; i < trailer.videos.length; i++) {
                                                sources.push({
                                                    src: "//" + trailer.videos[i].url,
                                                    type: trailer.videos[i].content_type,
                                                    size: trailer.videos[i].size
                                                });
                                            }
                                        }
                                        player.source = {
                                            type: 'video',
                                            title: trailer.title,
                                            sources: sources,
                                            poster: trailer.poster
                                        };
                                        if (HLS) {
                                            HLS.attachMedia(player.media);
                                        }
                                        current_trailer_is_vertical = U.anyBool(trailer.vertical, false);
                                        this.show();
                                        player.play();
                                        var self = this;
                                        window.setTimeout(function () {
                                            if (U.isCallable(self.backport_error) && !U.isCallable(window.onerror)) {
                                                window.onerror = self.backport_error;
                                            }
                                        }, 5000);
                                    }
                                } catch (e) {

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

    <?php echo '</script'; ?>
>
<?php }
}
