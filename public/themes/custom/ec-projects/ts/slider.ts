import { EventType } from './eventType';

/**
 * Provides functionality for a slider component
 */
export class Slider {

    private readonly clientWidth: number;
    private readonly sliderTrack: HTMLElement | null;

    /**
     * The constructor initializes the slider component
     */
    public constructor() {
        this.clientWidth = document.body.clientWidth;
        this.sliderTrack = document.getElementById('slider-track');
        this.setupSlider();
    }

    /**
     * Sets up the slider by adding event listeners for the previous and next arrows
     */
    private setupSlider() {
        if (this.sliderTrack != null) {
            let index = 0;
            const numberOfSlides = document.getElementsByClassName('slider__slide').length;
            this.sliderTrack.style.width = (this.clientWidth * numberOfSlides) + 'px';
            document.getElementById('previous-arrow')?.addEventListener(EventType.Click, () => {
                index--;
                if (index < 0) {
                    index = numberOfSlides - 1;
                }
                this.goToSlide(index);
            });
            document.getElementById('next-arrow')?.addEventListener(EventType.Click, () => {
                index++;
                if (index === numberOfSlides) {
                    index = 0;
                }
                this.goToSlide(index);
            });
        }
    }

    /**
     * Goes to the specified slide index
     * 
     * @param index The index of the slide to go to
     */
    private goToSlide(index: number) {
        if (this.sliderTrack != null) {
            this.sliderTrack.style.transform = `translate3d(-${this.clientWidth * index}px, 0, 0)`;
        }
    }
}