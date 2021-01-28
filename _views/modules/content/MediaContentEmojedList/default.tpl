{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module.css',0)|void}
{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0)|void}
<div id="genre_result">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <!--<h1><span class="rib">Эмоция: <span class="bold">{$this->emoji_name}</span></span></h1>-->
                            <div id="big_smile"><img src="/media/SMILE/{$this->emoji_id}/smile.SW_60H_60.png"></div>
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
    
        <div class="one_film_in_list chill-lenta-soap-new-{$soap->content_type}">
        
<a href='/Soap/{$soap->id}' title="{$soap->name}">
<div class="film_left">
                
                    {if $soap->image}
                    <img src="/media/media_content_poster/{$soap->id}/{$soap->image}.SW_400H_520CF_1.jpg">
                    {else}
                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                    {/if}
                
            </div>
            </a>
        
            <a href='/Soap/{$soap->id}' title="{$soap->name}">
            <div class="film_right">
            <div class="film_right_in">
            <div class="in_film_right">
            {if $soap->free}
            <div class="film_right_free">
                <span>Free</span>
            </div>
            {/if}
            <div class="film_right_lang ru_lang">
                <span>ru</span>
            </div>
            {if $soap->track_language_name !="ru"}
                                        <div class="film_right_lang {$soap->track_language_name}_lang">
                                            <span>{$soap->track_language_name}</span>
                                        </div>
                                        {/if}
            </div>
                <div class="one_film_in_list_title_a">{$soap->name}</div>
                <div class="one_film_prop"><span>{if $soap->origin_country_name}{$soap->origin_country_name}{/if}</span>, <span>{if $soap->genre_name}{$soap->genre_name}{/if}</span></div>
                <div class="one_film_prop"><span>{if $soap->seasons_count}{$soap->seasons_count}{/if} {TT  t='season'} ({if $soap->series_count}{$soap->series_count}{/if} {TT  t='series_lent'})</span></div>
            </div>
            </div>
        </div>
       </a>
    </div>
                            {/foreach}
                        </div>
                        <div style="color:white;padding:1em;">{if $this->additional_content}{$this->additional_content}{/if}</div>
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


