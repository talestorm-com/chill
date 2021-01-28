<?php
/* Smarty version 3.1.33, created on 2020-06-01 20:53:40
  from '/var/VHOSTS/site/_views/mailer/mailer_common/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ed540a4e43f67_98727308',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4952daef0fc8d0f0374aa417dbed74ed7726eb14' => 
    array (
      0 => '/var/VHOSTS/site/_views/mailer/mailer_common/header.tpl',
      1 => 1591034017,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ed540a4e43f67_98727308 (Smarty_Internal_Template $_smarty_tpl) {
?><!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <meta charset="utf-8" />
        
            <style type="text/css">
                body{
                    background:#c8c8c8;
                }
                .ECMSMailerMessageOuter{
                    background:#c8c8c8;
                    width:100%;
                    font-size:14px;
                    padding-top:1em;
                    padding-bottom: 1em;
                }
                .ECMSMailerMessage{
                    background:white;
                    max-width:960px;
                    width:100%;
                    margin:0 auto;
                    padding:1em;
                    padding-top:0;
                } 
                .ECMSMailerMessageHeader{
                    margin-bottom:1em;
                    padding:0;
                    box-sizing: border-box;
                    border-bottom: 1px solid silver;
                }
                .ECMSMailerMessageHeaderLogo{
                    box-sizing: border-box;
                    line-height: 0;
                    text-align:center;
                    background:black;
                    padding:.5em;
                }
                .ECMSMailerMessageHeaderLogo img{
                    width:36px;height:36px;
                }
                table{
                    box-sizing: border-box;
                    border-collapse: collapse;
                    width:100%;
                    min-width:100%;      
                    border:1px solid dimgray;
                    margin-bottom:1.5em;    
                }
                table thead th{
                    background:dimgray;
                    color:white;
                    font-weight: normal;
                    text-align: left;
                    border:none;
                    border-left:2px solid white;
                    padding: .25em;
                    padding-left:.5em; 
                                    
                }
                table thead th:nth-child(1){
                    border-left:none;
                }
                table tbody tr{
                    background:white;
                }
                table tbody tr:nth-child(even){
                    background:whitesmoke;
                }
                table tbody td.td-text-right{
                    text-align:right;
                }
                  table tbody td.td-text-center{
                    text-align:center;
                }
                table tbody tr.table-total{
                    border-top:2px solid dimgray;
                    padding-top:.25em;
                    font-weight: bold;
                    text-align: right;
                }
            </style>
        
    </head>
    <body>
        <div class="ECMSMailerMessageOuter">
            <div class="ECMSMailerMessage <?php echo $_smarty_tpl->tpl_vars['wrapper_class']->value;?>
">
                <div class="ECMSMailerMessageHeader">
                    <div class="ECMSMailerMessageHeaderLogo">                    
                        <img src="cid:<?php echo $_smarty_tpl->tpl_vars['this']->value->inline_img(((string)dirname($_smarty_tpl->source->filepath))."/logo_grad.png",'image/png');?>
" />
                    </div>
                </div><?php }
}
