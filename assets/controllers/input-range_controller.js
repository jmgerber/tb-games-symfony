import { Controller } from '@hotwired/stimulus';

/**
 * This Stimulus controller customize range inputs to display the value.
 */
export default class extends Controller {
    connect() {
        this.sliderElement = document.querySelector('.input-range')
        this.sliderValue = document.querySelector('.range-value')

        console.log(this.sliderElement.value)
        if (this.sliderElement.value !== 0) {
            this.changeValue(this.sliderElement.value)
        }

        this.sliderElement.addEventListener("input", (event) => {
            const tempSliderValue = event.target.value
            this.changeValue(tempSliderValue)
        })
    }

    /**
     * Change the displayed value and fill the range bar.
     * 
     * @param {number} value The new value to display.
     */
    changeValue(value) {
        this.sliderValue.textContent = value

        const progress = ((value - 1) / (this.sliderElement.max - 1)) * 100
        this.sliderElement.style.background = `linear-gradient(to right, #BF9A7C ${progress}%, #FFFFF0 ${progress}%)`
    }
}
