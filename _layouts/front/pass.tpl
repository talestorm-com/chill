

<script>
var a = localStorage.getItem("admin");
if(a != 'on'){
	$("main").fadeOut(0);
	$("header").fadeOut(0);
	$("footer").fadeOut(0);
	$("#line_out").fadeOut(0);
	$("#close_video").fadeOut(0);
	$("div#video_first").css("display","block!important");
}
</script>
