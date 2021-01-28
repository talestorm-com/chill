<!DOCTYPE html>
<html>
    <head>
        <title>Editor</title>        
        <style>
            {literal}
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
            {/literal}
            /*]]>*/
        </style>
    </head>
    <body>       
        {$OUT->get('page_content')}        
    </body>
</html>
