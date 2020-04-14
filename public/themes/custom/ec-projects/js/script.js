document.addEventListener('DOMContentLoaded', () => {
    $('.header__nav-trigger').on('click', (event) => {
        event.preventDefault();
        $('html, body').toggleClass('show-mobile-navigation');
        $('.mobile-navigation').toggleClass('mobile-navigation--active');
    });
    $('#slider').slick({
        arrows: false,
        autoplay: true,
        mobileFirst: true,
        responsive: [{
            breakpoint: 767,
            settings: {
                appendArrows: $('.slider__arrows'),
                arrows: true,
                autoplay: false,
                prevArrow: '<span class="slider__arrow"></span>',
                nextArrow: '<span class="slider__arrow slider__arrow--next"></span>'
            }
        }]
    });
    let $header = $('.header');
    let headerScrollClass = 'header--scroll';
    $(window).scroll(() => {
        (window.pageYOffset > 0) ? $header.addClass(headerScrollClass) : $header.removeClass(headerScrollClass);
    });
});