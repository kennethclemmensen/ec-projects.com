document.addEventListener('DOMContentLoaded', () => {
    document.getElementsByClassName('header__nav-trigger')[0]?.addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementsByTagName('html')[0]?.classList.toggle('show-mobile-menu');
        document.getElementsByTagName('body')[0]?.classList.toggle('show-mobile-menu');
        document.getElementsByClassName('mobile-menu')[0]?.classList.toggle('mobile-menu--active');
    });
    const header = document.getElementsByClassName('header')[0];
    const headerScrollClass = 'header--scroll';
    window.addEventListener('scroll', () => {
        if (header != null) {
            (window.scrollY > 0) ? header.classList.add(headerScrollClass) : header.classList.remove(headerScrollClass);
        }
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
        });
        document.getElementById('next-arrow')?.addEventListener('click', () => {
            index++;
            if (index === numberOfSlides) {
                index = 0;
            }
            sliderTrack.style.transform = `translate3d(-${clientWidth * index}px, 0, 0)`;
        });
    }
});