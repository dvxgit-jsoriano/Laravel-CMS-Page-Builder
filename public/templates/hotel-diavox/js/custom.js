
(function ($) {

    "use strict";

    // NAVBAR
    $('.navbar-collapse a').on('click', function () {
        $(".navbar-collapse").collapse('hide');
    });

    $(function () {
        // gather all <img> elements inside .hero-slides
        const slides = $('.hero-slides img').map(function () {
            return { src: $(this).attr('src') };
        }).get();

        $('.hero-slides').vegas({
            slides: slides,
            preload: true,
            timer: false,
            animation: 'kenburns',
        });
    });

    // CUSTOM LINK
    $('.smoothscroll').click(function () {
        var el = $(this).attr('href');
        var elWrapped = $(el);
        var header_height = $('.navbar').height() + 60;

        scrollToDiv(elWrapped, header_height);
        return false;

        function scrollToDiv(element, navheight) {
            var offset = element.offset();
            var offsetTop = offset.top;
            var totalScroll = offsetTop - navheight;

            $('body,html').animate({
                scrollTop: totalScroll
            }, 300);
        }
    });

})(window.jQuery);


