(function ($) {
    $(document).ready(function () {
        var $datatypeColor = $('.datatype-color');

        $datatypeColor.each(function () {
            var $this = $(this);
            var $datatypeColorInput = $this.find('input');

            $this.colorpicker({
                align:"left",
                customClass: 'colorpicker-2x',
                sliders: {
                    saturation: {
                        maxLeft: 200,
                        maxTop: 200
                    },
                    hue: {
                        maxTop: 200
                    },
                    alpha: {
                        maxTop: 200
                    }
                },
                format:($datatypeColorInput.data('format') !== undefined ? $datatypeColorInput.data('format') : false)
            });

            if($datatypeColorInput.val() !== '') {
                var initColor = $this.data('colorpicker').color;
                var rgb = initColor.toRGB();
                var o = Math.round(((parseInt(rgb.r) * 299) + (parseInt(rgb.g) * 587) + (parseInt(rgb.b) * 114)) / 1000);
                var fore = (o > 125) ? "black" : "white";

                $this.find(".input-group-addon").css("background-color", initColor.toString("hex")).find("i").css("color", fore);
            }
        });

        $datatypeColor.on("changeColor", function(e){
            var rgb = e.color.toRGB();
            var o = Math.round(((parseInt(rgb.r) * 299) + (parseInt(rgb.g) * 587) + (parseInt(rgb.b) * 114)) / 1000);
            var fore = (o > 125) ? "black" : "white";

            $(this).find(".input-group-addon").css("background-color", e.color.toString("hex")).find("i").css("color", fore);
        });
    })
})(jQuery);
