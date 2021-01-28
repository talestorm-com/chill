(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(ready);
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, W = EFO.Widgets, NS = W.DataTree, APS = Array.prototype.slice;
        if (!U.isCallable(NS.MenuTree)) {
            function MenuTree() {
                return (MenuTree.is(this) ? this.init : MenuTree.F).apply(this, APS.call(arguments));
            }
            var F = MenuTree.xInheritE(NS.Tree), FP = F.prototype;
            FP._node_instance = function () {
                return NS.MenuNode();
            };
            NS.MenuTree = F;
        }
        if (!U.isCallable(NS.MenuNode)) {
            function MenuNode() {
                return (MenuNode.is(this) ? this.init : MenuNode.F).apply(this, APS.call(arguments));
            }
            var F = MenuNode.xInheritE(NS.Node), FP = F.prototype;

            FP.url = null;
            FP.visible = null;            
            FP.css_class = null;


            FP.on_import_descedants = function (data, tree) {
                this.url = U.NEString(data.url, null);
                this.visible = U.anyBool(data.visible, true);
                this.css_class=U.NEString(data.css_class,null);
                return NS.Node.prototype.on_import_descedants.apply(this, APS.call(arguments));
            };

            FP.on_export_descedant = function (o) {
                o.url = U.NEString(this.url, '');
                o.visible = U.anyBool(this.visible, true);
                o.css_class=U.NEString(this.css_class,null);
                return NS.Node.prototype.on_export_descedant.apply(this, APS.call(arguments));
            };

            NS.MenuNode = F;
        }
    }
})();