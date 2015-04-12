
(function($, window){

    var default_title  = $('title').html();
    var resize_content = function(){
        var height = Math.max($('#sidebar').height(), $('#main').height(), $('body').height());
        $('#sidebar, #main').css({'min-height': height});
    };

    //--------------------------------------------------------------------------
    // SLIDER
    //--------------------------------------------------------------------------

    var init_slider = function(){
        $('#slider').each(function(){
            var $slider = $(this);
            if ( $slider.attr('data-slider-is-init') ) {
                return;
            }
            $slider.attr('data-slider-is-init', true);

            var $slider_nav   = $('div.slider-nav', $slider);
            var $slider_items = $('div.slider-items', $slider);
            var $slider_w     = $('div.slider-wrapper', $slider);
            var $slider_rows  = $('div.slider-row', $slider);

            var current_slide = 0;
            var slider_width  = $slider.width();
            var total_slides  = $slider_rows.length;

            var slide_to = function (i) {
                current_slide = i;
                var $links = $slider_nav.find('a').removeClass('active');
                $( $links[i] ).addClass('active');
                $slider_items.animate({scrollLeft: $slider.width() * i}, 500);
            };
            $slider_rows.each(function(i){
                var $link = $('<a/>').attr('href','#').attr('class', i?'':'active').html(i + 1)
                    .click(function(){
                        slide_to(i);
                        return false;
                    });
                $slider_nav.append($link);
            });

            $(window).resize(function(){
                slider_width = $slider.width();
                $slider_rows.width( slider_width );
                $slider_items.scrollLeft( slider_width * current_slide );
                $slider_w.width( slider_width * total_slides );
            });
        });

        $(window).resize();
    };

    //--------------------------------------------------------------------------

    $(document).ajaxComplete(function() {
        init_slider();
        resize_content();

        // title
        var h1 = $('h1').first().html();
        $('title').html(h1 ? h1 : default_title);

        // active menu items
        var path = window.location.pathname.replace(/^\/(.*?)\/?[^\/]*$/i,'$1');
        $('#sidebar a').each(function(){
            if (path.split('/')[0] !== 'netcat') {
                if (path && $(this).attr('href').search(path) >= 0) {
                    $('#sidebar li').removeClass('active');
                    $(this).parents('LI').addClass('active');
                }
            }
        });
    });

    $(window).load(function(){
        resize_content();
    });

    init_slider();


    //--------------------------------------------------------------------------

})(jQuery, window);