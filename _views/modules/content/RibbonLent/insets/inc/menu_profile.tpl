<div class="chill-lenta-item-new chill-lenta-item-new-static col s12 l4">
    <div class="author_block_main">
        {if {get_user_auth_status}}
            <a href="/page/menu">
                <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/youchill.jpg);background-color:#ffce14">
                </div>
            </a>
        {else}
            <a href="/Profile">
                <div class="chill_main_lent_block" style="background-image:url(/assets/chill/images/pl_a.gif)">
                </div>
            </a>
        {/if}
    </div>
</div>