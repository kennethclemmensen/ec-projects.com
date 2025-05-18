import { fromEvent, tap } from 'rxjs';
import { EventType } from './eventType';
import { Slider } from './slider';

fromEvent(document, EventType.DOMContentLoaded).pipe(
    tap(() => {
        const headerNavTrigger = document.getElementsByClassName('header__nav-trigger')[0];
        const header = document.getElementsByClassName('header')[0];
        const headerScrollClass = 'header--scroll';
        const showMobileMenuClass = 'show-mobile-menu';
        if (headerNavTrigger != null) {
            fromEvent(headerNavTrigger, EventType.Click).subscribe((event) => {
                event.preventDefault();
                document.getElementsByTagName('html')[0]?.classList.toggle(showMobileMenuClass);
                document.getElementsByTagName('body')[0]?.classList.toggle(showMobileMenuClass);
                document.getElementsByClassName('mobile-menu')[0]?.classList.toggle('mobile-menu--active');
            });
        }
        fromEvent(window, EventType.Scroll).subscribe(() => {
            if (header != null) {
                (window.scrollY > 0) ? header.classList.add(headerScrollClass) : header.classList.remove(headerScrollClass);
            }
        });
        new Slider();
    })
).subscribe();