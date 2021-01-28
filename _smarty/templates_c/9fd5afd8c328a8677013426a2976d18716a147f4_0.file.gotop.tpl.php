<?php
/* Smarty version 3.1.33, created on 2020-08-12 11:35:53
  from '/var/VHOSTS/site/_layouts/front/gotop.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f33a9e941b190_09815932',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9fd5afd8c328a8677013426a2976d18716a147f4' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/front/gotop.tpl',
      1 => 1597221347,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f33a9e941b190_09815932 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="go_top">
    <div id="go_top_in">
        <i class="mdi mdi-menu-up"></i> Вверх
    </div>
</div>
<style>

</style>

<?php echo '<script'; ?>
>
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
<?php echo '</script'; ?>
>
<?php }
}
