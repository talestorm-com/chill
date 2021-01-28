<?php
/* Smarty version 3.1.33, created on 2020-06-21 19:41:05
  from '/var/VHOSTS/site/_layouts/pixlr.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5eef8da1227d70_60981625',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b2490a982356fe36e8ddf4f870875f390e812b9c' => 
    array (
      0 => '/var/VHOSTS/site/_layouts/pixlr.tpl',
      1 => 1557319384,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eef8da1227d70_60981625 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
    <head>
        <title>Editor</title>        
        <style>
            
                /*<![CDATA[*/
                html, body { margin:0px; padding:0px; background: whitesmoke; overflow: hidden; height: 100%;box-sizing:border-box; }
                body { padding-right: 0px; }
                #noflash {padding-top:30px;margin:0 auto;width:600px;}
                #flash {float: left;width: 100%;height: 100%;box-sizing:border-box;}
                .ad-wrap {position: absolute;display: none;right: 0;height: 100%;padding: 20px;background: white;}
                .adslot {margin-bottom: 20px;}
                @media only screen and (max-width: 1023px) {
                    body { padding-right: 0px!important; }
                    .ad-wrap { display: none; }
                    .adslot { display: none; }
                }
                @media only screen and (max-height: 890px) {
                    .ad-wrap .adslot:last-child {
                        display: none;
                    }
                }
            
            /*]]>*/
        </style>
    </head>
    <body>       
        <?php echo $_smarty_tpl->tpl_vars['OUT']->value->get('page_content');?>
        
    </body>
</html>
<?php }
}
