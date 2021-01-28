<?php
/* Smarty version 3.1.33, created on 2020-08-25 19:32:48
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/comment_w_sticker.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f453d30c36ac2_52893285',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '096d7ff208093c776087c6bcd7935d327175b6f9' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/ComChillController/comment_w_sticker.tpl',
      1 => 1598373160,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f453d30c36ac2_52893285 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
_comment_with_sticker">
    <div class="<?php echo $_smarty_tpl->tpl_vars['controller']->value->MC;?>
_comment_with_sticker_inner">
        <div class="comment-header">
            <div class="row">
                <div class="col s6">
                    <p class="com-aut"><?php echo $_smarty_tpl->tpl_vars['comment']->value->author;?>
</p>
                </div>
                <div class="col s6">
                    <p class="com-date"><?php echo $_smarty_tpl->tpl_vars['comment']->value->datum->format('d.m.Y');?>
</p>
                </div>
            </div>
        </div>
        <div class="sticker-panel"><img src="//<?php echo $_smarty_tpl->tpl_vars['comment']->value->sticker_url;?>
"></div>
        <div class="comment-body"><?php echo $_smarty_tpl->tpl_vars['comment']->value->content;?>
</div>
        <div class="votepanel">
            <a href="#" class="comment-vote-minus" data-id="<?php echo $_smarty_tpl->tpl_vars['comment']->value->id;?>
"><i class="mdi mdi-heart-broken-outline"></i></a>
            <span><?php echo $_smarty_tpl->tpl_vars['comment']->value->rating;?>
 </span>
            <a href="#" class="comment-vote-plus" data-id="<?php echo $_smarty_tpl->tpl_vars['comment']->value->id;?>
"><i class="mdi mdi-heart"></i></a>
        </div>
    </div>
</div>
<?php if ($_smarty_tpl->tpl_vars['comment']->value->r != '') {?>
<div class="comment_res">
<div class="comment_logo">
<img src="/assets/chill/images/logo_grad_bg.png">
</div>
<div class="comment_res_in">
<h4>Ответ от Chill</h4>
<div class="comment_res_in_text">
<?php echo $_smarty_tpl->tpl_vars['comment']->value->r;?>

</div>
</div>
</div>
<?php }
}
}
