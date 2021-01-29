{$OUT->meta->set_title($this->name)->set_description($this->info|truncate:100|strip_tags)->set_og_title($this->name)->set_og_image_support(true)->set_og_image_data($this->images->get_image_by_index(0)->context, $this->images->get_image_by_index(0)->owner_id,$this->images->get_image_by_index(0)->image)|void}
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
                                {$this->postdate_string} <span>{$this->posttime_string}</span>
                            </div>
                            {if $this->images->get_image_by_index(0)}
                            <img src="/media/media_content_poster/{$this->id}/{$this->images->get_image_by_index(0)->image}.SW_1088CF_1PR_hposter.jpg" />
                            {else}
                            <img src="/media/fallback/1/media_content_poster.SW_1088CF_1PR_hposter.jpg" />
                            {/if}
                            
                             <div class="top_header_a_news_stars aga-ratestars-{$this->ratestars}">
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
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
     <div id="list_news">
                <div class="container">
                    <div class="row">
                        <div class="col s12 l10 offset-l1">
                         <h3>Другие новости</h3
                            <div class="row">
                            {get_last_contents assign='content_list' q=3 ct='ctTEXT'}
{foreach from=$content_list item='co'}
                                <div class="col s12 m6 l4">
                                    <div class="one_news">
                                        <div class="one_news_date">
                                            {$co->news_post_date_string} <span>{$co->news_post_time_string}</span>
                                        </div>
                                        <a href="" title="Название новости">
                                            <div class="one_news_main">
                                                {if $co->image}
                                    <img src="/media/media_content_poster/{$co->id}/{$co->image}.SW_400CF_1PR_sq.jpg"  alt="{$co->name}">
                                    {else}
                                    <img src="/media/fallback/1/media_content_poster.SW_300H_300CF_1.jpg" />
                                    {/if}
                                                <div class="top_header_a_news_stars aga-ratestars-{$co->ratestars}">
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
        <i class="mdi mdi-star"></i>
    </div>
                                                <div class="one_news_title">
                                                        {$co->name}</li>

                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                {/foreach}
                                </div>
                                </div>
                                </div>
                                </div>
                                </div>




    {get_media_reviews q=5 id=$this->id assign='reviews'}
    {*
    параметры - assign - имя переменной в которую будут помещены отзывы, !!!!обязательный!!!!
    q - сколько отзывов грузить (по дефолту 5)
    id - id контента для которого искать отзывы
    для всех случаев, когда заданы неправильные параметры - вернется пустой список
    *}
    {if count($reviews)}
    <div id="list_reviews">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <h3>Отзывы</h3>
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
    {/if}
    <div id="a_news_footer">
        <div class="container">
            <div class="row">
                <div class="col s12 l10 offset-l1">
                    <div class="row">
                        <div class="col s12">
                            <div id="send_review" class="init_review_seqence" data-content-id="{$this->id}">Оставить отзыв</div>
                        </div>
                        <div class="col s12 right-align">
                            <div id="one_soc_send">
                                <span>Поделиться</span>
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
</div>
