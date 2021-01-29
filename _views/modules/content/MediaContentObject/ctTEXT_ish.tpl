<div id="a_one_news">
    <div id="main_header">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12 l2 offset-l10">
                            <div class="right-align">
                                <a href="javascript:history.back()" class="back_back">Назад</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="a_news">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="header_a_news">
                        <div class="top_header_a_news_main">
                            <div class="top_one_news_date">
                                20.04.2020 <span>13:38</span>
                            </div>
                            {if $this->images->get_image_by_index(0)}
                            <img src="/media/media_content_poster/{$this->id}/{$this->images->get_image_by_index(0)->image}.SCF_1PR_hposter.jpg" />
                            {else}
                            <img src="/media/fallback/1/media_content_poster.SW_100H_100CF_1.jpg" />
                            {/if}
                            <div class="top_header_a_news_stars">
                                <i class="mdi mdi-star active_star"></i>
                                <i class="mdi mdi-star active_star"></i>
                                <i class="mdi mdi-star active_star"></i>
                                <i class="mdi mdi-star active_star"></i>
                                <i class="mdi mdi-star"></i>
                            </div>
                            <div class="top_header_a_news_title">
                                {$this->name}
                            </div>
                        </div>
                    </div>
                    <div class="body_a_news">
                        {$this->intro}
                        {$this->info}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--             <div id="list_news">
                <div class="container">
                    <div class="row">
                        <div class="col s12 l10 offset-l1">
                            <h3>Похожие новости</h3>
                            <div class="row">
                                <div class="col s12 m6 l4">
                                    <div class="one_news">
                                        <div class="one_news_date">
                                            13.03.2020 <span>13:38</span>
                                        </div>
                                        <a href="" title="Название новости">
                                            <div class="one_news_main">
                                                <img src="images/kv_pic.png">
                                                <div class="one_news_stars">
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </div>
                                                <div class="one_news_title">
                                                    Netflix, Apple и Disney приостановили съемки своих проектов из-за ситуации с коронавирусом
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l4">
                                    <div class="one_news">
                                        <div class="one_news_date">
                                            13.03.2020 <span>13:38</span>
                                        </div>
                                        <a href="" title="Название новости">
                                            <div class="one_news_main">
                                                <img src="images/kv_pic.png">
                                                <div class="one_news_stars">
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </div>
                                                <div class="one_news_title">
                                                    Netflix, Apple и Disney приостановили съемки своих проектов из-за ситуации с коронавирусом
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l4">
                                    <div class="one_news">
                                        <div class="one_news_date">
                                            13.03.2020 <span>13:38</span>
                                        </div>
                                        <a href="" title="Название новости">
                                            <div class="one_news_main">
                                                <img src="images/kv_pic.png">
                                                <div class="one_news_stars">
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star active_star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </div>
                                                <div class="one_news_title">
                                                    Netflix, Apple и Disney приостановили съемки своих проектов из-за ситуации с коронавирусом
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <div class="col l6 s12">
                                    <div id="send_review">Оставить отзыв</div>
                                </div>
                                <div class="col s12 l6 right-align">
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
            </div> -->
