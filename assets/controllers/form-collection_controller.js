import { Controller } from '@hotwired/stimulus';

/**
 * This Stimulus controller manages a dynamic form for adding and removing riddle entries.
 */
export default class extends Controller {
    connect() {
        this.index = this.element.childElementCount

        const addButton = document.createElement('button')
        addButton.classList.add('btn-riddle', 'btn-add')
        addButton.textContent = 'Ajouter une énigme'
        addButton.type = 'button'
        addButton.addEventListener('click', this.addElement)

        this.element.childNodes.forEach(this.addDeleteButton)
        this.element.append(addButton)

        this.element.childNodes.forEach((child, index) => {
            if (child.nodeName === 'DIV') {
                child.classList.add('riddle-form')
                child.firstChild.textContent = `Enigme ${index + 1}`
            }
        })        
    }

    /**
     * Add a new riddle entry to the form.
     * 
     * @param {MouseEvent} e The click event object triggered by the "Ajouter une énigme" button.
     */
    addElement = (e) => {
        e.preventDefault()
        this.index = this.element.childElementCount
        const prototypeContent = this.element.dataset['prototype']
        const newElement = document.createRange()
            .createContextualFragment(
                prototypeContent
                .replaceAll('__name__label__', `Enigme ${this.index}`)
                .replaceAll('__name__', this.index)
        ).firstElementChild

        newElement.classList.add('riddle-form')
        this.addDeleteButton(newElement)
        this.index++

        e.currentTarget.insertAdjacentElement('beforebegin', newElement)
    }

    /**
     * Add a delete button to a riddle entry in the form.
     * 
     * @param {HTMLElement} item The riddle entry element to which the button will be added.
     */
    addDeleteButton = (item) => {
        const deleteButton = document.createElement('button')
        deleteButton.classList.add('btn-riddle', 'btn-delete')
        deleteButton.textContent = '✖'
        deleteButton.type = 'button'

        item.append(deleteButton)
        deleteButton.addEventListener('click', (e) => {
            e.preventDefault()
            item.remove()
        })
    }
}
