document.addEventListener('DOMContentLoaded', () => {
    $('.header__nav-trigger').on('click', (event) => {
        event.preventDefault();
        $('html, body').toggleClass('show-mobile-menu');
        $('.mobile-menu').toggleClass('mobile-menu--active');
    });
    const header = $('.header');
    const headerScrollClass = 'header--scroll';
    $(window).on('scroll', () => {
        (window.scrollY > 0) ? header.addClass(headerScrollClass) : header.removeClass(headerScrollClass);
    });
    const sliderTrack = document.getElementById('slider-track');
    if (sliderTrack != null) {
        let index = 0;
        const clientWidth = document.body.clientWidth;
        const numberOfSlides = document.getElementsByClassName('slider__slide').length;
        sliderTrack.style.width = (clientWidth * numberOfSlides) + 'px';
        sliderTrack.style.transform = `translate3d(0, 0, 0)`;
        document.getElementById('previous-arrow')?.addEventListener('click', () => {
            index--;
            if (index < 0) {
                index = numberOfSlides - 1;
            }
            sliderTrack.style.transform = `translate3d(-${clientWidth * index}px, 0, 0)`;
            console.log(index);
        });
        document.getElementById('next-arrow')?.addEventListener('click', () => {
            index++;
            if (index === numberOfSlides) {
                index = 0;
            }
            sliderTrack.style.transform = `translate3d(-${clientWidth * index}px, 0, 0)`;
            console.log(index);
        });
    }
});