<div class="row">
<!--<div class="col s6 m4 l3 chill-lenta-item-new chill-lenta-item-new-static div_kv">
                                <div id="what_chill">
                                </div>
                            </div>-->
                            <div class="col s6 m4 l3 chill-lenta-item-new chill-lenta-item-new-static div_kv">
                            <a href="/profile">
                                <div id="what_chill_iz" style="background-image:url(/assets/chill/images/wicz_aa.jpg)">
                                </div>
                                </a>
                            </div>
                            <div class="col s6 m4 l3 chill-lenta-item-new chill-lenta-item-new-static div_kv">
                            <a href="/page/for_authors">
                                <div id="what_chill_iz">
                                </div>
                                </a>
                            </div>

    {assign var='index' value=-1}
    {foreach from=$this->items item='item'}
    {assign var='index' value=$index+1}
    {if $index>14}
    {assign var='index' value=1}
    {/if}
    


<!--
  {if $index === 2}
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s6 m4 l3 div_kv">
        <div class="author_block_main">
            
            <a href="/Profile">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/pl_a.gif)">
            </div>
            </a>

        </div>
    </div>
    {/if}
-->
{if $index === 3}
    <div class="chill-lenta-item-new chill-lenta-item-new-static col s6 m4 l3 div_kv">
        <div class="emo_block_main">
            <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/1307_emo.gif)">
            </div>
        </div>
    </div>
    {/if}




    {assign var='image_url' value="/media/{$item->get_image_url()}.SW_600H_400CF_1.jpg"}
    {assign var='image_urla' value="/media/{$item->get_image_url()}.SW_300H_300CF_1.jpg"}
    {assign var='image_url_sq' value="/media/{$item->get_image_url()}.SW_400H_400CF_1.jpg"}
    {assign var='image_url_qq' value="/media/{$item->get_image_url()}.SW_400H_520CF_1.jpg"}
    {if $item->content_type==='ctBANNER'}
    {if $item->id != 213}
    <div class="chill-lenta-item-new chill-lenta-item-new-{$item->content_type} col s6 m4 l3 div_kv">
    <div class="banner_collection">
        <a {if $item->banner_url !=''}href="{$item->banner_url}" target="_blank"{/if} class="ribbon_link_out">
        <div class="chill_main_lent_block" style="background-image:url({$image_urla})">
            </div>
            </a>
     
          
         
        </div>
    </div>
    {/if}
    {else if $item->content_type==='ctCOLLECTION'}
    <div class="chill-lenta-item-new chill-lenta-item-new-{$item->content_type} col s6 m4 l3 div_kv">
        <div class="lenta_collection">
            <a href="/collection/{$item->content_id}" title="{$item->name}">
                
                <div class="chill_main_lent_block" style="background-image:url({$image_urla})">
            </div>
            </a>
        </div>
    </div>
    {/if}
    {/foreach}
</div>