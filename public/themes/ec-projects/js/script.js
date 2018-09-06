jQuery.noConflict();
(function($) {
    $(document).ready(function() {
        $('.header__nav-trigger').on('click', function(event) {
            event.preventDefault();
            $('html, body').toggleClass('show-mobile-navigation');
            $('.mobile-navigation').toggleClass('mobile-navigation--active');
        });
        var $header = $('.header');
        var headerScrollClass = 'header--scroll';
        $(window).scroll(function() {
            if(window.pageYOffset > 0) {
                $header.addClass(headerScrollClass);
            } else {
                $header.removeClass(headerScrollClass);
            }
        });
    });
})(jQuery);