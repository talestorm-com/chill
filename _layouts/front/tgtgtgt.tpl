<script>
    (function () {
        window.Eve = window.Eve ||{};
        window.Eve.EFO = window.Eve.EFO ||{};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            window.Eve.EFO.Events.GEM().on("LOGIN_SUCCESS", window, function () {
                console.log("after login");
            });
        });
        window.Eve.EFO.Alert().set_text("текст алерта").set_title("Заголовок алерта").set_close_btn(true)
                .set_style("red").set_timeout(5000)
                .set_icon("!").set_image("!").set_callback(window,function(){
                    alert("alert is closed");
                }).show();
    })();
</script>
