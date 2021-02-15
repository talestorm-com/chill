<!DOCTYPE html>
<html lang="ru">
    <head>
        <script>
            {literal}
                (function () {
                    var json_obj ={/literal}{$output_json}{literal};
                    var callback_name = "{/literal}{$jsonp_callback}{literal}";
                    try {
                        window.opener[callback_name](JSON.stringify(json_obj));
                    } catch (e) {
                        console.log(e);
                        try {
                            window.parent[callback_name](JSON.stringify(json_obj));
                        } catch (ee) {
                            console.log(ee);
                        }
                    }
                })();
            {/literal}
        </script>
    </head>
    <body>request done</body>
</html>