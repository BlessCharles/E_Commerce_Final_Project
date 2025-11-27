let currentIndex = 0;
const cards = document.querySelectorAll('.event-card');
const indicators = document.querySelectorAll('.indicator');
const totalCards = cards.length;
        
function updateCarousel() {
    cards.forEach((card, index) => {
        card.classList.remove('center', 'left', 'right', 'hidden');
                
        if (index === currentIndex) {
            card.classList.add('center');
        } else if (index === (currentIndex - 1 + totalCards) % totalCards) {
            card.classList.add('left');
        } else if (index === (currentIndex + 1) % totalCards) {
            card.classList.add('right');
        } else {
            card.classList.add('hidden');
        }
    });
            
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentIndex);
    });
}
        
function nextSlide() {
    currentIndex = (currentIndex + 1) % totalCards;
    updateCarousel();
}
        
function prevSlide() {
    currentIndex = (currentIndex - 1 + totalCards) % totalCards;
    updateCarousel();
}
        
function goToSlide(index) {
    currentIndex = index;
    updateCarousel();
}
        
// Click on center card to select event
cards.forEach(card => {
    card.addEventListener('click', () => {
        if (card.classList.contains('center')) {
            const eventType = card.dataset.event;
            window.location.href = `budget_input.php?event=${eventType}`;
        }
    });
});
        
// Auto-rotate every 5 seconds
setInterval(nextSlide, 5000);