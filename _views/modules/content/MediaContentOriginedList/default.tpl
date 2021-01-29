{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module.css',0)|void}
{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0)|void}
<div id="genre_result">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <h1><span class="rib">Страна: <span class="bold">{$this->origin_name}</span></span></h1>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if (count($this->soap))}
        <div class="tag_list">
            <div class="container">
                <div class="row">
                    <div class="col s12 m10 offset-m1">
                        <div class="row">
                            {foreach from=$this->soap item='soap'}
                                <div class="col s12 l3">
                                    
                                        <div class="one_film_in_list">
                                        <div class="film_left">
                                        <a href='/Soap/{$soap->id}' title="{$soap->name}">
                                            {if $soap->image}
                                                <img src="/media/media_content_poster/{$soap->id}/{$soap->image}.SW_400H_400CF_1PR_sq.jpg">
                                            {else}
                                                <img src="/media/fallback/1/media_content_poster.SW_400H_400CF_1PR_sq.jpg" />
                                            {/if}
                                            </a>
                                            </div>
                                            <div class="film_right">
                <div class="one_film_in_list_title_a truncate">{$soap->name}</div>
                <div class="one_film_prop">Страна: <span>{if $soap->origin_country_name}{$soap->origin_country_name}{/if}</span></div>
                <div class="one_film_prop">Жанр: <span>{if $soap->genre_name}{$soap->genre_name}{/if}</span></div>
                <div class="one_film_prop"><span>{if $soap->seasons_count}{$soap->seasons_count}{/if} сезон ({if $soap->series_count}{$soap->series_count}{/if} серий)</span></div>
            </div>
            <div class="film_right_lang">
                <span>{if $soap->track_language_name}{$soap->track_language_name}{/if}</span>
            </div>
                                        </div>
                                    </a>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{else}
    <div class="white_error">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="inner_white_error center-align">
                        Нет сериалов, соответствующих выбранным параметрам
                    </div>
                </div>
            </div>
        </div>
    </div>

{/if}
</div>


