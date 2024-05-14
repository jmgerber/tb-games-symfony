import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    async connect() {
        this.riddleContainer = document.querySelector('.riddle')
        this.riddleAnswerForm = document.querySelectorAll('#riddle_answer_form')
        this.riddleQuestion = document.querySelector('.riddle_question')
        this.riddleMessage = document.querySelector('.riddle_message')
        this.nextRiddleButton = document.getElementById('next_riddle_button')
        this.submitRiddleButton = document.getElementById('riddle_submit_button')
        this.riddleTimer = document.querySelector('.riddle_timer')
        
        const gameID = document.getElementById('riddle_container').dataset.gameId
        const gameData = await this.getGameData(gameID)
        this.countdownTime = (gameData.time * 60)
        this.startTimer()

        this.riddleAnswerForm.forEach(form => {

            let riddleId = form.closest('.riddle').dataset.riddleId
            this.displayRiddle(riddleId)

            form.addEventListener('submit', async (event) => {
                event.preventDefault()
                const playerAnswer = document.getElementById('riddle_answer_input').value
                const data = await this.riddleResponseVerify(riddleId, playerAnswer)
                
                if (data.success) {
                    // Display success message
                    this.riddleMessage.textContent = data.message;

                    // Load next riddle if available
                    if (data.nextRiddleId) {
                        riddleId = data.nextRiddleId
                        this.createNextRiddleButton(riddleId)
                    } else {
                        // Handle end of game message
                        this.riddleMessage.textContent = data.message;
                        this.submitRiddleButton.setAttribute('disabled', "")
                        form.style.display = 'none'
                        clearInterval(this.intervalId)
                    }
                } else {
                    // Handle bad answer message
                    this.riddleMessage.textContent = data.message;
                }
            })
        })
    }

    async riddleResponseVerify(riddleId, playerAnswer) {
        const response = await fetch('/api/riddle/' + riddleId + '/answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ answer: playerAnswer })
        })
        if(response.ok){
            const data = await response.json()
            return data
        } else {
            console.error(`Returned error : ${response.status}`)
        }
    }

    async getRiddleData(riddleId) {
        const response = await fetch('/api/riddle/' + riddleId)
        if(response.ok){
            const data = await response.json()
            return data
        } else {
            console.error(`Returned error : ${response.status}`)
        }
    }

    async getGameData(gameId) {
        const response = await fetch('/api/game/' + gameId)
        if(response.ok){
            const data = await response.json()
            return data
        } else {
            console.error(`Returned error : ${response.status}`)
        }
    }

    async displayRiddle(riddleId) {
        const riddleData = await this.getRiddleData(riddleId)
        this.riddleQuestion.textContent = riddleData.question

        if (document.querySelector('.riddle_picture')) {
            this.riddleContainer.removeChild(document.querySelector('.riddle_picture'))
        }

        if (riddleData.picture) {
            const pictureDOM = document.createElement('img')
            pictureDOM.src = '/images/riddlesPictures/' + riddleData.picture
            pictureDOM.classList.add('riddle_picture')
            this.riddleContainer.insertBefore(pictureDOM, this.riddleQuestion)
        }

        this.submitRiddleButton.disabled = false
        document.getElementById('riddle_answer_input').value = ''
        this.riddleMessage.textContent = ''
        this.nextRiddleButton.style.display = 'none'
    }

    createNextRiddleButton(nextRiddle) {
        this.nextRiddleButton.style.display = "flex"
        this.submitRiddleButton.disabled = true

        const clickHandler = () => {
            this.displayRiddle(nextRiddle)
            this.nextRiddleButton.removeEventListener("click", clickHandler)
        }

        this.nextRiddleButton.addEventListener("click", clickHandler)
    }

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