<div class="{$controller->MC}form">
    <div class="{$controller->MC}forminner">
        <div class="{$controller->MC}formrow">
            <div class="{$controller->MC}formcell {$controller->MC}formcellsticker ">
                <div class="{$controller->MC}formcellheader ">Стикер:</div>
                <div class="{$controller->MC}formcellbody" id="{$controller->MC}sticker_place"></div>
                <input type="hidden" id="{$controller->MC}sticker_field">
            </div>
            <div class="{$controller->MC}formcell {$controller->MC}formcelltext ">
                <div class="{$controller->MC}formcellheader ">Текст:</div>
                <div class="{$controller->MC}formcellbody"><textarea id="{$controller->MC}text"></textarea></div>
            </div>
        </div>
    </div>
    <div class="{$controller->MC}formfooter">
        <div class="{$controller->MC}formfooterbutton" id="{$controller->MC}send">Отправить</div>
    </div>
</div>
