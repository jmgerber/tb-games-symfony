import { Controller } from '@hotwired/stimulus';
import gameDifficultyShow from './difficulty-display_controller.js';

/**
 * This Stimulus controller manages a game card container, fetching game data and displaying modal details on card click.
 */
export default class extends Controller {

    /**
     * Fetches game data from the API on controller initialization.
     * Handles potential errors and sets the `games` property.
     * 
     * @returns {Promise | null} Promise resolving to fetched game data or null on error.
     */
    async connect() {
        this.games = await this.fetchGameData()

        if (!this.games) return

        this.initializeModalElements()
        this.setupGameCards()
    }

    /**
     * Fetches game data from the provided API endpoint.
     * 
     * @returns {Promise | null} Promise resolving to fetched game data or null on error.
     */
    async fetchGameData() {
        try {
            const response = await fetch('http://localhost:8000/api/games')
            this.gamesData = await response.json()
            return this.gamesData
        } catch (error) {
            console.error('Error fetching game data:', error)
            return null
        }
    }

    /**
     * Initializes references to modal elements within the DOM.
     */
    initializeModalElements() {
        this.modal = document.getElementById('modal')
        this.modalTitle = this.modal.querySelector('.modal-title')
        this.modalPicture = this.modal.querySelector('.modal-picture')
        this.modalDescription = this.modal.querySelector('.modal-description')
        this.modalDifficulty = this.modal.querySelector('.modal-difficulty')
        this.modalTime = this.modal.querySelector('.time-text')
        this.closeButton = this.modal.querySelector('.close-modal-btn')
        this.startButton = this.modal.querySelector('.start-button')
    }

    /**
     * Sets up click events for game cards and the close button.
     * Loops through all game cards, displays difficulty and adds click event.
     */
    setupGameCards() {
        const cards = document.querySelectorAll('.game-card')
        cards.forEach(card => {
            // Diplays the difficulty of each game with stars
            const difficultyDisplay = card.querySelector('.game-difficulty')
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
        this.startButton.addEventListener('click', (event) => {
            event.preventDefault()
            window.location.href = `../game/${cardData.id}`
            this.hideModal(event)
        })
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
}