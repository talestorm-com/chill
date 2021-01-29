<?php
/* Smarty version 3.1.33, created on 2020-08-25 10:31:25
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/PageController/page_404_e.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f44be4dbdbce4_33333758',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ee3f8d7c0b6a9cabdcf080d08b523b5fc33cde19' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/PageController/page_404_e.tpl',
      1 => 1598340676,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f44be4dbdbce4_33333758 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="error_block">
<div class="container">
    <div class="row">
            <div class="col s12 m10 offset-m1"> 

<h1 class="error">404</h1>
<p class="error_text"><span>Страница не найдена. В ленте есть много интересного!</span></p>
</div>
</div>
    </div>
    </div>
    </div>
<div class="CommonPageWrapper <?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
PageWrapper Infopage_<?php echo $_smarty_tpl->tpl_vars['page']->value->properties->get('css');?>
 <?php if (!($_smarty_tpl->tpl_vars['page']->value->properties->get_filtered('system',array('Boolean','DefaultFalse')))) {?> CommonInfoPage <?php }?>">    
    <?php echo $_smarty_tpl->tpl_vars['page']->value->render_content();?>

</div>

<?php }
}
