<div id="sticker_list_wrapper">
    <div id="sticker_list_wrapper-inside">
        <div id="sticker_list_window">
            <div id="close_sticker_list"><i class="mdi mdi-close"></i></div>
            <div class="sticker_list_inner">
                {foreach from=$stickers item='sticker'}
                    <div class="one-sticker-from-list" data-id="{$sticker->id}" data-url="{$sticker->cdn_url}" data-title="{$sticker->name}">
                        <div class="one-sticker-from-list-inner">
                            <img src="//{$sticker->cdn_url}" />
                        </div> 
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
