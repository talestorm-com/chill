{/literal}{*
                function posite_menu() {
                    var current_scroll_top = U.FloatMoreOr(jQuery(window).scrollTop(), 0, 0);
                    var dir = Math.sign(current_scroll_top - last_sroll_top);
                    last_sroll_top = current_scroll_top;
                    if (true || dir !== 0) {
                        var menu_height = U.FloatMoreOr(menu.outerHeight(false), 0, null);
                        if (null !== menu_height) {
                            var window_height = U.FloatMoreOr(jQuery(window).innerHeight(), 0, null);
                            if (null !== window_height) {
                                if (menu_height <= window_height) {
                                    var tmt = current_scroll_top;
                                    var cbr = menu.get(0).getBoundingClientRect();
                                    var mbp = cbr.top + cbr.height + current_scroll_top;//on screen
                                    var wbp1 = (window_height + current_scroll_top) - 16;
                                    var wbp2 = U.FloatOr(jQuery('.FrontLayoutPageFooter').position().top - 150);
                                    var wbp = Math.min(wbp1, wbp2);
                                    if (tmt + cbr.height > wbp) {
                                        tmt -= ((tmt + cbr.height) - wbp);
                                    }
                                    menu.css("marginTop", Math.max(0, tmt) + "px");

                                } else {
                                    if (dir === 1) {//down
                                        // штатная прокрутка, до тех пор, пока низ не поравняется с низом экрана   

                                        var wbp1 = (window_height + current_scroll_top) - 16;
                                        var wbp2 = U.FloatOr(jQuery('.FrontLayoutPageFooter').position().top);
                                        var wbp = Math.min(wbp1, wbp2);
                                        var cbr = menu.get(0).getBoundingClientRect();
                                        var mbp = cbr.top + cbr.height + current_scroll_top;//on screen
                                        if (mbp < wbp) {
                                            var mrt = U.FloatOr(menu.css('margin-top'), 0, 0);
                                            mrt += (wbp - mbp);
                                            menu.css('margin-top', Math.max(mrt, 0) + "px");
                                        }
                                    } else {//up
                                        //штатная прокрутка пока верх не поравняется с верхом экрана
                                        var wtp = (U.FloatOr(jQuery('.FrontLayoutPageHeader').outerHeight(false), 0, 0) + 0 * current_scroll_top);
                                        var cbr = menu.get(0).getBoundingClientRect();
                                        var mtp = cbr.top;//on screen                                        
                                        if (mtp > wtp) {
                                            var mrt = U.FloatOr(menu.css('margin-top'), 0, 0);
                                            mrt += -1 * (mtp - wtp);
                                            menu.css('margin-top', Math.max(mrt, 0) + "px");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                posite_menu();
                *}{literal}