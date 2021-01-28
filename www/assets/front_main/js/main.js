$(document).ready(function() {
    var a = $("#map").width();
    $("#map").height(a);
    var b = $("#map_bg").width();
    $("#map_bg").height(b);
    var c = 10 / 320;
    var d = $(window).width();
    var n = d * c;
    if (d < 993) {
        $("html").css("font-size", n);
    }
    $(".open_zap").click(function() {
        $(".bg_bg").fadeIn(0);
        $("#send_form").fadeIn(0);
    });
    $(".bg_bg").click(function() {
        $(".bg_bg").fadeOut(0);
        $("#send_form").fadeOut(0);
        $("#login_form").fadeOut(0);

    });
    $("#login").click(function() {
        $(".bg_bg").fadeIn(0);
        $("#login_form").fadeIn(0);
    });
$("#send_test").click(function() {
        $(".bg_bg").fadeIn(0);
        $("#send_form").fadeIn(0);
    });
});
$(window).resize(function() {
    var a = $("#map").width();
    $("#map").height(a);
    var b = $("#map_bg").width();
    $("#map_bg").height(b);
    var c = 10 / 320;
    var d = $(window).width();
    var n = d * c;
    if (d < 993) {
        $("html").css("font-size", n);
    }
});
$(document).ready(function() {
    var a = $(this).scrollTop();
    $(".op1").each(function() {
        var aa = $(this).offset();
        var aaa = aa.top - 300;

        if (a > aaa) {
            $(this).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_1").delay(0).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_2").delay(500).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_3").delay(1000).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_4").delay(1500).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_5").delay(2000).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
        }
    });
});

$(window).scroll(function() {
    var a = $(this).scrollTop();
    $(".op1_1").each(function() {
var aa = $(this).offset();
        var aaa = aa.top - 100;
        if(a >aaa){
            $(this).css("opacity","1");
        }
    });
    $(".op1_2").each(function() {
var aa = $(this).offset();
        var aaa = aa.top - 100;
        if(a >aaa){
            $(this).css("opacity","1");
        }
    });
    $(".op1_3").each(function() {
var aa = $(this).offset();
        var aaa = aa.top - 100;
        if(a >aaa){
            $(this).css("opacity","1");
        }
    });
    $(".op1_4").each(function() {
var aa = $(this).offset();
        var aaa = aa.top - 100;
        if(a >aaa){
            $(this).css("opacity","1");
        }
    });
    $(".op1_5").each(function() {
var aa = $(this).offset();
        var aaa = aa.top - 100;
        if(a >aaa){
            $(this).css("opacity","1");
        }
    });
});

$(window).scroll(function() {
    var a = $(this).scrollTop();
    $(".op1").each(function() {
        var aa = $(this).offset();
        var aaa = aa.top - 300;

        if (a > aaa) {
            $(this).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_1").delay(500).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_2").delay(1000).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_3").delay(1500).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_4").delay(2000).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
            $(this).find(".op1_5").delay(2500).animate({
                opacity: 1,
            }, 1000, function() {
                // Animation complete.
            });
        }
    });
});


$(document).ready(function(){
	$("#go_2").click(function(){
		$("#q_2").fadeIn(0);
		$("#q_1").fadeOut(0);
	});
	$("#go_3").click(function(){
		$("#q_3").fadeIn(0);
		$("#q_2").fadeOut(0);
	});
	$("#go_4").click(function(){
		$("#q_4").fadeIn(0);
		$("#q_3").fadeOut(0);
	});
	$("#go_5").click(function(){
		$("#q_5").fadeIn(0);
		$("#q_4").fadeOut(0);
	});
	$("#menu_open_btn").click(function(){
$("#menu_mobile").toggle(0);
	});
})