</div>
<div style="color:white">
    Простые свойства ($this->xxx):<br>
    id - id ,<br>
    content_type - всегда ctTEXT (новость),<br>
    enabled - включено,<br>
    common_name - заголовок внутр.<br>
    name - заголовок,<br>
    intro - интро <br>
    info - описание (текст)<br>
    default_poster - id первого изображения<br><br><br><br><br><br>
    тоесть {literal}{$this->id} = {/literal}{$this->id}<br><br><br>
    Сложные свойства<br>
    <b>images</b> - список изображений<br>
    {literal}
    <pre>
        {if (count($this->images))}
        {count($this->images)}
        {/if}:
        </pre>
    {/literal}
    {if (count($this->images))}
    {count($this->images)}
    {/if}<br>
    {literal}
    <pre>
        {foreach from=$this->images item='image'}
        {$image->image}  
        {/foreach}:
        </pre>
    {/literal}
    {foreach from=$this->images item='image'}
    {$image->image}
    {/foreach}<br>
    Изображение с конкретным номером:<br>
    {literal}
    <pre>{$this->images->get_image_by_index(0)|var_dump}</pre>{/literal}:
    <pre>{$this->images->get_image_by_index(0)|var_dump}</pre><br>
    {literal}
    <pre>{$this->images->get_image_by_index(100)|var_dump}</pre>{/literal}:
    <pre>{$this->images->get_image_by_index(100)|var_dump}</pre>
    <br>
    url изображения строится по следующей схеме:<b>/media/<span style='color:red'>context</span>/<span style='color:red'>owner_id</span>/<span style='color:red'>image_id</span>.<span style='color:blue'>spec</span>.jpg</b><br>
    context - контекст изображения. в данном случае везде почти контекст - media_content_poster<br>
    owner_id - идентификатор новости<br>
    image_id - идентификатор непосредственно изображения (fe034a8a413dabeac8865160fad72f40 или что-то в этом роде).<br>
    spec - спецификация изображения.<br>
    строится по следующим правилам: <b>S</b><span style="color:red">W_250</span><span style='color:green'>H_100</span><span style='color:blue'>CF_1</span>
    <span style='color:magenta'>B_ffffff</span><span style='color:crimson'>PR_sq</span><br>
    <span style='color:red'>W_250</span> - ширина - 250px<br>
    <span style='color:green'>H_100</span> - высота - 100px<br>
    <span style='color:blue'>CF_1</span> - кроп с заполнением (без полей)<br>
    <span style='color:magenta'>B_ffffff</span> - цвет подложки (при CF_1 не имеет смысла)<br>
    <span style='color:crimson'>PR_sq</span> - пресет (sq,hposter,vposter)<br>
    Параметры можно опускать, но нельзя менять их порядок - это приведет к редиректу на url с правильным порядком.<br>
    Естественно, все параметры не нужны - если заданы ширина и высота, то PR не имеет смысла и тд<br><br>
    Если изображения нет, но нужно что-то показать - можно воспользоваться фаллбаком.<br>
    Фаллбак строится по схеме: /media/fallback/1/<span style="color:red">context</span>.spec.jpg<br>
    Где контекст - это контекст изображения (фаллбаки в админке), spec - спецификатор.<br><br><br><br>
    Тоесть чтобы показать изображение с определенным индексом:<br>
    <pre>
        {literal}
            {if $this->images->get_image_by_index(0)}
            &LT; img src="/media/media_content_poster/{$this->id}/{$this->images->get_image_by_index(0)->image}.SW_100H_100CF_1.jpg" /&GT;
            {else}
            &LT;img src="/media/fallback/1/media_content_poster.SW_100H_100CF_1.jpg" / &GT;
            {/if}
        {/literal}
    </pre>
    {if $this->images->get_image_by_index(0)}
    <img src="/media/media_content_poster/{$this->id}/{$this->images->get_image_by_index(0)->image}.SW_100H_100CF_1.jpg" />
    {else}
    <img src="/media/fallback/1/media_content_poster.SW_100H_100CF_1.jpg" />
    {/if}
    <br><br><br><br><br>
    <b>Теги</b> - список тегов<br>
    {literal}
    <pre>
        {foreach from=$this->tags item='tag'}
            <div>
                id:{$tag->id}<br>
                name:{$tag->name}
            </div>
        {/foreach}:
        </pre>
    {/literal}
    {foreach from=$this->tags item='tag'}
    <div>
        id:{$tag->id}<br>
        name:{$tag->name}
    </div>
    {/foreach}
    <br><br><br>
</div>
<h1 style="color:white">Полный дамп</h1>
<pre style="color:black;font-family:monospace;background:white">{$this|var_dump}</pre>