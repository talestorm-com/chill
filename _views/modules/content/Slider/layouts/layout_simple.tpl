{if $this->images && $this->images->has_images}
    {$OUT->add_script("/assets/vendor/anime/anime.min.js", 0, true)|void}
    {$OUT->add_script("/assets/js/front/slider/slider.core.js", 0, true)|void}
    {$OUT->add_script("/assets/js/front/slider/slider.layout_simple.js", 0, true)|void}
    {assign var="slider_uuid" value="a{$OUT->get_euid('slider_core')}"}
    <div class="ModuleSliderLayoutMarkup" id="{$slider_uuid}">
        <div class="ModuleSliderLayoutPreloader" id="{$slider_uuid}_preloader" >{include {$controller->common_templtes("preloader")}}</div>
    </div>
    <script>
        {literal}
            (function () {
                var uuid = "{/literal}{$slider_uuid}{literal}";
                var images = {/literal}{$this->images->marshall()|json_encode}{literal};
                window.Eve = window.Eve || {};
                window.Eve.SLIDER_CORE_READY = window.Eve.SLIDER_CORE_READY || [];
                window.Eve.SLIDER_CORE_READY.push(function () {
                    window.Eve.SLIDER_CORE(uuid, 'simple', images,{/literal}{$this->timeout}{literal},{/literal}{$this->crop|intval}{literal},{/literal}{$this->crop_fill|intval}{literal}, '{/literal}{$this->background}{literal}',{/literal}{$this->properties->kvs|json_encode}{literal});
                });
            })();
        {/literal}
    </script>
{/if}