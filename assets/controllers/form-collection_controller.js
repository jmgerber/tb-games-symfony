import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.index = this.element.childElementCount
        const btn = document.createElement('button')
        btn.setAttribute('class', 'btn_riddle btn_add')
        btn.innerText = 'Ajouter une énigme'
        btn.setAttribute('type', 'button')
        btn.addEventListener('click', this.addElement)
        this.element.childNodes.forEach(this.addDeleteButton)
        this.element.append(btn)
        this.element.childNodes.forEach((child) => {
            if (child.nodeName === 'DIV') {
                child.setAttribute('class', 'riddle_single_form')
                child.firstChild.innerText = 'Enigme ' + (this.index + 1)
            }
        })        
    }

    /**
     * @param {MouseEvent} e 
     */
    addElement = (e) => {
        e.preventDefault()
        const element = document.createRange().createContextualFragment(
            this.element.dataset['prototype']
                .replaceAll('__name__label__', 'Enigme ' + (this.index + 1))
                .replaceAll('__name__', this.index)
        ).firstElementChild
        element.setAttribute('class', 'riddle_single_form')
        this.addDeleteButton(element)
        this.index++
        e.currentTarget.insertAdjacentElement('beforebegin', element)
    }

    /**
     * @param {HTMLElement} item 
     */
    addDeleteButton = (item) => {
        const btn = document.createElement('button')
        btn.setAttribute('class', 'btn_riddle btn_delete')
        btn.innerText = '✖'
        btn.setAttribute('type', 'button')
        item.append(btn)
        btn.addEventListener('click', e => {
            e.preventDefault()
            item.remove()
        })
    }
}
