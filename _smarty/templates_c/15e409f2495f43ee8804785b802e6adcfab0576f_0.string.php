<?php
/* Smarty version 3.1.33, created on 2020-12-15 16:32:56
  from '15e409f2495f43ee8804785b802e6adcfab0576f' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5fd8bb086e7bf6_58215681',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fd8bb086e7bf6_58215681 (Smarty_Internal_Template $_smarty_tpl) {
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
    console.log(a+'aaa');
    if(a != '/'){

    }else{
      $("#logo a").removeAttr("href");
      $("#logo a").click(function(){
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;

      });
    }
  });
<?php echo '</script'; ?>
><?php }
}
