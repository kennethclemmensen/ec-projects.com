import { fromEvent, tap } from 'rxjs';

fromEvent(document, 'DOMContentLoaded').pipe(
    tap(() => {
        const headerNavTrigger = document.getElementsByClassName('header__nav-trigger')[0];
        const header = document.getElementsByClassName('header')[0];
        const showMobileMenuClass = 'show-mobile-menu';
        const slides = document.querySelectorAll<HTMLElement>('.slider__slide');
        const numberOfSlides = slides.length;
        const previousArrow = document.getElementById('previous-arrow');
        const nextArrow = document.getElementById('next-arrow');
        let currentSlideIndex = 0;
        slides.forEach((slide, i) => {
            slide.style.transform = `translateX(${i * 100}%)`;
        });
        fromEvent(window, 'scroll').pipe(
            tap(() => {
                if (header != null) {
                    header.classList.toggle('header--scroll', window.scrollY > 0);
                }
            })
        ).subscribe();
        if(headerNavTrigger != null && previousArrow != null && nextArrow != null) {
            fromEvent(headerNavTrigger, 'click').pipe(
                tap((event) => {
                    event.preventDefault();
                    document.getElementsByTagName('html')[0]?.classList.toggle(showMobileMenuClass);
                    document.body.classList.toggle(showMobileMenuClass);
                    document.getElementsByClassName('mobile-menu')[0]?.classList.toggle('mobile-menu--active');
                })
            ).subscribe();
            fromEvent([previousArrow, nextArrow], 'click').pipe(
                tap((event) => {
                    if (event.target === previousArrow) {
                        currentSlideIndex--;
                        if(currentSlideIndex < 0) {
                            currentSlideIndex = numberOfSlides - 1;
                        }
                    } else {
                        currentSlideIndex++;
                        if(currentSlideIndex === numberOfSlides) {
                            currentSlideIndex = 0;
                        }
                    }
                    slides.forEach((slide, i) => {
                        slide.style.transform = `translateX(${(i - currentSlideIndex) * 100}%)`;
                        slide.style.transition = 'transform 0.5s ease';
                    });
                })
            ).subscribe();
        }
    })
).subscribe();