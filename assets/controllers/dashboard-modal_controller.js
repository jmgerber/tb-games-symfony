import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    async connect() {

        const res = await fetch('http://localhost:8000/api/games')
        this.games = await res.json()

        this.card = this.element;
        this.modal = document.getElementById('modal')
        this.modalTitle = this.modal.querySelector('.modal_title')
        this.modalPicture = this.modal.querySelector('.modal_picture')
        this.modalDescription = this.modal.querySelector('.modal_description')
        this.modalDifficulty = this.modal.querySelector('.modal_difficulty')
        this.modalTime = this.modal.querySelector('.time_text')
        this.closeButton = this.modal.querySelector('.close_modal_btn')
        this.startButton = this.modal.querySelector('.start_button')
        
        // Add click event listener to all cards within the container
        const cards = document.querySelectorAll('.game_card')
        cards.forEach(card => card.addEventListener('click', this.showCardDetails.bind(this)))
        this.closeButton.addEventListener('click', this.hideModal.bind(this))
    }
  
    showCardDetails(event) {
        const card = event.currentTarget
        const cardId = parseInt(card.dataset.cardId, 10)

        if (!this.games) {
            console.error('Games data not available in container');
            return;
        }
        

        const cardData = this.games.find((item) => item.id === cardId)
        this.modalTitle.textContent = cardData.title
        this.modalDescription.textContent = cardData.description
        this.modalPicture.setAttribute('src', '/images/gamesPictures/' + cardData.picture)

        this.modalDifficulty.innerHTML = ""
        this.gameDifficultyShow(cardData.difficulty).forEach((element) => {
            this.modalDifficulty.appendChild(element)
        })

        this.modalTime.textContent = cardData.time + "min"

        this.startButton.setAttribute('href', '../games/' + cardData.id);

        this.modal.style.display = 'flex'
    }
  
    hideModal(event) {
        this.modal.style.display = 'none'
        event.stopPropagation()
    }

    gameDifficultyShow(difficulty) {
        return [...Array(5)].map((star, index) => {
            const starElement = document.createElement('span');
            starElement.classList.add('star')
            if (index < difficulty) {
                starElement.innerHTML = '&#9733;'
            } else {
                starElement.innerHTML = '&#9734;'
            }
            return starElement
        })
    }
}