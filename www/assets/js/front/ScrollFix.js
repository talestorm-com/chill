(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
    window.Eve.EFO.Ready.push(function(){
        jQuery(ready);
    });
    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice, H = null, AR = null;
        if (!U.isCallable(E.scroll_fix)) {
            init_plugin();
        }
        function init_plugin() {

            function scroll_fix() {
                return (scroll_fix.is(H) ? (H.add_monitor) : (scroll_fix.is(this) ? this.init : scroll_fix.F)).apply(H ? H : this, APS.call(arguments));
            }
            var P = U.FixCon(scroll_fix).prototype;

            P.monitors = null;
            P.mindex = null;
            P.current_scroll_top = 0;
            P.init = function () {
                this.monitors = [];
                this.mindex = {};
                H = this;                
                this.init_handlers();
                this.add_monitor.apply(this, APS.call(arguments));
                EFO.Events.GEM().on("REALIGN_REQUIRED",this,this.exec_monitors);
                return this;
            };

            P.init_handlers = function () {
                this.disable();
                jQuery(window).on('scroll', this.exec_monitors.bindToObjectWParam(this));
                jQuery(window).on('resize', this.exec_monitors.bindToObjectWParam(this));
                return this;
            };

            P.add_monitor = function (scrollable_id, bottom_stop, top_stop, top_lag, bottom_lag) {
                var i = new Monitor(scrollable_id, bottom_stop, top_stop, top_lag, bottom_lag);
                if (i && i.is_valid()) {
                    if (!Monitor.is(this.mindex[i.id])) {
                        this.monitors.push(i);
                        this.mindex[i.id] = i;
                    }
                }
                return this;
            };

            P.exec_monitors = function (c, e) {
                var cs = this.get_page_scroll_top();
                var dir = Math.sign(cs - this.current_scroll_top);
                this.current_scroll_top = cs;
                dir === 0 ? dir = 1 : 0;
                var wh = this.get_window_height();
                for (var i = 0; i < this.monitors.length; i++) {
                    this.monitors[i].exec(cs, dir, wh);
                }
                return this;
            };
            P.get_page_scroll = function () {
                var supportPageOffset = window.pageXOffset !== undefined;
                var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");

                var x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
                var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
                return {x: x, y: y};
            };

            P.get_page_scroll_top = function () {
                return this.get_page_scroll().y;
            };
            P.get_page_scrll_left = function () {
                return this.get_page_scroll().x;
            };
            P.get_window_height = function () {
                return window.innerHeight;//document.documentElement.clientHeight;
            };


            P.disable = function () {
                jQuery(window).off('scroll', this.exec_monitors.bindToObjectWParam(this));
                jQuery(window).off('resize', this.exec_monitors.bindToObjectWParam(this));
                return this;
            };




            function Monitor() {
                return (Monitor.is(this) ? this.init : Monitor.F).apply(this, APS.call(arguments));
            }
            var M = U.FixCon(Monitor).prototype;

            M.node = null;
            M.id = null;
            M.bottom_stop = null;
            M.top_stop = null;
            M.bottom_lag = null;
            M.top_lag = null;

            M.init = function (scrollable, bottom_stop, top_stop, top_lag, bottom_lag) {
                this.id = U.NEString(scrollable, null);
                this.node = this.get_node_by_id(this.id);
                this.top_stop = this.get_node_by_id(top_stop);
                this.bottom_stop = this.get_node_by_id(bottom_stop);
                this.top_lag = U.IntOr(top_lag, 0);
                this.bottom_lag = U.IntOr(bottom_lag, 0);
                return this;
            };

            M.is_valid = function () {
                return !!(this.id && this.node);
            };

            M.get_node_by_id = function (x) {
                if (U.isDOM(x)) {
                    return x;
                }
                x = U.NEString(x, null);
                if (x) {
                    var rv = document.getElementById(x);
                    return rv ? rv : null;
                }
                return null;
            };

            M.get_rect = function () {
                return this.node.getBoundingClientRect();
            };

            M.get_node_height = function () {
                var rect = this.get_rect();
                return rect.bottom - rect.top;
            };
            M.get_node_width = function () {
                var rect = this.get_rect();
                return rect.right - rect.left;
            };
            M.get_node_window_pos_x = function () {
                return this.get_rect().left;
            };

            M.get_node_window_pos_y = function () {
                return this.get_rect().top;
            };

            M.exec = function (cs, dir, wh) {
                var nh = this.get_node_height();
                var cwh = wh;
                if (this.top_stop) {
                    var rect = this.top_stop.getBoundingClientRect();
                    var tsh = rect.bottom - rect.top;
                    cwh -= tsh;
                }
                if (nh < cwh) {
                    return this.exec_smaller(nh, wh, cs);
                } else if (dir === 1) {
                    return this.exec_larger_down(nh, wh, cs);
                } else if (dir === -1) {
                    return this.exec_larger_up(nh, wh, cs);
                }
                return this;
            };


            M.exec_larger_down = function (nh, wh, cs) {
                var window_bottom = (wh + cs);
                if (this.bottom_stop) {
                    var bst = this.bottom_stop.getBoundingClientRect().top + cs - this.get_any_node_margin_top(this.bottom_stop);
                    window_bottom = Math.min(bst, window_bottom);
                }
                window_bottom -= this.bottom_lag;
                var node_bottom = this.get_node_page_pos_y() + nh;
                //var cbr = menu.get(0).getBoundingClientRect();
                //var mbp = cbr.top + cbr.height + current_scroll_top;//on screen
                if (node_bottom < window_bottom) {
                    var current_margin = this.get_node_margin_top();
                    // var mrt = U.FloatOr(menu.css('margin-top'), 0, 0);
                    //mrt += (wbp - mbp);
                    var target_margin = current_margin + (window_bottom - node_bottom);
                    this.node.style.marginTop = Math.max(target_margin, 0) + "px";
                    //menu.css('margin-top', Math.max(mrt, 0) + "px");
                }
                return this;
            };

            M.exec_larger_up = function (nh, wh, ch) {                
                var top_pos = 0;
                if (this.top_stop) {
                    var bst = this.top_stop.getBoundingClientRect().bottom;
                    top_pos = Math.max(bst, top_pos);
                }
                var node_top = this.get_rect().top;
                if (node_top > top_pos) {
                    var current_margin = this.get_node_margin_top();                   
                    var target_margin = current_margin - (node_top - top_pos);                    
                    this.node.style.marginTop = Math.max(target_margin, 0) + "px";
                }

                return this;
            };



            M.exec_smaller = function (nh, wh, cs) {
                var window_bottom = (wh + cs);
                if (this.bottom_stop) {
                    var bst = this.bottom_stop.getBoundingClientRect().top + cs - this.get_any_node_margin_top(this.bottom_stop);
                    window_bottom = Math.min(bst, window_bottom);
                }
                window_bottom -= this.bottom_lag;
                var tm = cs;
                var target_bottom = tm + nh + (this.get_node_page_pos_y() - this.get_node_margin_top());
                if (target_bottom > window_bottom) {
                    tm -= (target_bottom - window_bottom);
                }
                this.node.style.marginTop = Math.max(0, tm) + "px";
                return this;
            };



            M.get_node_page_rect = function () {
                var pos = this.get_rect();
                var scr = this.get_page_scroll();
                return {
                    left: pos.left + scr.x,
                    top: pos.top + scr.y,
                    bottom: pos.bottom + scr.y,
                    right: pos.right + scr.x
                };
            };

            M.get_node_page_pos_x = function () {
                return this.get_node_page_rect().left;
            };
            M.get_node_page_pos_y = function () {
                return this.get_node_page_rect().top;
            };
            M.get_node_style = function () {
                return window.getComputedStyle ? getComputedStyle(this.node) : this.node.currentStyle;
            };
            M.get_any_node_style = function (x) {
                return window.getComputedStyle ? getComputedStyle(x) : x.currentStyle;
            };

            M.get_node_margin_top = function () {
                return U.FloatOr(this.get_node_style().marginTop, 0.0);
            };
            M.get_any_node_margin_top = function (x) {
                return U.FloatOr(this.get_any_node_style(x).marginTop, 0.0);
            };
            M.get_page_scroll = function () {
                var supportPageOffset = window.pageXOffset !== undefined;
                var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");

                var x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
                var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
                return {x: x, y: y};
            };

            M.get_page_scroll_top = function () {
                return this.get_page_scroll().y;
            };
            M.get_page_scroll_left = function () {
                return this.get_page_scroll().x;
            };







            E.scroll_fix = scroll_fix;

            E.scroll_fix_ready = E.scroll_fix_ready || [];
            var c = [].concat(E.scroll_fix_ready);
            E.scroll_fix_ready = {
                push: function () {
                    var args = APS.call(arguments);
                    for (var i = 0; i < args.length; i++) {
                        try {
                            if (U.isCallable(args[i])) {
                                args[i]();
                            }
                        } catch (e) {
                            U.TError(e);
                        }
                    }
                }
            };
            for (var i = 0; i < c.length; i++) {
                E.scroll_fix_ready.push(c[i]);
            }

        }
    }
})();