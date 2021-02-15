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
        var style = {"style": ".EveCoreSlider.EveCoreSlider-simple {\n    box-sizing: border-box;\n    width: 100%;\n    padding: 0;\n    line-height: 0;\n    will-change: transform;\n}\n\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderInner {\n    box-sizing: border-box;\n    width: 100%;\n    overflow: hidden;\n    display: flex;\n    flex-direction: row;\n    flex-wrap: nowrap;\n    justify-content: center;\n    align-items: flex-start;\n}\n\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderInner .EveCoreSliderImage {\n    width: 33.3333%;\n    overflow: visible;\n    display: none;\n}\n\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderInner .EveCoreSliderImage img {\n    width: 100%;\n}\n\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderImage.EveCoreSliderLayoutSimpleBeforeView {\n    position: absolute;\n    top: 0;\n    perspective: 300vw;\n    perspective-origin: center;\n    display: block;\n}\n\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderImage.EveCoreSliderLayoutSimpleBeforeView img{\n    transform: rotateY(90deg);\n    transform-origin: center;\n}\n\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderImage.EveCoreSliderLayoutSimpleDisplayed{\n    position: static;\n    perspective: 300vw;\n    perspective-origin: center;\n    display: block;\n}\n.EveCoreSlider.EveCoreSlider-simple .EveCoreSliderImage.EveCoreSliderLayoutSimpleDisplayed img{\n    transform-origin: center;\n}\n\n"};
        jQuery(['<style type="text/css" data-id="slider.layout_simple">', style.style, '</style>'].join('')).appendTo('body');
        var FP = U.FixCon(layout).prototype;
        FP.start_index = 0;
        FP.visible_slides = null;
        FP.step_index = 0;
        FP.animation_duration = null;
        FP.section_delay = null;
        FP.framecount = null;

        FP.init = function (slider) {
            this.start_index = -1;
            this.visible_slides = [];
            this.visible_images = [];
            this.section_delay = U.IntMoreOr(slider.layout_params.section_delay, -1, 150);
            this.animation_duration = U.IntMoreOr(slider.layout_params.animation_duration, -1, 200);
            this.framecount = U.IntMoreOr(slider.layout_params.framecount, 0, 3);
            this.init_style(slider);
            return this;
        };

        FP.init_style = function (slider) {
            var width = [(100 / this.framecount).toFixed(5), "%;"].join('');
            this.style = jQuery('<style type="text/css"></style>');
            this.style.html(["#", slider.uid, ' .EveCoreSliderImage{width:', width, "min-width:", width, "max-width:", width, "}"].join(''));
            this.style.appendTo('body');
            return this;
        };

        FP.prepare_images = function (slider) {
            var index = 1;
            if (slider.images.length < this.framecount * 2) {
                while (slider.images.length < this.framecount * 2) {
                    slider.images.push(slider.images[index].clone());
                    index++;
                }
                slider.images.sort(function () {
                    return Math.random() - .5;
                });
            }
        };

        FP.on_slide = function (slider) {
            var tl = anime.timeline({
                easing: 'linear',
                duration: this.animation_duration
            });
            tl.pause();
            /**
             * абс или флекс? флексом их не совместить? и для абс не нужен порядок?
             * abs только новые?
             */
            if (this.visible_images.length) {
                for (var i = 0; i < this.visible_slides.length; i++) {
                    tl.add({
                        targets: this.visible_images[i].get(0),
                        rotateY: [0, -90]
                    }, i * this.section_delay);
                }
            }

            this.start_index = U.IntMoreOr(this.start_index, -1, -1) + this.framecount;
            if (this.start_index >= slider.images.length) {
                this.start_index = this.start_index - slider.images.length;
            }
            var end_index = this.start_index + this.framecount;
            var new_visible_slides = [];
            var new_visible_images = [];
            for (var i = this.start_index; i < end_index; i++) {
                var offset = i < slider.images.length ? i : (i - slider.images.length);
                new_visible_slides.push(slider.images[offset].node);
                new_visible_images.push(slider.images[offset].image_node);
            }
            for (var i = 0; i < new_visible_slides.length; i++) {
                new_visible_slides[i].removeAttr("style");
                new_visible_slides[i].addClass('EveCoreSliderLayoutSimpleBeforeView');
                new_visible_slides[i].css('left', ((100 / this.framecount) * i).toFixed(5) + "%");
                tl.add({
                    targets: new_visible_images[i].get(0),
                    rotateY: [90, 0]
                }, this.animation_duration + (i * this.section_delay));

                new_visible_slides[i].appendTo(new_visible_slides[i].parent());
            }
            var self = this;
            tl.complete = function () {
                for (var i = 0; i < self.visible_images.length; i++) {
                    self.visible_images[i].removeAttr('style');
                    self.visible_slides[i].removeAttr('style');
                    self.visible_slides[i].removeClass('EveCoreSliderLayoutSimpleDisplayed');
                }
                self.visible_images = new_visible_images;
                self.visible_slides = new_visible_slides;
                for (var i = 0; i < self.visible_images.length; i++) {
                    self.visible_slides[i].addClass('EveCoreSliderLayoutSimpleDisplayed');
                    self.visible_slides[i].removeClass('EveCoreSliderLayoutSimpleBeforeView');
                    self.visible_images[i].removeAttr('style');
                    self.visible_slides[i].removeAttr('style');
                }
            };

            tl.play();
            this.step_index++;



        };

        FP.get_image_spec = function (slider) {
            var max_dim=Math.max(screen.width,screen.height);
            return {
                width: U.IntMoreOr(max_dim / this.framecount, 0, null),
                height: U.IntMoreOr(((max_dim / this.framecount) * (5.04 / 3.360)), 0, null)
            };
        };

        E.SLIDER_CORE_LAYOUTS.push(function (x) {
            x.register_layout('simple', layout);
        });

    }

})();