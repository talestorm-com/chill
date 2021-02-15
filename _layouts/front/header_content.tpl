<div class="FrontLayoutHeaderContent">
    <div class="FrontLayoutHeaderRow">
        <div class="FrontLayoutHeaderRowCell FrontLayoutHeaderRowCellMenus">
            <div class="FrontLayoutHeaderMenuButton">{menu alias='main' template='main_menu'}</div>
            <div class="FrontLayoutHeaderMenuLineMenu">{menu alias='header_free_space' template='free_space'}</div>
        </div>
        <div class="FrontLayoutHeaderRowCell FrontLayoutHeaderRowCellLogo">
            <div class="FrontLayoutHeaderLogo"><a href="/"><svg><use xlink:href="#common_logo"/></svg></a></div>   
        </div>
        <div class="FrontLayoutHeaderRowCell FrontLayoutHeaderRowCellAccount">
            <div class="fronLayoutAccountIconsWrapper">
                <div class="FrontLayoutAccountIconButtonButton" data-command="perform_search">
                    <svg><use xlink:href="#global_search" /></svg>
                </div>
                <div class="FrontLayoutAccountIconButtonButton">
                    <a href="/Cabinet/Favorite">
                        <svg><use xlink:href="#global_account" /></svg>
                    </a>
                </div>
                {*
                <div class="FrontLayoutAccountIconButtonButton">
                <a href="/Basket">
                <svg><use xlink:href="#global_cart" /></svg>
                <div class="FrontLayoutFilledCartMarker {if !$controller->basket->empty}FrontLayoutFilledCartMarkerFilled{/if}"><div class="FrontLayoutFilledCartMarkerInner"></div></div>
                </a>
                </div> *}
            </div>
        </div>
    </div>
</div>