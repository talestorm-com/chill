<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <meta charset="utf-8" />
        {literal}
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
        {/literal}
    </head>
    <body>
        <div class="ECMSMailerMessageOuter">
            <div class="ECMSMailerMessage {$wrapper_class}">
                <div class="ECMSMailerMessageHeader">
                    <div class="ECMSMailerMessageHeaderLogo">                    
                        <img src="cid:{$this->inline_img("{$smarty.current_dir}/logo_grad.png",'image/png')}" />
                    </div>
                </div>