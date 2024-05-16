import { Controller } from '@hotwired/stimulus';
import gameDifficultyShow from './difficulty-display_controller.js';

/**
 * This Stimulus controller manages a game card container, fetching game data and displaying modal details on card click.
 */
export default class extends Controller {
    
    /**
   * Fetches game data from the API and initializes the controller state.
   * 
   * @async
   */
    async connect() {
        try {
            const response = await fetch('http://localhost:8000/api/games')
            this.games = await response.json()
        } catch (error) {
            console.error('Error fetching game data:', error)
        }

        this.modal = document.getElementById('modal')
        this.initializeModalElements()

        
        const cards = document.querySelectorAll('.game_card')
        cards.forEach(card => {
            // Diplays the difficulty of each game with stars
            const difficultyDisplay = card.querySelector('.game_difficulty')
            const difficultyLevel = difficultyDisplay.dataset['difficulty']
            gameDifficultyShow(difficultyLevel).forEach(element => {
                difficultyDisplay.appendChild(element)
            })

            // Adds a click event to each game card to display the modal
            card.addEventListener('click', this.showModalDetails.bind(this))
        })

        this.closeButton.addEventListener('click', this.hideModal.bind(this))
    }

    /**
     * Initializes references to modal elements within the DOM.
     */
    initializeModalElements() {
        this.modalTitle = this.modal.querySelector('.modal_title')
        this.modalPicture = this.modal.querySelector('.modal_picture')
        this.modalDescription = this.modal.querySelector('.modal_description')
        this.modalDifficulty = this.modal.querySelector('.modal_difficulty')
        this.modalTime = this.modal.querySelector('.time_text')
        this.closeButton = this.modal.querySelector('.close_modal_btn')
        this.startButton = this.modal.querySelector('.start_button')
    }

    /**
     * Handles clicking a game card, fetches details for the specific game, and displays them in the modal.
     * 
     * @param {Event} event The click event object triggered by a game card.
     */
    showModalDetails(event) {
        const card = event.currentTarget
        const cardId = parseInt(card.dataset.cardId, 10)

        if (!this.games) {
            console.error('Games data not available in container');
            return;
        }
        
        const cardData = this.games.find(item => item.id === cardId)

        this.modalTitle.textContent = cardData.title
        this.modalDescription.textContent = cardData.description
        this.modalPicture.setAttribute('src', '/images/gamesPictures/' + cardData.picture)

        this.modalDifficulty.innerHTML = ""
        gameDifficultyShow(cardData.difficulty).forEach(element => {
            this.modalDifficulty.appendChild(element)
        })

        this.modalTime.textContent = `${cardData.time} min`
        this.startButton.setAttribute('href', '../game/' + cardData.id)
        this.modal.style.display = 'flex'
    }
    
    /**
     * Hides the game details modal.
     * 
     * @param {Event} event The click event object triggered by the close button.
     */
    hideModal(event) {
        this.modal.style.display = 'none'
        event.stopPropagation()
    }

    // /**
    //  * Generates and returns an array of star elements representing the game difficulty.
    //  * 
    //  * @param {number} difficulty The difficulty level of the game (1-5).
    //  * @returns {HTMLElement[]} An array of star elements.
    //  */
    // gameDifficultyShow(difficulty) {
    //     return [...Array(5)].map((star, index) => {
    //         const starElement = document.createElement('span')
    //         starElement.classList.add('star')
            
    //         // Filled or empty star based on difficulty
    //         starElement.innerHTML = index < difficulty ? '&#9733;' : '&#9734;' 

    //         return starElement
    //     })
    // }
}