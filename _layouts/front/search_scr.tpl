{literal}
    <script>
        window.Eve = window.Eve || {};
        window.Eve.EFO = window.Eve.EFO || {};
        window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
        window.Eve.EFO.Ready.push(function () {
            jQuery(function () {
                jQuery("#search_input").keyup(function (e) {
                    if(e.key === 'Enter' || e.keyCode === 13) {
                    return;
                    }else{
                    if (void(0) === window.a3c3f7fb2ea154afa9a7d244337d49dba) {
                        window.a3c3f7fb2ea154afa9a7d244337d49dba = 0;
                    }
                    var a = jQuery(this).val();
                    if (a.length > 3) {
                        window.a3c3f7fb2ea154afa9a7d244337d49dba++;
                        var temp = window.a3c3f7fb2ea154afa9a7d244337d49dba;
                        jQuery("#quick_reult").empty();
                        jQuery.getJSON('/Public/API', {action: 'search', q: a})
                                .done(function (json) {
                                    if (window.a3c3f7fb2ea154afa9a7d244337d49dba === temp) {
                                        if (json.list.soap.length !== 0) {
                                            jQuery.each(json.list.soap, function (i, item) {
                                                jQuery("#quick_reult").append("<a href='/Soap/" + item.id + "' class='one_quick'><img src='https://chillvision.ru/media/media_content_poster/" + item.id + "/" + item.image + ".SW_60H_60CF_1.jpg'><p class='q_zag'>" + item.name + "</p><p class='q_podzag'><span class='q_p'>" + item.origin_country_name + ", </span><span class='q_d'>" + item.genre_name + "</span></p></a>");
                                            });
                                            $(".q_p").each(function(){
                                            var qText = $(this).text();
                                            if(qText ==='null, '){
                                            $(this).fadeOut(0);
                                            };
                                            });
                                            $(".q_d").each(function(){
                                            var qText = $(this).text();
                                            if(qText ==='null'){
                                            $(this).fadeOut(0);
                                            };
                                            });
                                        } else {
                                            jQuery("#quick_reult").append("<div class='no-res-q'>Нет результатов</div>");
                                        }
                                    } else {
                                    }
                                });
                    } else {
                        jQuery("#quick_reult").empty();
                    }
                    }
                });
            });
        });
    </script>
{/literal}