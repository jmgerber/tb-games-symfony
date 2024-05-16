function gameDifficultyShow(difficulty) {
    return [...Array(5)].map((star, index) => {
        const starElement = document.createElement('span')
        starElement.classList.add('star')
        
        // Filled or empty star based on difficulty
        starElement.innerHTML = index < difficulty ? '&#9733;' : '&#9734;' 

        return starElement
    })
}

export default gameDifficultyShow
