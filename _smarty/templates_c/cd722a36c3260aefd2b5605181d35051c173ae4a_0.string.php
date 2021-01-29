<?php
/* Smarty version 3.1.33, created on 2020-08-25 10:36:00
  from 'cd722a36c3260aefd2b5605181d35051c173ae4a' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f44bf60f22ea1_07955320',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f44bf60f22ea1_07955320 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.display_lent_v2.php','function'=>'smarty_function_display_lent_v2',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.content_block.php','function'=>'smarty_function_content_block',),));
?>
<h1 id="nine">Онлайн-кинотеатр Chill</h1>
<?php echo smarty_function_display_lent_v2(array(),$_smarty_tpl);?>

<?php echo smarty_function_content_block(array('alias'=>"scroll_to"),$_smarty_tpl);?>

<style>
  footer {
    display: none;
  }
</style>
<?php echo '<script'; ?>
>
  $(document).ready(function(){
    var a = window.location.pathname;
    console.log(a);
    $("#logo a").removeAttr("href");
    $("#logo a").click(function(){
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return false;
    });
  });
<?php echo '</script'; ?>
><?php }
}
