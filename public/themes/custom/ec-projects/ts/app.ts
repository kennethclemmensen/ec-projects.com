import { EventType } from './eventType';
import { Slider } from './slider';

/**
 * The App class is the main entry point for the application
 */
class App {

    /**
     * The constructor initializes the application
     */
    public constructor() {
        document.addEventListener(EventType.DOMContentLoaded, () => {
            this.setupHeader();
            new Slider();
        });
    }

    /**
     * Sets up the header by adding event listeners for the mobile menu and scroll events
     */
    private setupHeader() {
        document.getElementsByClassName('header__nav-trigger')[0]?.addEventListener(EventType.Click, (event) => {
            event.preventDefault();
            document.getElementsByTagName('html')[0]?.classList.toggle('show-mobile-menu');
            document.getElementsByTagName('body')[0]?.classList.toggle('show-mobile-menu');
            document.getElementsByClassName('mobile-menu')[0]?.classList.toggle('mobile-menu--active');
        });
        const header = document.getElementsByClassName('header')[0];
        const headerScrollClass = 'header--scroll';
        window.addEventListener(EventType.Scroll, () => {
            if (header != null) {
                (window.scrollY > 0) ? header.classList.add(headerScrollClass) : header.classList.remove(headerScrollClass);
            }
        });
    }
}

new App();