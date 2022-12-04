"use strict";
document.addEventListener('DOMContentLoaded', () => {
    $('.header__nav-trigger').on('click', (event) => {
        event.preventDefault();
        $('html, body').toggleClass('show-mobile-menu');
        $('.mobile-menu').toggleClass('mobile-menu--active');
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
    const header = $('.header');
    const headerScrollClass = 'header--scroll';
    $(window).on('scroll', () => {
        (window.pageYOffset > 0) ? header.addClass(headerScrollClass) : header.removeClass(headerScrollClass);
    });
});
