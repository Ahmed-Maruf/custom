;(function ($) {
    $(document).ready(function () {
        console.log('Hi');
        var slider = tns({
            container: '.t-slider',
            items: 1,
            slideBy: 'page',
            autoplay: true,
            controls: false,
            nav: false,
            autoplayButtonOutput: false
        });
    });
})(jQuery);