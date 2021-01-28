<div class="global_loader_backdrop_frame059f1e50b32c4f5882b2c2c3df1904f0" style="display:none" id="global_wait_loader_059f1e50b32c4f5882b2c2c3df1904f0">
    <div class="global_loader_inner_frame059f1e50b32c4f5882b2c2c3df1904f0">
        <div class="global_loader_rotating_element059f1e50b32c4f5882b2c2c3df1904f0">
            {include './loader.svg'}
        </div>
    </div>
</div>
<script>
    {literal}
        (function () {
            window.show_global_loader = function () {
                try {
                    document.getElementById('global_wait_loader_059f1e50b32c4f5882b2c2c3df1904f0').style.display = 'block';
                } catch (e) {

                }
            };
            window.hide_global_loader = function () {
                try {
                    document.getElementById('global_wait_loader_059f1e50b32c4f5882b2c2c3df1904f0').style.display = 'none';
                } catch (e) {

                }
            };
        })();
    {/literal}
</script>