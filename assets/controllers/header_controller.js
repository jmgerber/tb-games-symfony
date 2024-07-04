import { Controller } from '@hotwired/stimulus';

/**
 * This Stimulus controller is responsible for the header component.
 * It toggles the active class on the nav element when the burger button is clicked.
 */
export default class extends Controller {
    /**
     * Connects the header controller to the DOM and adds event listeners for the burger menu button.
     */
    connect() {
        document.addEventListener('DOMContentLoaded', function () {
            const burgerButton = document.querySelector('.burger-menu-button')
            const nav = document.querySelector('.header-nav--pages-links')
        
            burgerButton.addEventListener('click', function () {
                nav.classList.toggle('is-active')
            })
        })

        // Close nav menu when clicking outside of it
        document.addEventListener('click', (event) => {
            const nav = document.querySelector('.header-nav--pages-links')
            const burgerButton = document.querySelector('.burger-menu-button')

            if (event.target !== nav && event.target !== burgerButton) {
                nav.classList.remove('is-active')
            }
        })
    }
}
