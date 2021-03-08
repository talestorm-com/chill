{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module.css',0)|void}
{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0)|void}
    <div id="genre_result">
{if $result}

    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 m10 offset-m1">
                    <div class="row">
                        <div class="col s12 m10 offset-m">
                            <h1><span class="rib">Поиск <span class="bold">"{$result->search_query}"</span></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {if (count($result->soap))}
  
                        
     <div class="tag_list">
            <div class="container">
                <div class="row">
                    <div class="col s12 m10 offset-m1">
                        <div class="row">
                            {foreach from=$result->soap item='soap'}
                                <div class="col s12 l3">
    
        <div class="one_film_in_list chill-lenta-soap-new-{$soap->content_type}">
        
<a href='/Soap/{$soap->id}-{$soap->translit_name}' title="{$soap->name}">
<div class="film_left">
                
                    {if $soap->image}
                    <img src="/media/media_content_poster/{$soap->id}/{$soap->image}.SW_400H_520CF_1.jpg">
                    {else}
                    <img src="/media/fallback/1/media_content_poster.SW_400H_520CF_1.jpg" />
                    {/if}
                
            </div>
            </a>
        
            <a href='/Soap/{$soap->id}-{$soap->translit_name}' title="{$soap->name}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
        {/if}
    {else}
        <div class="white_error">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="inner_white_error">
                        Нет контента, соответствующего вашему поиску
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}
</div>
<script>
$('#owl-tag-news').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 3
        }
    }
});
$('#owl-tag-soap').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 2
        },
        600: {
            items: 3
        },
        1000: {
            items: 4
        }
    }
});
$('#owl-tag-gifs').owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        }
    }
});
$(".gif_load").each(function() {
            $(this).click(function() {
                    var a = $(this).data("gifa");
                    $(".gif_display").fadeIn(0);
                    $("body").css("overflow","hidden");
                    $(".gif_img").each(function() {
                        var b = $(this).data("gif");
                        if (b === a) {
                            $(".gif_img").fadeOut(0);
                            $(this).fadeIn(0);
                        }
                    });
                });
            });
             $(".gif_display").click(function() {
            $(".gif_display").fadeOut(0);
            $("body").css("overflow","scroll");
        });
</script>
