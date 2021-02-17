<div id="go_top">
    <div id="go_top_in">
        <i class="mdi mdi-menu-up"></i> Вверх
    </div>
</div>
<style>

</style>
{literal}
<script>
$(document).ready(function() {
    var a = $("#logo").position();
    var b = a.left;
    var c = b-40;
    $("#go_top").width(c);
    if (b > 130) {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                $('#go_top').fadeIn(200);
            } else {
                $('#go_top').fadeOut(200);
            }
        });
    } else {
        $("#go_top").fadeOut(0);
    }
});
$(window).resize(function() {
    var a = $("#logo").position();
    var b = a.left;
    var c = b-40;
    $("#go_top").width(c);
    if (b > 130) {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                $('#go_top').fadeIn(200);
            } else {
                $('#go_top').fadeOut(200);
            }
        });
    } else {
        $("#go_top").fadeOut(0);
    }
});
$("#go_top").click(function() {
	$('html,body').animate({scrollTop: 0}, 500);
});
</script>
{/literal}