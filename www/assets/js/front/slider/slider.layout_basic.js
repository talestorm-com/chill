(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
      window.Eve.EFO.Ready.push(ready1);
    function ready1(){
     jQuery(ready);
    }

    function ready() {
        var E = window.Eve, EFO = E.EFO, U = EFO.U, APS = Array.prototype.slice;
        E.SLIDER_CORE_LAYOUTS = E.SLIDER_CORE_LAYOUTS || [];
        function layout() {
            return (layout.is(this) ? this.init : layout.F).apply(this, APS.call(arguments));
        }
        var style = {"style":".EveCoreSlider.EveCoreSlider-basic {\n    box-sizing: border-box;\n    line-height: 0;\n}\n\n.EveCoreSlider.EveCoreSlider-basic .EveCoreSliderInner {\n    box-sizing: border-box;\n    width: 100%;\n    position: relative;\n    overflow:hidden;\n}\n\n.EveCoreSlider.EveCoreSlider-basic .EveCoreSliderImage {\n    position: absolute;\n    top:0;\n    z-index: 1;        \n    will-change:scroll-position,opacity,transform;\n}\n\n\n.EveCoreSlider.EveCoreSlider-basic .EveCoreSliderImage img {\n    width:100%;\n    max-width:100%;\n    min-width:100%;\n}\n"};
        jQuery(['<style type="text/css" data-id="slider.layout_triplex">', style.style, '</style>'].join('')).appendTo('body');
        var FP = U.FixCon(layout).prototype;
        FP.start_index = 0;
        FP.animation_duration = null;
        FP.section_delay = null;
        FP.slides = null;
        FP.slides_per_screen = null;
        FP.dimension_x = 3360;
        FP.dimension_y = 5040;
        FP.handle_height_k = FP.dimension_y / FP.dimension_x;
        FP.image_width = null;


        FP.ki_first = true;


        FP.init = function (slider) {
            this.start_index = 0;
            this.section_delay = 0; //all together
            this.animation_duration = U.IntMoreOr(slider.layout_params.animation_duration, -1, 200);
            this.slides_per_screen = U.IntMoreOr(slider.layout_params.framecount, 0, 3);
            this.init_style(slider);
            return this;
        };


        FP.init_style = function (slider) {
            var slider_height = (100 * this.handle_height_k / this.slides_per_screen).toFixed(5);
            this.image_width = (100 / this.slides_per_screen)+.1;
            var css_width = [this.image_width.toFixed(5), "%;"].join('');
            var styletext = ["#", slider.uid, ' .EveCoreSliderInner{ padding-top:', slider_height, "%}"];
            styletext.push(["#", slider.uid, ' .EveCoreSliderImage{width:', css_width, "min-width:", css_width, "max-width:", css_width, ";position:absolute;top:0;}"].join(''));
            this.style = jQuery('<style type="text/css"></style>');
            this.style.html(styletext.join(''));
            this.style.appendTo('body');
            return this;
        };

        FP.prepare_images = function (slider) {
            var index = 0;
            var min_images = this.slides_per_screen + 1;
            if (slider.images.length < min_images) {
                while (slider.images.length < min_images) {
                    slider.images.push(slider.images[index].clone());
                    index++;
                }
            }
            return this;
        };

        FP.on_slide = function (slider) {
            if (window.stop_all_sliders) {
                slider.stop();
            }
            if (!this.slides) {
                this.percent_step = (100 / this.slides_per_screen);
                this.slides = [];
                for (var i = 0; i < slider.images.length; i++) {
                    var p = {n: slider.images[i].node, left: (i * this.percent_step).toFixed(6)};
                    this.slides.push(p);
                    p.n.get(0).style.left = [p.left, "%"].join("");
                }
                return this;
            }            
            var new_slides = this.slides.slice(1);
            new_slides.push(this.slides[0]);
            var tl = anime.timeline({
                easing: 'linear',
                duration: this.animation_duration
            });
            tl.pause();
            for (var i = 0; i < new_slides.length - 1; i++) {
                tl.add({
                    targets: new_slides[i].n.get(0),
                    left: (i * this.percent_step).toFixed(6) + "%"
                }, 0);
            }


            var self = this;
            tl.complete = function () {                
                self.slides = new_slides;
                self.slides[self.slides.length-1].n.get(0).style.left = (self.percent_step * (self.slides.length-1)).toFixed(6)+"%";
            };

            tl.play();
        };

        FP.get_image_spec = function (slider) {
            var max_dim=Math.max(screen.width,screen.height);
            var image_width = U.IntMoreOr((max_dim / this.slides_per_screen), 0, null);
            return {
                width: image_width,
                height: U.IntMoreOr((image_width * (this.handle_height_k)), 0, null)
            };
        };

        E.SLIDER_CORE_LAYOUTS.push(function (x) {
            x.register_layout('basic', layout);
        });

    }

})();