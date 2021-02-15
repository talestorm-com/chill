<div id="flash">
    <noscript>Pixlr editor requires javascript.</noscript>
    <script>
        (function () {
        {literal}
                //<![CDATA[
                var version = 0;
                if (navigator.plugins != null && navigator.plugins.length > 0 && navigator.plugins["Shockwave Flash"]) {
                    version = navigator.plugins["Shockwave Flash"].description.split(" ")[2].split(".")[0];
                } else {
                    try {
                        var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
                        version = axo.GetVariable("$version").split(" ")[1].split(",")[0];
                    } catch (e) {
                    }
                }
                if (version >= 10) {
        {/literal}
                    var url = "/assets/flash/pixlr/editor.swf?email=&loc=ru&name=&locktarget=true&referrer=larro&locktitle=true&title={$OUT->get('image_title')}&target={$OUT->get('post_image_url')}&locktype=true&quality=100&redirect=false&method=POST&image={$OUT->get('get_image_url')}";
        {literal}
                    RunContent('width', '100%', 'height', '100%', 'src', url,
                            'movie', url, 'quality', 'high', 'wmode',
                            'window', 'devicefont', 'false', 'id', 'editor', 'bgcolor', '#606060',
                            'name', 'editor', 'menu', 'false', 'allowFullScreen', 'true', 'allowScriptAccess', 'always', 'allowFullScreenInteractive', 'true');
                } else {
                    document.write("<div id='noflash'>You need Flash player 10 or better to be able to use Editor - <a href='http://get.adobe.com/flashplayer'>Get Flash Player</a></div>");
                }

                function Generateobj(objAttrs, params, embedAttrs) {
                    var isIE = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
                    var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
                    var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;
                    var str = '';
                    if (isIE && isWin && !isOpera) {
                        str += '<object ';
                        for (var i in objAttrs) {
                            str += i + '="' + objAttrs[i] + '" ';
                        }
                        str += '>';
                        for (var i in params) {
                            str += '<param name="' + i + '" value="' + params[i] + '"/>';
                        }
                        str += '</object>';
                    } else {
                        str += '<embed ';
                        for (var i in embedAttrs) {
                            str += i + '="' + embedAttrs[i] + '" ';
                        }
                        str += ' />';
                    }
                    document.write(str);
                }

                function RunContent() {
                    var ret = GetArgs(arguments, "movie", "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000", "application/x-shockwave-flash");
                    Generateobj(ret.objAttrs, ret.params, ret.embedAttrs);
                }

                function GetArgs(args, srcParamName, classid, mimeType) {
                    var ret = new Object();
                    ret.embedAttrs = new Object();
                    ret.params = new Object();
                    ret.objAttrs = new Object();
                    for (var i = 0; i < args.length; i = i + 2) {
                        var currArg = args[i].toLowerCase();
                        switch (currArg) {
                            case "src":
                            case "movie":
                                ret.embedAttrs["src"] = args[i + 1];
                                ret.params[srcParamName] = args[i + 1];
                                break;
                            case "id":
                                ret.objAttrs[args[i]] = args[i + 1];
                                break;
                            case "width":
                            case "height":
                            case "name":
                            case "tabindex":
                                ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i + 1];
                                break;
                            default:
                                ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i + 1];
                        }
                    }
                    ret.objAttrs["classid"] = classid;
                    if (mimeType)
                        ret.embedAttrs["type"] = mimeType;
                    return ret;
                }
                //]]>

                //<![CDATA[
                function CallBack(b, a) {
                    debugger;
                    document.getElementById("editor")[b](a)
                }
                function Auth(a) {
                    debugger;
                }
                function Clipboard() {
                    debugger;
                    var m = document.getElementById("mole"), obj = frame(m).getElementById("clipboard");
                    if (obj == null) {
                        m.src = "clipboard.htm";
                    } else {
                        CallBack("clipboard", obj.GetClipboardImage());
                    }
                }
                function frame(iframe) {
                    debugger;
                    return(iframe.contentDocument || iframe.contentWindow.document || iframe.document);
                }
                function Login(a) {
                    debugger;
                }
                //]]>

                //<![CDATA[
                function buildUrl(base, args) {
                    debugger;
                    if (!args)
                        return base;
                    var res = [];
                    for (var key in args) {
                        if (args.hasOwnProperty(key)) {
                            res.push(key + '=' + encodeURI(args[key]));
                        }
                    }
                    return base + '?' + res.join('&');
                }

                function share(url, name, desc, service) {
                    debugger;
                    return;

                }

                function viewportSize() {
                    debugger;
                    var dims = {width: parseInt($(window).innerWidth()), height: parseInt($(window).innerHeight())};
                    return dims;
                }

                window.onload = function () {
                    //var flash = document.getElementById("flash");
                    //var viewport = viewportSize();
                    //flash.style.height = viewport.height + "px";
                }

                var adVisible = false;

                window.onresize = function () {
                    //var viewport = viewportSize();
                    //document.getElementById("flash").style.height = viewport.height + "px";
                    //document.getElementById("flash").style.width = viewport.width + "px";
                }

                function initDFP() {
                    debugger;
                    return false;

                }

                function fetchAmazon() {
                    debugger;
                    return false;

                }

                function injectAds() {
                    debugger;
                    return false;

                }
        {/literal}
                //]]>
            })();
            (function () {
                try {
                    window.opener['{$OUT->get('image_callback')}']();
                } catch (ee) {
                    try {
                        window.parent['{$OUT->get('image_callback')}']();
                    } catch (eee) {
                    }
                }
            })();
    </script>

</div>