{literal}
    <script>
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            jQuery(function () {
                jQuery("#search_input").keyup(function () {
                    if (void(0) === window.a3c3f7fb2ea154afa9a7d244337d49dba) {
                        window.a3c3f7fb2ea154afa9a7d244337d49dba = 0;
                    }
                    var a = jQuery(this).val();
                    if (a.length > 2) {
                        window.a3c3f7fb2ea154afa9a7d244337d49dba++;
                        var temp = window.a3c3f7fb2ea154afa9a7d244337d49dba;
                        jQuery("#quick_reult").empty();
                        jQuery.getJSON('/Public/API', {action: 'search', q: a})
                                .done(function (json) {
                                    if (window.a3c3f7fb2ea154afa9a7d244337d49dba === temp) {
                                        if (json.list.soap.length !== 0) {
                                            jQuery.each(json.list.soap, function (i, item) {
                                                console.log(item);
                                                jQuery("#quick_reult").append("<a href='/Soap/" + item.id + "' class='one_quick'><img src='https://chillvision.ru/media/media_content_poster/" + item.id + "/" + item.image + ".SW_60H_60CF_1.jpg'><p class='q_zag'>" + item.name + "</p><p class='q_podzag'>" + {if item.origin_country_name !=null}item.origin_country_name{/if} + ", " + item.genre_name + "</p></a>");
                                            });
                                        } else {
                                            jQuery("#quick_reult").append("<div class='no-res-q'>Нет результатов</div>");
                                        }
                                    } else {
                                        console.log('requestId doesnot match - skip');
                                    }
                                });
                    } else {
                        jQuery("#quick_reult").empty();
                    }
                });
            });
        });
    </script>
{/literal}