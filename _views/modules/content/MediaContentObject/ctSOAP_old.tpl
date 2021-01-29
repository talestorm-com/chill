{$OUT->add_css('/assets/css/chill/soap_page.css',100)|void}
{$OUT->add_script('/assets/chill/player/plyr/plyr.min.js',100,false)|void}
{$OUT->add_css('/assets/chill/player/plyr/plyr.css',100)|void}
<div id="a_one_film">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s10">
                            <h1>{$this->name} <span class="chill-soap-page-series-title"></span></h1>
                        </div>
                        <div class="col s2">
                            <div class="right-align">
                                <a href="javascript:history.back()" class="back_back">Назад</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="a_film_head">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div id="a_film_head_block">
                        <video id="player_element"></video>
                        <div class="a-film-cover">
                            <img src="javascript:void" />
                            <div id="film_btns">
                                <div id="film_pay_start">
                                    Смотреть за 6 &#8381; (1 серию 1 сезона)
                                </div>
                                <div id="film_pay_start_trail">
                                    <i class="mdi mdi-play"></i>
                                    <span>Трейлер</span>
                                </div>
                            </div>
                            <div class="a-film-cover-preloader">
                                <div class="CommonTemplatePreloader">
                                    <div class="CommonTemplatePreloaderPreloader">
                                        <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="100px" height="100px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M59.6 0h8v40h-8V0z" fill="#000000" fill-opacity="1"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(30 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(60 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(90 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#cccccc" fill-opacity="0.2" transform="rotate(120 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#b2b2b2" fill-opacity="0.3" transform="rotate(150 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#999999" fill-opacity="0.4" transform="rotate(180 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#7f7f7f" fill-opacity="0.5" transform="rotate(210 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#666666" fill-opacity="0.6" transform="rotate(240 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#4c4c4c" fill-opacity="0.7" transform="rotate(270 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#333333" fill-opacity="0.8" transform="rotate(300 64 64)"/><path d="M59.6 0h8v40h-8V0z" fill="#191919" fill-opacity="0.9" transform="rotate(330 64 64)"/><animateTransform attributeName="transform" type="rotate" values="0 64 64;30 64 64;60 64 64;90 64 64;120 64 64;150 64 64;180 64 64;210 64 64;240 64 64;270 64 64;300 64 64;330 64 64" calcMode="discrete" dur="960ms" repeatCount="indefinite"></animateTransform></g></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="a-error-cover"><div class="a-error-cover-inner"><div class="a-error-cover-text">erorerrorerrror</div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="a_film_body">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l8">
                            <div id="a_film_desc">
                                {$this->intro}
                                {$this->info}
                            </div>
                        </div>
                        <div class="col s12 l3 offset-l1">
                            <div id="a_film_features">
                                <div class="one_features">Рейтинг: <b>4.7 (2 551)</b></div>
                                <div class="one_features">Дата выхода: <b>10 мая 2019</b></div>
                                <div class="one_features">Страна: {foreach from=$this->countries item='country'}<b>{$country->name}</b> {/foreach}</div>
                                <div class="one_features">Жанр: <b>{foreach from=$this->genres item='genre'}<a href="/search/by_genre?id={$genre->id}">{$genre->name}</a> {/foreach}</b></div>
                                <div class="one_features">Время: <b>58 мин.</b></div>
                                <div class="one_features">Режиссер: {foreach from=$this->persons->filter('ROLE_DIRECTOR') item='person'}<b>{$person->name}</b> {/foreach}</div>
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
                <div class="col s12 l10 offset-l1">
                    <div id="select_season">
                        <ul>
                            {foreach from=$this->seasons item='season'}                                
                                <li class="chill-soap-season-selector" data-season-id="a{$season->id}">{$season->name}</li>
                                {/foreach}                            
                        </ul>
                    </div>
                    <div id="season-tabs-bodies">
                        {foreach from=$this->seasons item='season'}
                            <div class="season-tab-item" data-id="a{$season->id}">
                                <div class="chill-season-info">{$season->intro}</div>
                                <div class="chill-season-series-list">
                                    {foreach from=$season->series item='serie'}                                        
                                        <div class="chill-season-series-list-item col s6 m3 l2" data-serie-id="{$serie->id}">
                                            <a href="#a_film_head_block" data-serie-id="{$serie->id}">
                                                <div class="one_seria_select">
                                                    <img src="{$serie->image_url}.SW_526CF_1PR_vposter.jpg" />
                                                    <div class="one_seria_select_name">
                                                        {$serie->name}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>                                        
                                    {/foreach}
                                </div>
                            </div>
                        {/foreach}
                    </div>                                    
                </div>
            </div>
        </div>
    </div>
    <script>{literal}
        (function () {
            jQuery(function () {
                var E = window.Eve, EFO = E.EFO, U = EFO.U;
                var soap_data = {/literal}{$this->marshall()|json_encode}{literal};
                var trailer_data = null;
                var current_serie_data = null;
                var current_season_data = null;
                var tabheaders = jQuery('#select_season');
                var tab_bodies = jQuery('#season-tabs-bodies');
                var player = new Plyr('#player_element', {
                    debug: true
                });
                window.player = player;


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
                    if (U.NEString(current_serie_data.default_poster, null)) {
                        return "/media/media_content_poster/" + current_serie_data.id + "/" + current_serie_data.default_poster + '.SW_1200CF_1PR_hposter.jpg';
                    }
                    if (U.NEString(current_season_data.default_poster, null)) {
                        return "/media/media_content_poster/" + current_season_data.id + "/" + current_season_data.default_poster + '.SW_1200CF_1PR_hposter.jpg';
                    }
                    if (U.NEString(soap_data.default_poster, null)) {
                        return "/media/media_content_poster/" + soap_data.id + "/" + soap_data.default_poster + '.SW_1200CF_1PR_hposter.jpg';
                    }
                    return '/media/fallback/1/media_content_poster.SW_1200CF_1PR_hposter.jpg';
                }

                function setup(season_id, serie_id) {
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
                    select_season(['a', season.id].join(''));
                    setup_image(get_poster_url());
                    trailer_data = search_trailer();
                    jQuery('#film_pay_start_trail')[trailer_data ? 'show' : 'hide']();
                    var cost = EFO.Checks.formatPriceNSD(U.FloatMoreOr(serie.price, 0, 0), 2);
                    jQuery('#film_pay_start').html(
                            ['Смотреть за ', cost, " \u20BD ", "(", serie.name, ', ', season.name, ")"].join('')
                            );
                    jQuery('#film_pay_start').hide();
                    show_loader();
                    jQuery.getJSON('/Info/API', {action: "check_user_access", content_id: serie.id})
                            .done(serie_access_loaded)
                            .fail(serie_access_fail)
                            .always(hide_loader);
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

                function on_serie_access_success(ci) {
                    var links = JSON.parse(ci.links);
                    var sources = [];
                    for (var i = 0; i < links.length; i++) {
                        sources.push({
                            src: "//" + links[i].url,
                            type: links[i].content_type,
                            size: U.IntMoreOr(links[i].size, 0, null)
                        });
                    }
                    player.source = {
                        type: 'video',
                        title: current_serie_data.name,
                        sources: sources,
                        poster: get_poster_url()
                    };
                    jQuery('.a-film-cover').hide();
                }

                function on_serie_access_fail(ci) {
                    var cost = U.FloatOr(ci.price, 0);
                    var serie_num = U.IntMoreOr(current_serie_data.num, 0, 0);
                    var season_num = U.IntMoreOr(current_season_data.num, 0, 0);
                    jQuery('#film_pay_start').show().html(
                            ['Смотреть за ', EFO.Checks.formatPriceNSD(cost, 2), " \u20BD ", "(", serie_num, ' серию ', season_num, " сезона)"].join('')
                            );
                    return this;
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

                function request_access_response(d) {
                    if (U.isObject(d)) {
                        if (d.status === 'ok') {
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
    </script>{/literal}
    <div id="list_reviews">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h3>Отзывы</h3>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="one_review">
                                <div class="row">
                                    <div class="col s8">
                                        <div class="one_review_name">Андрей</div>
                                    </div>
                                    <div class="col s4 right-align">
                                        <div class="one_review_date">15.03.2020</div>
                                    </div>
                                </div>
                                <div class="one_review_text">Отличный сериал, с удовольствием жду четвертый сезон. Кстати, когда он? 5+.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="a_news_footer">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12">
                            <div id="send_review">Оставить отзыв</div>
                        </div>
                        <div class="col s12 right-align">
                            <div id="one_soc_send">
                                <span>Поделиться</span>
                                <div id="one_soc_send_btns">
                                    <div class="one_soc_send_btn" id="vk_soc_btn">
                                        <i class="mdi mdi-vk"></i>
                                    </div>
                                    <div class="one_soc_send_btn" id="fb_soc_btn">
                                        <i class="mdi mdi-facebook"></i>
                                    </div>
                                    <div class="one_soc_send_btn" id="ok_soc_btn">
                                        <i class="mdi mdi-odnoklassniki"></i>
                                    </div>
                                    <div class="one_soc_send_btn" id="tg_soc_btn">
                                        <i class="mdi mdi-telegram"></i>
                                    </div>
                                    <div class="one_soc_send_btn" id="tw_soc_btn">
                                        <i class="mdi mdi-twitter"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>