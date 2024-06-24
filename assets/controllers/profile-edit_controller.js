import { Controller } from '@hotwired/stimulus';

/**
 * This Stimulus controller manages profile edit functionalities.
 * It handles toggling edit forms for email and password and submitting
 * the picture edit form on file change.
 */
export default class extends Controller {
    /**
     * Connects to the DOM and sets up event listeners.
     */
    connect() {
        this.emailEditLink = document.querySelector('.email-change-link')
        this.passwordEditLink = document.querySelector('.password-change-link')

        this.toggleForm(this.emailEditLink)
        this.toggleForm(this.passwordEditLink)

        this.pictureEditForm = document.querySelector('form[name=profile_edit_picture]')

        this.uploadOnChange(this.pictureEditForm)
    }

    /**
     * Toggles the visibility of an edit form based on a linked element.
     *
     * @param {HTMLElement} link The element that triggers the toggle.
     */
    toggleForm(link) {
        const editForm = link.nextElementSibling
        const cancelButton = editForm.querySelector('.cancel-button')

        link.addEventListener('click', () => {
            if (editForm.style.display === "none") {
                editForm.style.display = "block"
                link.style.display = "none"
            } else {
                editForm.style.display = "none"
            }
        })

        cancelButton.addEventListener('click', () => {
            link.style.display = "block"
            editForm.style.display = "none"
        })
    }

    /**
     * Attaches a change event listener to a form and submits it on change.
     *
     * @param {HTMLFormElement} form The form to submit on change.
     */
    uploadOnChange(form) {
        form.addEventListener('change', () => {
            form.submit()
        })
    }
}
