import { Controller } from '@hotwired/stimulus';

/**
 * This Stimulus controller manages a riddle-answering game, fetching riddles, handling player answers, 
 * and displaying messages and timers.
 */
export default class extends Controller {
    /**
     * Fetches game data, initializes timer, and displays the first riddle.
     * 
     * @async
     */
    async connect() {
        this.riddleContainer = document.querySelector('.riddle')
        this.riddleAnswerForms = document.querySelectorAll('#riddle-answer-form')
        this.riddleQuestion = document.querySelector('.riddle-question')
        this.riddleMessage = document.querySelector('.riddle-message')
        this.nextRiddleButton = document.getElementById('next-riddle-button')
        this.submitRiddleButton = document.getElementById('riddle-submit-button')
        this.riddleTimer = document.querySelector('.riddle-timer')
        
        const gameID = document.getElementById('riddle-container').dataset.gameId
        const gameData = await this.getGameData(gameID)
        this.countdownTime = (gameData.time * 60)
        this.startTimer()

        for (const form of this.riddleAnswerForms) {
            let riddleId = form.closest('.riddle').dataset.riddleId
            await this.displayRiddle(riddleId)

            form.addEventListener('submit', async (event) => {
                event.preventDefault()
                const playerAnswer = document.getElementById('riddle-answer-input').value
                const response = await this.riddleResponseVerify(riddleId, playerAnswer)
                
                if (response.success) {
                    // Display success message
                    this.riddleMessage.textContent = response.message;

                    // Load next riddle if available
                    if (response.nextRiddleId) {
                        riddleId = response.nextRiddleId
                        this.createNextRiddleButton(riddleId)
                    } else {
                        // Handle end of game message
                        this.riddleMessage.textContent = response.message;
                        this.submitRiddleButton.disabled = true
                        form.style.display = 'none'
                        clearInterval(this.intervalId)
                        this.createEndGameButton()
                    }
                } else {
                    // Handle bad answer message
                    this.riddleMessage.textContent = response.message;
                }
            })
        }
    }

    /**
     * Fetches and validates the player's answer for a specific riddle.
     * 
     * @param {number} riddleId The ID of the riddle the player is answering.
     * @param {string} playerAnswer The player's answer to the riddle.
     * @returns {Promise<object>} An object containing the response data from the API.
     */
    async riddleResponseVerify(riddleId, playerAnswer) {
        const response = await fetch('/api/riddle/' + riddleId + '/answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ answer: playerAnswer })
        })
        if (response.ok) {
            return await response.json()
        } else {
            console.error(`Returned error : ${response.status}`)
            return {} // Indicate error with an empty object
        }
    }

    /**
     * Fetches game data from the API.
     * 
     * @param {number} gameId The ID of the game to fetch data for.
     * @returns {Promise<object>} An object containing the game data.
     */
    async getGameData(gameId) {
        const response = await fetch(`/api/game/${gameId}`)
        if(response.ok){
            const data = await response.json()
            return data
        } else {
            console.error(`Returned error : ${response.status}`)
        }
    }

    /**
     * Fetches riddle data from the API for a specific riddle.
     * 
     * @param {number} riddleId The ID of the riddle to fetch data for.
     * @returns {Promise<object>} An object containing the riddle data.
     */
    async getRiddleData(riddleId) {
        const response = await fetch(`/api/riddle/${riddleId}`)
        if(response.ok){
            return await response.json()
        } else {
            console.error(`Returned error : ${response.status}`)
            return {} // Indicate error with an empty object
        }
    }

    /**
     * Fetches riddle data from the API and displays it on the page.
     * 
     * @param {number} riddleId The ID of the riddle to display.
     * @returns {Promise<void>}
     */
    async displayRiddle(riddleId) {
        const riddleData = await this.getRiddleData(riddleId)
        this.riddleQuestion.textContent = riddleData.question

        if (!riddleData) {
            console.error("Failed to fetch riddle data")
            return
        }

        const existingPicture = document.querySelector('.riddle-picture')
        if (existingPicture) {
            this.riddleContainer.removeChild(existingPicture)
        }

        if (riddleData.picture) {
            const pictureDOM = document.createElement('img')
            pictureDOM.src = `/images/riddlesPictures/${riddleData.picture}`
            pictureDOM.classList.add('riddle-picture')
            this.riddleContainer.insertBefore(pictureDOM, this.riddleQuestion)
        }

        this.submitRiddleButton.disabled = false
        document.getElementById('riddle-answer-input').value = ''
        this.riddleMessage.textContent = ''
        this.nextRiddleButton.style.display = 'none'
    }

    /**
     * Creates and displays a button to navigate to the next riddle.
     * 
     * @param {number} nextRiddle The ID of the next riddle to display.
     */
    createNextRiddleButton(nextRiddle) {
        this.nextRiddleButton.style.display = "flex"
        this.submitRiddleButton.disabled = true

        const clickHandler = () => {
            this.displayRiddle(nextRiddle)
            // Remove event to prevent double events sending.
            this.nextRiddleButton.removeEventListener("click", clickHandler)
        }

        this.nextRiddleButton.addEventListener("click", clickHandler)
    }

    createEndGameButton() {
        const endGameButton = document.createElement('a')
        endGameButton.textContent = "Accueil"
        endGameButton.classList.add("button--highlighted", "button--small")
        endGameButton.setAttribute("href", "/dashboard")

        this.riddleContainer.append(endGameButton)
    }

    /**
     * Starts a timer for the current riddle and updates the display.
     */
    startTimer() {
        this.intervalId = setInterval(() => {
          this.countdownTime--
          this.updateTimerDisplay()
    
          if (this.countdownTime === 0) {
            clearInterval(this.intervalId)
            console.log("Time's up!")
          }
        }, 1000)
    }
    
    /**
     * Updates the on-screen timer display with the formatted countdown time.
     */
    updateTimerDisplay() {
        // Format the countdown time and update the display element
        const formattedTime = this.formatCountdownTime(this.countdownTime)
        this.riddleTimer.textContent = formattedTime
    }
    
    formatCountdownTime(time) {
        // Implement logic to format the time string
        const minutes = Math.floor(time / 60)
        const seconds = time % 60

        return `${minutes}:${seconds.toString().padStart(2, '0')}`
    }
}