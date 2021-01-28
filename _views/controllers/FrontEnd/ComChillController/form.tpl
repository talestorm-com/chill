<div id="form_com_chill">
    <div class="{$controller->MC}form">
        <div class="row">
            <div class="col s12">
                <div class="{$controller->MC}formcellbody"><textarea id="{$controller->MC}text" placeholder="Напишите отзыв"></textarea></div>
            </div>
            <div class="col s6 l3">
                <div class="{$controller->MC}formfooterbutton" id="{$controller->MC}send">Отправить</div>
            </div>
            <div class="col s3 offset-s3 l2 offset-l1 right-align">
                <div class="{$controller->MC}formcellbody" id="{$controller->MC}sticker_place">
                    <i class="mdi-tooltip-image-outline mdi"></i>
                </div>
                <input type="hidden" id="{$controller->MC}sticker_field">
                <input type='hidden' id='{$controller->MC}token' value='{$controller->mk_csrf('comchill')}' />
            </div>
        </div>
    </div>
</div>