<?php
/* Smarty version 3.1.33, created on 2020-08-04 15:42:37
  from '553f625b6864152d23a51ab443ba3fa15474838e' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f2957bdcab712_63562428',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f2957bdcab712_63562428 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.content_block.php','function'=>'smarty_function_content_block',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.display_lent_v2.php','function'=>'smarty_function_display_lent_v2',),));
echo smarty_function_content_block(array('alias'=>"video"),$_smarty_tpl);?>

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
    $("#logo a").removeAttr("href");
    $("#logo a").click(function(){
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return false;
    });
  });
<?php echo '</script'; ?>
><?php }
}
