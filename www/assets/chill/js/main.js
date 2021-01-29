// $(document).ready(function() {
//     var wind = $(window).width();
//     var ls = $("#login_signup").width();
//     var wind_ls = (wind - ls) / 2;
//     $("#login_signup").css("margin-left", wind_ls);
//     $("#close_login_signup").click(function() {
//         $("#login_signup").fadeOut(0);
//         $("#bg_bg").fadeOut(0);
//     });
//     $("#bg_bg").click(function() {
//         $("#login_signup").fadeOut(0);
//         $("#bg_bg").fadeOut(0);
//     });
//     $("#login_btn").click(function() {
//         $("#login_signup").fadeIn(0);
//         $("#bg_bg").fadeIn(0);
//     });
//     $("#no_acc a").click(function(){
//         $("#login_block").fadeOut(0);
//         $("#signup_block").fadeIn(0);
//     });
//     $("#to_login_block").click(function(){
//         $("#login_block").fadeIn(0);
//         $("#signup_block").fadeOut(0);
//     });
// });
// $(window).resize(function() {
//     var wind = $(window).width();
//     var ls = $("#login_signup").width();
//     var wind_ls = (wind - ls) / 2;
//     $("#login_signup").css("margin-left", wind_ls);
// });
$(document).ready(function() {
    $('select').material_select();
    var a = window.location.pathname;
    $("#desktop_menu li").removeClass("active");
    $("#desktop_menu li").each(function() {
        var b = $(this).find("a").attr("href");
        if (a === b) {
            $(this).addClass("active");
        }
    });
    $("#menu_menu_mobile a").removeClass("active_mobile_menu");
    $("#menu_menu_mobile a").each(function() {
        var b = $(this).attr("href");
        if (a === b) {
            $(this).addClass("active_mobile_menu");
        }
    });
    var ww = $(window).width();
    var wh = $(window).height();
    if (ww < 993) {
        if (ww > wh) {
            $(".film_left").each(function() {
                $(this).addClass("film_left_h");
            });
            $(".film_right").each(function() {
                $(this).addClass("film_right_h");
            });
        } else {
            $(".film_left").each(function() {
                $(this).removeClass("film_left_h");
            });
            $(".film_right").each(function() {
                $(this).removeClass("film_right_h");
            });
        }
    } else {
        $(".film_left").each(function() {
            $(this).removeClass("film_left_h");
        });
        $(".film_right").each(function() {
            $(this).removeClass("film_right_h");
        });
    }
});
$(window).resize(function() {
    var ww = $(window).width();
    var wh = $(window).height();
    if (ww < 993) {
        if (ww > wh) {
            $(".film_left").each(function() {
                $(this).addClass("film_left_h");
            });
            $(".film_right").each(function() {
                $(this).addClass("film_right_h");
            });
        } else {
            $(".film_left").each(function() {
                $(this).removeClass("film_left_h");
            });
            $(".film_right").each(function() {
                $(this).removeClass("film_right_h");
            });
        }
    } else {
        $(".film_left").each(function() {
            $(this).removeClass("film_left_h");
        });
        $(".film_right").each(function() {
            $(this).removeClass("film_right_h");
        });
    }
})