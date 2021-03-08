{if empty($this->genres) && $this->series_count === NULL}
    {$OUT->meta->set_title("Фильм `$this->name` смотреть онлайн в хорошем качестве - Веб-кинотеатр Chill")->set_description($this->info|truncate:100|strip_tags)->set_og_title($this->name)->set_og_image_support(true)->set_og_image_data($this->images->get_image_by_index(0)->context, $this->images->get_image_by_index(0)->owner_id,$this->images->get_image_by_index(0)->image)|void}
{/if}
{foreach from=$this->genres item='genre' name=foo}
{if $genre->name == 'Дорама'}
{$OUT->meta->set_title("Дорама `$this->name` смотреть онлайн в хорошем качестве - Веб-кинотеатр Chill")->set_description($this->info|truncate:100|strip_tags)->set_og_title($this->name)->set_og_image_support(true)->set_og_image_data($this->images->get_image_by_index(0)->context, $this->images->get_image_by_index(0)->owner_id,$this->images->get_image_by_index(0)->image)|void}
{elseif $this->series_count === NULL}
{$OUT->meta->set_title("Фильм `$this->name` смотреть онлайн в хорошем качестве - Веб-кинотеатр Chill")->set_description($this->info|truncate:100|strip_tags)->set_og_title($this->name)->set_og_image_support(true)->set_og_image_data($this->images->get_image_by_index(0)->context, $this->images->get_image_by_index(0)->owner_id,$this->images->get_image_by_index(0)->image)|void}
{else}
{$OUT->meta->set_title("Сериал `$this->name` смотреть онлайн в хорошем качестве - Веб-кинотеатр Chill")->set_description($this->info|truncate:100|strip_tags)->set_og_title($this->name)->set_og_image_support(true)->set_og_image_data($this->images->get_image_by_index(0)->context, $this->images->get_image_by_index(0)->owner_id,$this->images->get_image_by_index(0)->image)|void}
{/if}
{/foreach}
{$OUT->add_css('/assets/css/chill/soap_page.css',100)|void}
{$OUT->add_script('/assets/chill/player/plyr/plyr.min.js',100,false)|void}
{$OUT->add_css('/assets/chill/player/plyr/plyr.css',100)|void}
<div id="a_film_head" style="display:none">

    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1" id="uiaa">
                <div id="cluii" style="display:none">
                    <div id="logo_in_top">
                        <div class="preloader__image">
                            <img src="/assets/chill/images/logo.png">
                        </div>
                        <!--<img src="/assets/chill/images/logo_grad.png">-->
                    </div>
                    <div id="a_film_head_block">
                        <video id="player_element"></video>
                        <div class="a-film-cover">
                            <div id="film_pay_start" data-pricecur="">

                            </div>
                            <!--<img src="/media/media_content_poster/{$this->id}/{$this->images->get_image_by_index(0)->image}.SW_1000H_600CF_1.jpg">-->
                            <img src="">

                                <div class="a-film-cover-preloader">
                                    <div class="CommonTemplatePreloader">
                                        <div class="CommonTemplatePreloaderPreloader">
                                            <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="100px" height="100px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M59.6 0h8v40h-8V0z" fill="#000000" fill-opacity="1"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(30 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(60 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(90 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(120 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#b2b2b2" fill-opacity="0.3" transform="rotate(150 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#999999" fill-opacity="0.4" transform="rotate(180 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#7f7f7f" fill-opacity="0.5" transform="rotate(210 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#666666" fill-opacity="0.6" transform="rotate(240 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#4c4c4c" fill-opacity="0.7" transform="rotate(270 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#333333" fill-opacity="0.8" transform="rotate(300 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#191919" fill-opacity="0.9" transform="rotate(330 64 64)"/><animateTransform attributeName="transform" type="rotate" values="0 64 64;30 64 64;60 64 64;90 64 64;120 64 64;150 64 64;180 64 64;210 64 64;240 64 64;270 64 64;300 64 64;330 64 64" calcMode="discrete" dur="960ms" repeatCount="indefinite"></animateTransform></g></svg>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="a-error-cover"><div class="a-error-cover-inner"><div class="a-error-cover-text">ошибка</div></div></div>
                    </div>
                </div>


                <div id="film_btns">
                    <div class="row">
                        <div class="col l1 hide-on-med-and-down" style="height:1px;">
                        </div>
                        <div class="col s10 l3 offset-s1" id="language_selector_outer_Da0phohche">
                            <div class="Da0phohcheLanguageSelector">
                                <select id="Da0phohche" class="browser-default"></select>
                            </div>
                        </div>

                        <div class="col s10 l3 offset-s1">
                            <div id="film_pay_start_trail" style="display:none">
                                {TT t='trailer'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="podel_block">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s10 offset-s1">
                        <div id="one_soc_send">
                            <span>{TT t='Podelis'}</span>
                            <div id="one_soc_send_btns">
                                <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                                <script src="https://yastatic.net/share2/share.js"></script>
                                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,whatsapp,telegram"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="select_series">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s10 offset-s1">
                        {if $this->seasons|@count > 1}
                            <div id="select_season">
                                <ul>
                                    {foreach from=$this->seasons item='season'}                                
                                        <li class="chill-soap-season-selector" data-season-id="a{$season->id}">{$season->name}</li>
                                        {/foreach}                            

                                </ul>
                            </div>
                        {/if}
                    </div>

                    <div class="col s12">
                        <div id="season-tabs-bodies">
                            {foreach from=$this->seasons item='season'}
                                {if $season->series|@count > 1}
                                    <div class="season-tab-item" data-id="a{$season->id}">
                                        <div class="chill-season-info">{$season->intro}</div>
                                        <div class="chill-season-series-list owl-carousel owl-series" id="owl-series">
                                            {foreach from=$season->series item='serie'}    
                                                <div class="chill-season-series-list-item" data-serie-id="{$serie->id}"  data-merge="2">
                                                    <a class="go_top_aa" data-serie-id="{$serie->id}">
                                                        <div class="one_seria_select">

                                                            <img src="/media/media_content_poster/{$serie->id}/{$serie->default_poster}.SW_200H_200CF_1PR_sq.jpg" />
                                                            <div class="one_seria_select_name">
                                                                {$serie->name}
                                                            </div>
                                                            <div class="one_seria_select_dur" data-dur="{$serie->duration}">
                                                                <span class="dura_in"></span> мин.
                                                            </div>
                                                            <div class="ser_price">
                                                                {if $serie->price > 0}
                                                                    <span class="paye">{$serie->price} {TT t='rub_point'}</span>
                                                                {else}
                                                                    <span class="freee">Free</span>
                                                                {/if}
                                                            </div>

                                                        </div>
                                                    </a>
                                                </div>                                        
                                            {/foreach}
                                        </div>
                                    </div>
                                {/if}
                            {/foreach}
                        </div>                                    
                    </div>
                </div>                                    
            </div>
        </div>
    </div>
</div>
<div id="a_film_body">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s10 l10 offset-l1 offset-s1">
                        <h1>{$this->name}</h1>
                    </div>
                    <div class="col s10 l7 offset-l1 offset-s1">
                        <div id="a_film_desc">
                            {$this->intro}
                            {$this->info}
                        </div>
                    </div>
                    <div class="col s10 l3 offset-l1 offset-s1">
                        <div id="a_film_features">
                            {if $this->age_restriction_tag != ''}
                                <div class="one_features">Возрастные ограничения: <b>{$this->age_restriction_tag}</b></div>
                            {/if}
                            {if $this->countries|@count > 0}<div class="one_features">{TT t='country'}: {foreach from=$this->countries item='country' name=foo}<b>{$country->name}{if $smarty.foreach.foo.last}{else}, {/if}</b> {/foreach}</div>{/if}
                        {if $this->countries|@count > 0}<div class="one_features">{TT t='genre'}: <b>{foreach from=$this->genres item='genre' name=foo}{$genre->name}{if $smarty.foreach.foo.last}{else}, {/if}{/foreach}</b></div>{/if}
                        {if (count($this->persons->filter('ROLE_DIRECTOR')))}
                            <div class="one_features">{TT t='rejisser'}: {foreach from=$this->persons->filter('ROLE_DIRECTOR') item='person' name='per'}<b>{$person->name}{if $smarty.foreach.per.last}{else},{/if}</b> {/foreach}</div>
                        {/if}
                        {if (count($this->persons->filter('ROLE_ACTOR')))}
                            <div class="one_features">{TT t='actors'}: {foreach from=$this->persons->filter('ROLE_ACTOR') item='person' name='per'}<b>{$person->name}{if $smarty.foreach.per.last}{else},{/if}</b> {/foreach}</div>
                        {/if}

                        {if (count($this->persons->filter('ROLE_OPERATOR')))}
                            <div class="one_features">{TT t='operator'}: {foreach from=$this->persons->filter('ROLE_OPERATOR') item='person' name='per'}<b>{$person->name}{if $smarty.foreach.per.last}{else},{/if}</b> {/foreach}</div>
                        {/if}
                        {if (count($this->persons->filter('ROLE_SCENARIST')))}
                            <div class="one_features">{TT t='author_scen'}: {foreach from=$this->persons->filter('ROLE_SCENARIST') item='person' name='per'}<b>{$person->name}{if $smarty.foreach.per.last}{else},{/if}</b> {/foreach}</div>
                        {/if}
                        {if (count($this->persons->filter('ROLE_COMPOSITOR')))}
                            <div class="one_features">{TT t='compositor'}: {foreach from=$this->persons->filter('ROLE_COMPOSITOR') item='person' name='per'}<b>{$person->name}{if $smarty.foreach.per.last}{else},{/if}</b> {/foreach}</div>
                        {/if}
                        {if (count($this->persons->filter('ROLE_PRODUCER')))}
                            <div class="one_features">{TT t='produser'}: {foreach from=$this->persons->filter('ROLE_PRODUCER') item='person' name='per'}<b>{$person->name}{if $smarty.foreach.per.last}{else},{/if}</b> {/foreach}</div>
                        {/if}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="container" id="naz">
    <div class="row">
        <div class="col s12 m10 offset-m1">
            <div class="row">

                <div class="col s12">
                    <div id="series_cadrs_a" class="owl-carousel">

                        {foreach from=$this->frames item='frame'}
                            <div class="one_photo_frame" data-merge="2">
                                <a href="/media/media_content_frame/{$this->id}/{$frame->name}.S.jpg" class="gooo" rel="gal">
                                    <img src="/media/media_content_frame/{$this->id}/{$frame->name}.SW_200H_200CF_1PR_sq.jpg">
                                </a>
                            </div>
                        {/foreach}

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>{literal}
    $(document).ready(function () {
        $.get('https://kino-cache.cdnvideo.ru/kinoteatr/wic/pix.jpg').done(function () {
            $("#cluii").fadeIn(0);
            $("#a_film_head").fadeIn(0);
        })
                .fail(function () {

                    $("#cluii").remove();
                    $("#a_film_head").remove();
                });
        var bbb = $(".one_photo_frame").length;
        if (bbb > 0) {
            $("#naz").fadeIn(0);
        } else {
            $("#naz").fadeOut(0);
        }
    });
    (function () {
        jQuery(function () {
            var E = window.Eve, EFO = E.EFO, U = EFO.U;
            var HLS_TEMPLATE_P = '{/literal}{$controller->get_preference('HLS_TEMPLATE_P',null)}{literal}';
            console.log(HLS_TEMPLATE_P);
            var soap_data = {/literal}{$this->marshall()|json_encode}{literal};
            var trailer_data = null;
            var current_serie_data = null;
            var current_season_data = null;
            var tabheaders = jQuery('#select_season');
            var tab_bodies = jQuery('#season-tabs-bodies');
            var player = new Plyr('#player_element', {
                debug: false,
                i18n: {
                    speed: 'Скорость',
                    normal: 'Нормальная'
                }

            });
            var language_map = null;
            var link_map = null;
            function check_need_show_fuck() {
                try {
                    if (U.isMobile()) {//если мобилька
                        if (player.playing) { // если плеер играет
                            if (U.anyBool(U.safeObject(current_serie_data).vertical, false)) { // если есть флаг vertical
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
            player.on('playing', check_need_show_fuck);
            player.on('play', showLoad);
            player.on('playing', hideLoad);
            player.on('pause', check_need_show_fuck);
            window.addEventListener('resize', check_need_show_fuck);
            window.addEventListener('orientationchanged', check_need_show_fuck);

            function showLoad() {
                $('#logo_in_top').fadeIn(0);
            }
            function hideLoad() {
                $('#logo_in_top').fadeOut(0);
            }

            function select_season(season_marker) {
                tabheaders.find('.tabheader-active').removeClass('tabheader-active');
                tab_bodies.find('.tabbody-active').removeClass('tabbody-active');
                tabheaders.find('[data-season-id=' + season_marker + ']').addClass('tabheader-active');
                tab_bodies.find('[data-id=' + season_marker + ']').addClass('tabbody-active');
            }
            tabheaders.on('click', '[data-season-id]', function (e) {
                e.stopPropagation();
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                select_season(jQuery(this).data('seasonId'));
            });




            function search_series(series_id) {
                var found = null;
                var found_season = null;
                series_id = U.IntMoreOr(series_id, 0, null);
                if (series_id) {
                    var sa = U.safeArray(U.safeObject(soap_data).seasons);
                    for (var i = 0; i < sa.length; i++) {
                        var season = U.safeObject(sa[i]);
                        var series = U.safeArray(season.series);
                        for (var j = 0; j < series.length; j++) {
                            var serie = U.safeObject(series[j]);
                            if (U.IntMoreOr(serie.id, 0, null) === series_id) {
                                found = serie;
                                found_season = season;
                                break
                            }
                        }
                    }
                }
                return found && found_season ? {found: found, season: found_season} : null;
            }

            function search_season(season_id) {
                var found = null;
                season_id = U.IntMoreOr(season_id, 0, null);
                if (season_id) {
                    var sa = U.safeArray(U.safeObject(soap_data).seasons);
                    for (var i = 0; i < sa.length; i++) {
                        var season = U.safeObject(sa[i]);
                        if (U.IntMoreOr(season.id, 0, null) === season_id) {
                            found = season;
                            break
                        }
                    }
                }
                return found;
            }

            function search_trailer() {
                var found = null;
                var sa = U.safeArray(U.safeObject(soap_data).trailers);
                if (sa.length) {
                    return sa[0];
                }
                var sa = U.safeArray(U.safeObject(soap_data).seasons);
                for (var i = 0; i < sa.length; i++) {
                    var season = U.safeObject(sa[i]);
                    var trailers = U.safeArray(season.trailers);
                    if (trailers.length) {
                        return trailers[0];
                    }

                }
                return null;
            }

            function fixup(season_id, serie_id) {
                season_id = U.IntMoreOr(season_id, 0, null);
                serie_id = U.IntMoreOr(serie_id, 0, null);
                if (serie_id && !season_id) {
                    var serie_info = search_series(serie_id);
                    if (serie_info) {
                        season_id = U.IntMoreOr(serie_info.season.id, 0, null);
                    }
                } else if (season_id && !serie_id) {
                    var season = search_season(season_id);
                    if (season) {
                        serie_id = U.IntMoreOr(U.safeArray(season.series).length ? season.series[0].id : null);
                    }
                } else if (!season_id && !serie_id) {
                    var seasons = U.safeArray(U.safeObject(soap_data).seasons);
                    var season = seasons.length ? seasons[0] : null;
                    if (season) {
                        season_id = U.IntMoreOr(season.id, 0, null);
                        var series = U.safeArray(season.series);
                        if (series.length) {
                            serie_id = U.IntMoreOr(series[0].id, 0, null);
                        }
                    }
                }
                return season_id && serie_id ? {season_id: season_id, serie_id: serie_id} : null;
            }


            function setup_error(v) {
                v = U.NEString(v, null);
                if (v) {
                    jQuery('.a-error-cover-text').html(v);
                    jQuery('.a-error-cover').show();
                } else {
                    jQuery('.a-error-cover').hide();
                }
            }

            function setup_image(image_url) {
                jQuery('.a-film-cover>img').attr('src', image_url);
            }

            function get_poster_url() {
                if (U.NEString(current_serie_data.default_preview, null)) {
                    return "/media/media_content_preview/" + current_serie_data.id + "/" + current_serie_data.default_preview + '.SW_996H_560CF_1.jpg';
                }
                if (U.NEString(current_serie_data.default_poster, null)) {
                    return "/media/media_content_poster/" + current_serie_data.id + "/" + current_serie_data.default_poster + '.SW_996H_560CF_1.jpg';
                }
                if (U.NEString(current_season_data.default_poster, null)) {
                    return "/media/media_content_poster/" + current_season_data.id + "/" + current_season_data.default_poster + '.SW_996H_560CF_1.jpg';
                }
                if (U.NEString(soap_data.default_poster, null)) {
                    return "/media/media_content_poster/" + soap_data.id + "/" + soap_data.default_poster + '.SW_996H_560CF_1.jpg';
                }
                return '/media/fallback/1/media_content_poster.SW_996H_560CF_1.jpg';
            }


            function join_objects_to_string(x) {
                var r = [];
                var s = U.safeArray(x);
                for (var i = 0; i < s.length; i++) {
                    try {
                        var p = U.NEString(U.safeObject(s[i]).name, null);
                        p ? r.push(p) : 0;
                    } catch (e) {

                    }
                }
                return r.join('');
            }

            function setup(season_id, serie_id) {
                jQuery('#language_selector_outer_Da0phohche').hide();
                jQuery('#Da0phohche').off('change', on_lang_change_sw);
                link_map = null;
                language_map = null;
                setup_error(null);
                jQuery('.a-film-cover').show();
                var t = fixup(season_id, serie_id);
                if (!t) {
                    setup_error("Извините, этот сериал пока не готов к просмотру!<br>Мы постараемся подготовить его как можно скорее.");
                }
                var found = search_series(t.serie_id);
                var season = found.season;
                var serie = found.found;
                current_season_data = season;
                current_serie_data = serie;
                var fils = U.safeArray(current_serie_data.files);
                var langs = [];
                var langsi = {};
                var langsii = {};
                for (var i = 0; i < fils.length; i++) {
                    var selector = U.NEString(fils[i].selector, null);
                    if (selector) {
                        if (langsi[selector] !== selector) {
                            langs.push(selector);
                            langsi[selector] = selector;
                        }

                        langsii[selector] = U.safeArray(langsii[selector]);
                        langsii[selector].push(fils[i].cdn_id);
                    }
                }

                language_map = langs.length ? langsii : null;
                select_season(['a', season.id].join(''));
                setup_image(get_poster_url());
                trailer_data = search_trailer();
                jQuery('#film_pay_start_trail')[trailer_data ? 'show' : 'hide']();
                var cost = EFO.Checks.formatPriceNSD(U.FloatMoreOr(serie.price, 0, 0), 0);
                jQuery('#film_pay_start').html(
                        ['<i class="mdi-play mdi"></i> Открыть доступ на 24 часа (', cost, " {/literal}{TT t='rub_point'}{literal})"].join('')
                        );
                jQuery('#film_pay_start').hide();
                if (langs.length > 1) {
                    jQuery('#language_selector_outer_Da0phohche').show();
                    var html = [];
                    for (var i = 0; i < langs.length; i++) {
                        html.push(['<option value="', langs[i], '">', langs[i], '</option>'].join(''));
                    }
                    jQuery('#Da0phohche').html(html.join(''));
                }
                show_loader();
                jQuery.getJSON('/Info/API', {action: "check_user_access", content_id: serie.id})
                        .done(serie_access_loaded)
                        .fail(serie_access_fail)
                        .always(hide_loader);
                try {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({event: 'gtm-ee-event', "gtm-ee-event-category": 'Enhanced Ecommerce', "gtm-ee-event-action": 'Product Details', "gtm-ee-event-non-interaction": 'True',
                        ecommerce: {
                            detail: {
                                actionField: {'list': 'Lenta'},
                                products: [
                                    {
                                        name: soap_data.name,
                                        id: soap_data.id,
                                        price: serie.price,
                                        brand: join_objects_to_string(soap_data.countries),
                                        category: join_objects_to_string(soap_data.genres),
                                        variant: serie.num
                                    }
                                ]
                            }
                        }
                    });
                } catch (e) {

                }
            }

            function show_loader() {
                jQuery('.a-film-cover-preloader').show();
            }

            function hide_loader() {
                jQuery('.a-film-cover-preloader').hide();
            }

            function serie_access_fail() {
                setup_error("Извините, при обработке запроса призошла ошибка.<br>Мы постараемся исправить ее как можно скорее.");
            }

            function on_lang_change_sw(e) {
                debugger;
                var t = jQuery(this).val();
                var sources = [];
                if (language_map && link_map) {
                    var video_id = language_map[t][0];
                    if (video_id) {
                        for (var i = 0; i < link_map.length; i++) {
                            if (link_map[i].id === video_id) {
                                sources.push({
                                    src: "//" + link_map[i].url,
                                    type: link_map[i].content_type,
                                    size: U.IntMoreOr(link_map[i].size, 0, null)
                                });
                            }
                        }
                    }
                }
                if (!sources.length) {
                    for (var i = 0; i < link_map.length; i++) {
                        sources.push({
                            src: "//" + link_map[i].url,
                            type: link_map[i].content_type,
                            size: U.IntMoreOr(link_map[i].size, 0, null)
                        });
                    }
                }
                uni_set_player_source({//lcang
                    type: 'video',
                    title: current_serie_data.name,
                    sources: sources,
                    poster: get_poster_url()
                });
                player.film_source = null;

            }


            function prepare_hls_source(mp_url) {
                mp_url = U.NEString(mp_url, '');
                if(/.*\.m3u8$/i.test(mp_url)){
                    return mp_url;
                }
                //kino-cache.cdnvideo.ru/temp/
                var m = /kino-cache\.cdnvideo\.ru\/temp\/(.{1,})$/i.exec(mp_url);
                if (m) {
                    var PID = m[1];
                    var value = HLS_TEMPLATE_P.replace('%ID%', PID);
                    return value;
                }
                return null;
            }

            function uni_set_player_source(source_object) {
                // Преобразовать src в ссылки на hls, если доступен
                source_object = U.safeObject(source_object);
                source_object = JSON.parse(JSON.stringify(source_object));
                var sources = U.safeArray(source_object.sources);
                source_object.sources = sources;

                if (window.Hls && window.Hls.isSupported()) {
                    if (window.Hls.is_native()) {
                        for (var i = 0; i < sources.length; i++) {
                            var asrc = prepare_hls_source('//' + sources[i].src);
                            sources[i].src = asrc ? asrc : sources[i].src;
                        }
                        player.source = source_object; //usps
                    } else {
                        var HLS = null;
                        for (var i = 0; i < sources.length; i++) {
                            var asrc = prepare_hls_source('//' + sources[i].src);
                            sources[i].src = asrc ? asrc : sources[i].src;
                            if (asrc && !HLS) {
                                HLS = new window.Hls();
                                HLS.loadSource(asrc);
                            }
                        }
                        player.source = source_object;//usps
                        if (HLS) {
                            HLS.attachMedia(player.media);
                        }
                    }
                } else {
                    player.source = source_object; //usps
                }
            }


            function lock_player_controls() {
                if (player.film_source) {
                    jQuery('.plyr__controls').addClass('strict-hidden');
                    if (player.__vv === void(0)) {
                        player.__vv = player.volume;
                        player.volume = Math.max(0, player.volume / 2);
                    }
                }
            }

            function unlock_player_controls() {
                jQuery('.plyr__controls').removeClass('strict-hidden');
                if (player.__vv) {
                    player.volume = player.__vv;
                    player.__vv = void(0);
                }
            }

            function parse_link_temp(link_info){
                var m = /^.*temp\/(.{1,})$/.exec(link_info.url);
                if(m){
                    return link_info.id;//[link_info.id,m[1]].join(':');
                }
                return link_info.id;//null;
            }

            function on_serie_access_success(ci) {

                jQuery('#Da0phohche').on('change', on_lang_change_sw);
                var links = JSON.parse(ci.links);
                link_map = links;
                var uplay = false;//use hls player
                debugger;
                if (uplay) {
                    var ids_to_player = [];
                    if (language_map) {
                        var current_language = jQuery('#Da0phohche').val();
                        var video_ids = U.safeArray(language_map[current_language]);
                        if (video_ids && video_ids.length) {
                            for (var i = 0; i < links.length; i++) {
                                var ix = video_ids.indexOf(links[i].id);
                                if (ix >= 0) {
                                    ids_to_player.push(parse_link_temp(links[i]));
                                }
                            }
                        }
                    }
                    if (!ids_to_player.length) {
                        for (var i = 0; i < links.length; i++) {
                            ids_to_player.push(parse_link_temp(links[i]));
                        }
                    }

                    jQuery.getJSON('/Info/API', {action: 'player_id', vil: ids_to_player})
                            .done(function (d) {
                                if (d.status === 'ok') {
                                    var player_id = U.NEString(d.player_id, '');
                                    var sources = [{src: ['//video.platformcraft.ru/vod/', player_id, '/playlist.m3u8'].join(''), size: null, type: null}];
                                    if (!soap_data.preplay_video_url) {
                                        uni_set_player_source({//sas
                                            type: 'video',
                                            title: current_serie_data.name,
                                            sources: sources,
                                            poster: get_poster_url()
                                        });
                                        player.film_source = null;
                                    } else {
                                        player.film_source = {//sas
                                            type: 'video',
                                            title: current_serie_data.name,
                                            sources: sources,
                                            poster: "/assets/chill/images/black.jpg"
                                        };
                                        player.on('ended', player_play_end);
                                        uni_set_player_source({//sas
                                            type: 'video',
                                            title: current_serie_data.name,
                                            sources: [{src: "//" + soap_data.preplay_video_url}],
                                            poster: get_poster_url()
                                        });
                                        player.on('playing', lock_player_controls);
                                        lock_player_controls();

                                    }
                                    jQuery('.a-film-cover').hide();
                                }
                            });
                } else {
                    var sources = [];
                    if (language_map) { // если лангмап определен
                        var current_language = jQuery('#Da0phohche').val();
                        var video_id = U.safeArray(language_map[current_language])[0];
                        if (video_id) {
                            for (var i = 0; i < links.length; i++) {
                                if (links[i].id === video_id) {
                                    sources.push({
                                        src: "//" + links[i].url,
                                        type: links[i].content_type,
                                        size: U.IntMoreOr(links[i].size, 0, null)
                                    });
                                }
                            }
                        }
                    }
                    if (!sources.length) {
                        for (var i = 0; i < links.length; i++) {
                            sources.push({
                                src: "//" + links[i].url,
                                type: links[i].content_type,
                                size: U.IntMoreOr(links[i].size, 0, null)
                            });
                        }
                    }

                    if (!soap_data.preplay_video_url) {

                        uni_set_player_source({//sas
                            type: 'video',
                            title: current_serie_data.name,
                            sources: sources,
                            poster: get_poster_url()
                        });
                        player.film_source = null;

                    } else {

                        player.film_source = {//sas

                            type: 'video',
                            title: current_serie_data.name,
                            sources: sources,
                            poster: "/assets/chill/images/black.jpg"
                        };
                        player.on('ended', player_play_end);
                        uni_set_player_source({//sas
                            type: 'video',
                            title: current_serie_data.name,
                            sources: [{src: "//" + soap_data.preplay_video_url}],
                            poster: get_poster_url()
                        });
                        player.on('playing', lock_player_controls);
                        lock_player_controls();

                    }
                    jQuery('.a-film-cover').hide();
                }
            }


            function player_play_end() {
                if (player.film_source) {
                    uni_set_player_source(player.film_source);//predef
                    player.film_source = null;
                    player.off('playing', lock_player_controls);
                    unlock_player_controls();
                    player.play();
                }
            }

            function on_serie_access_fail(ci) {
                var cost = U.FloatOr(ci.price, 0);
                var serie_name = current_serie_data.name;
                var serie_num = U.IntMoreOr(current_serie_data.num, 0, 0);
                var season_num = U.IntMoreOr(current_season_data.num, 0, 0);
                if (cost != '0') {
                    jQuery('#film_pay_start').show().attr("data-pricecur", cost).html(
                            ['<i class="mdi-play mdi"></i> Открыть доступ на 24 часа (', cost, " {/literal}{TT t='rub_point'}{literal})"].join('')
                            );

                    return this;
                } else {
                    jQuery('#film_pay_start').show().attr("data-pricecur", "free").html(
                            ['<i class="mdi-play mdi"></i> Открыть доступ на 24 часа ({/literal}{TT t='free'}{literal})'].join('')
                            );

                    return this;
                }

            }

            function serie_access_loaded(d) {
                if (U.isObject(d)) {
                    if (d.status === 'ok') {
                        var sa = U.safeObject(d.content_info);

                        if (!U.anyBool(sa.enabled, false)) {
                            setup_error("Извините, серия временно недоступна.");
                            return;
                        }
                        if (U.anyBool(d.access, false)) {
                            return on_serie_access_success(d.content_info);
                        } else {
                            return on_serie_access_fail(d.content_info);
                        }
                    }
                    if (d.status === 'error') {
                        setup_error("Извините, серия временно недоступна.");
                        return;
                    }
                }
                return serie_access_fail();
            }


            jQuery('#film_pay_start').on('click', function (e) {
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                e.stopPropagation();
                show_loader();
                jQuery.getJSON('/Info/API', {action: "request_access", content_id: current_serie_data.id})
                        .done(request_access_response)
                        .fail(request_access_fail)
                        .always(hide_loader);
            });
            jQuery('.a-film-cover').on('click', function (e) {
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                e.stopPropagation();
                show_loader();
                jQuery.getJSON('/Info/API', {action: "request_access", content_id: current_serie_data.id})
                        .done(request_access_response)
                        .fail(request_access_fail)
                        .always(hide_loader);
            });
            jQuery('#film_pay_start_trail').on('click', function (e) {
                e.preventDefault ? e.preventDefault() : e.returnValue = false;
                e.stopPropagation();
                try {
                    window.run_trailers_player(soap_data);
                } catch (e) {

                }
            });

            function request_access_response(d) {
                if (U.isObject(d)) {

                    if (d.status === 'ok') {
                        var nnn = d.content_info.price;
                        console.log(nnn);
                        $("#film_pay_start").fadeOut(0);
                        window.Eve.EFO.Alert().set_text("Доступ открыт на 24 часа").set_title("Успешно!").set_close_btn(true)
                                .set_style("green").set_timeout(3000).set_callback(window, function () {
                        }).show();
                        if (nnn > 0 && nnn != '' && typeof nnn != 'undefined' && nnn != 'undefined') {
                            var a = $("#balans_in_out_ser").text();
                            var b = a - 1;
                            function declOfNum(n, text_forms) {
                                n = Math.abs(n) % 100;
                                var n1 = n % 10;
                                if (n > 10 && n < 20) {
                                    return text_forms[2];
                                }
                                if (n1 > 1 && n1 < 5) {
                                    return text_forms[1];
                                }
                                if (n1 == 1) {
                                    return text_forms[0];
                                }
                                return text_forms[2];
                            }
                            var nx = declOfNum(b, ['серия', 'серии', 'серий']);
                            $("#balans_in").html('<a href="/Profile"><i class="mdi mdi-wallet-outline koshel"></i> <span id="balans_in_out_ser">' + b + '</span> ' + nx + '</a>');
                        }
                        var tid = U.NEString(d.transaction_id, null);
                        if (tid) {
                            try {
                                window.dataLayer = window.dataLayer || [];
                                window.dataLayer.push({
                                    event: 'gtm-ee-event', "gtm-ee-event-category": 'Enhanced Ecommerce', "gtm-ee-event-action": 'Purchase', "gtm-ee-event-non-interaction": 'False',
                                    ecommerce: {
                                        purchase: {
                                            actionField: {id: tid, revenue: current_serie_data.price},
                                            products: [
                                                {
                                                    'name': soap_data.name,
                                                    'id': soap_data.id,
                                                    'price': current_serie_data.price, // стоимость
                                                    'brand': join_objects_to_string(soap_data.countries),
                                                    'category': join_objects_to_string(soap_data.genres),
                                                    'variant': current_serie_data.season_id+"_"+current_serie_data.num,
                                                    'quantity': 1
                                                }
                                            ]
                                        }
                                    }
                                });
                            } catch (e) {

                            }
                        }
                        return serie_access_loaded(d);
                    }
                    if (d.status === 'error') {
                        if (d.error_info.message === "no files") {
                            return setup_error("Извините, сериал пока не готов к показу.<br>Транзакция отменена");
                        }
                        if (d.error_info.message === "auth_required") {
                            return run_authorization_sequence();
                        }
                        if (d.error_info.message === "no_money") {
                            return setup_error("У Вас на счету недостаточно средств!<br>Пополнить счет Вы можете в Вашем <a href=\"/profile\">личном кабинете</a>.");
                        }
                        return setup_error(d.error_info.message);
                    }
                }
                setup_error("Некорректный ответ сервера");
            }

            function request_access_fail() {
                setup_error("Ошибка?");
            }

            jQuery('body').on('click', '.chill-season-series-list-item a', function (e) {
                var serie_id = U.IntMoreOr(jQuery(this).data('serieId'), 0, null);
                if (serie_id) {
                    setup(null, serie_id);
                }
            });


            EFO.Events.GEM().on('LOGIN_SUCCESS', window, function () {
                setup(null, current_serie_data.id);
            });
            var hash_serie_match = /serie_(\d{1,})/i.exec(U.NEString(location.hash, ''));
            var hash_season_match = /season_(\d{1,})/i.exec(U.NEString(location.hash, ''));
            if (hash_serie_match) {
                setup(null, U.IntMoreOr(hash_serie_match[1], 0, null));
            } else if (hash_season_match) {
                setup(U.IntMoreOr(hash_season_match[1], 0, null), null);
            } else {
                setup();
            }
        });
    })();

    $(document).ready(function () {
        $(".owl-series").owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            merge: true,
            dots: false,
            responsive: {
                0: {
                    mergeFit: true,
                    items: 5
                },
                1100: {
                    mergeFit: true,
                    items: 9
                }
            }
        });
        $("#series_cadrs_a").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            merge: true,
            dots: false,
            responsive: {
                0: {
                    mergeFit: true,
                    items: 5
                },
                1100: {
                    mergeFit: true,
                    items: 9
                }
            }
        });
        $("a.gooo").colorbox({transition: 'elastic', maxWidth: '90%', maxHeight: '90%', rel: 'gal', next: '<i class="mdi mdi-chevron-right"></i>', previous: '<i class="mdi mdi-chevron-left"></i>'});
        var urla = window.location.pathname;
        localStorage.setItem("soap", urla);
        $(".one_seria_select_dur").each(function () {
        if($(this).data("dur") !=''){
            var a = $(this).data("dur");
            }else{
            var a = 0;
            }
            var b = a.toFixed();
            var c = b / 60;
            var d = c.toFixed();
            $(this).find(".dura_in").text(d);
        })
    });
    $(".go_top_aa").click(function () {
        $('html,body').animate({scrollTop: 0}, 'slow');
    });
</script>{/literal}
</div>
{get_media_reviews q=10 id=$this->id assign='reviews'}
{if count($reviews)}
    <div id="list_reviews">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s10 offset-s1">
                            <div class="head3">{TT t='Otyvs'}</div>
                            {foreach from=$reviews item='r'}
                                <div class="row">
                                    <div class="col s12">
                                        <div class="one_review">
                                            <div class="row">
                                                <div class="col s8">
                                                    <div class="one_review_name">{$r->name}</div>
                                                </div>
                                                <div class="col s4 right-align">
                                                    <div class="one_review_date">{$r->post_date_str}</div>
                                                </div>
                                            </div>
                                            <!-- 
                                            Доступные поля отзыва
                                            * @property int $media_id - id media для которого отзыв
                                            * @property int $user_id - id пользака
                                            * @property string $name - имя пользака
                                            * @property int $rate - оценка (1-5)
                                            * @property string $info - текст отзыва
                                            * @property \DateTime $post - объект даты отзыва
                                            * @property string $post_str - дата отзыва d.m.Y H:i
                                            * @property string $post_date_str d.m.Y
                                            * @property string $post_time_str H:i
                                            -->
                                            <div class="one_review_text">{$r->info}</div>
                                        </div>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
{/if}

<div id="a_news_footer">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s10 offset-s1">
                        <div class="row">
                            <div class="col s12">
                                <div id="send_review" class="init_review_seqence" data-content-id="{$this->id}">{TT t='Send_otz'}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /div -->
{include './trailer_player.tpl'}
