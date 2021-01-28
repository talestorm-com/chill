<script>
    (function () {
        var upload_log = {$OUT->getOpt('upload_log',[])|json_encode};
        var upload_error = {$OUT->getOpt('upload_error',[])|json_encode};
        var list = {$OUT->getOpt('list',[])|json_encode};
        var callback_name = '{$OUT->get('callback_name')}';
        try {
            window.opener[callback_name](JSON.stringify(upload_log), JSON.stringify(upload_error), JSON.stringify(list));
        } catch (ee) {
            try {
                window.parent[callback_name](JSON.stringify(upload_log), JSON.stringify(upload_error), JSON.stringify(list));
            } catch (eee) {

            }
        }
    })();
</script>