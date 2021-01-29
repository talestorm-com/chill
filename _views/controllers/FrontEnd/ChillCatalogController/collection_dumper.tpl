
<pre style="color:black;background:white;padding:1em;position:relative;z-index:100;">{$collection|var_dump}</pre>
<div id="podbor">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <h1><span class="rib"><span class="bold">{$collection->name}</span></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="films_list_a">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        {foreach from=$collection item='soap'}
                        <div class="col s12 l3">
    
        <div class="one_film_in_list chill-lenta-soap-new-{$soap->content_type}">
        
<a href='/Soap/{$soap->id}' title="{$soap->name}">
<div class="film_left">
              
                    {if $soap->default_poster}
                    <img src="/media/media_content_poster/{$soap->id}/{$soap->default_poster}.SW_400H_520CF_1.jpg">
                    {else}
                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                    {/if}
                
            </div>
            </a>
        
            <a href='/Soap/{$soap->id}' title="{$soap->name}">
            <div class="film_right">
            <div class="film_right_in">
            <div class="in_film_right">
            {if $soap->track_language_name}
                                        <div class="film_right_lang ru_lang">
                                            <span>ru</span>
                                        </div>
                                        {if $soap->track_language_name !="ru"}
                                        <div class="film_right_lang {$soap->track_language_name}_lang">
                                            <span>{$soap->track_language_name}</span>
                                        </div>
                                        {/if}
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
                </div>
            </div>
        </div>
    </div>
</div>