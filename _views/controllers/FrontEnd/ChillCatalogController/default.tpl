<div id="a_one_cat">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col l10 s12">
                            <h1>Каталог</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {assign var='last_x_items' value=$controller->last_contents()}
    <div id="cat_slide">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div id="slider_cat" class="owl-carousel">
                        {foreach from=$last_x_items item='item' name=foo}
                        {if $smarty.foreach.foo.index % 2 == 0}
                        <div>
                            <a href='/Soap/{$item->id}' title="{$item->name}">
                                <div class="one_film_janr">
                                    {if $item->image}
                                    <img src="/media/media_content_poster/{$item->id}/{$item->image}.SW_1200H_400CF_1PR_hposter.jpg" alt="{$item->name}">
                                    {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_1200H_400CF_1PR_hposter.jpg" />
                                    {/if}
                                    <div class="one_film_janr_title">{$item->name}</div>
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-{$item->ratestars}">
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="films_filter">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l9">
                            <div class="col_1"></div>
                            <div id="filters" class="row">
                                <div class="col s12 l4">
                                    <label for="select_country">Страна</label>
                                    <select id="select_country">
                                        <option disabled selected>Выберите страну</option>
                                        {foreach from=$country_list item='c'}
                                        
                                        <option value="{$c.id}">{$c.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="col s12 l4" id="emo_block">
                                    <label for="select_emoji">Эмоции</label>
                                    <select id="select_emoji">
                                        <option disabled selected>Выберите эмоцию</option>
                                        {foreach from=$emoji_list item='c'}
                                        
                                        <option value="{$c.id}" data-icon="/media/emojirenderer/{$c.id}/emoji.SW_45H_45CF_1B_ffffff.jpg">{$c.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="col s12 l4">
                                    <label for="select_genre">Жанр</label>
                                    <select id="select_genre">
                                        <option disabled selected>Выберите жанр</option>
                                        {foreach from=$genre_list item='c'}
                                        <option value="{$c.id}">{$c.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 l3">
                            <div class="center-align">
                                <a id="filter_open" class="back_back">Фильтры <i class="mdi mdi-chevron-up"></i></a>
                            </div>
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
                    {foreach from=$rows item='row'}
                    {if count($row->soap) || count($row->video)}
                    <div class="one_films_group">
                        <div class="row">
                            <div class="col s8">
                                <h2>{$row->genre_name}</h2>
                            </div>
                            <div class="s4">
                                <div class="right-align">
                                    <a href="/search/by_genre/{$row->genre_id}" class="back_back">посмотреть все</a>
                                </div>
                            </div>
                        </div>
                        <div class="owl-carousel owl-carousela" id="{$row->genre_id}">
                            {foreach from=$row->soap item='soap'}
                            <a href='/Soap/{$soap->id}' title="{$soap->name}">
                                <div class="one_film_in_list">
                                    {if $soap->image}
                                    <img src="/media/media_content_poster/{$soap->id}/{$soap->image}.SW_400CF_1PR_vposter.jpg" alt="{$soap->name}">
                                    {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                    {/if}
                                    <div class="one_film_in_list_title">{$soap->name}</div>
                                    <div class="top_one_news_stars top_top_stars aga-ratestars-{$soap->ratestars}">
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                        <i class="mdi mdi-star"></i>
                                    </div>
                                </div>
                            </a>
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
<script>
$('.owl-carousela').owlCarousel({
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

$('#slider_cat').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    items: 1
});

$("#select_country").change(function() {
    var selectcountry = $(this).children("option:selected").val();
    window.location.assign('/search/by_origin/' + selectcountry);
});
$("#select_emoji").change(function() {
    var selectemoji = $(this).children("option:selected").val();
    window.location.assign('/search/by_emoji/' + selectemoji);

});
$("#select_genre").change(function() {
    var selectgenre = $(this).children("option:selected").val();
    window.location.assign('/search/by_genre/' + selectgenre);
});
$("#filter_open").click(function() {
    $("#filters").fadeToggle(0);
});
</script>