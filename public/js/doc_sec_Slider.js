// Doctor Carousel Slider
let currentSlide = 0;

function getSlidesToShow() {
    const width = window.innerWidth;
    if (width >= 1200) return 4;
    if (width >= 992) return 3;
    if (width >= 768) return 2;
    return 1;
}

function slideCarousel(direction) {
    const slider = document.getElementById('doctorsSlider');
    const cards = slider?.querySelectorAll('.doctor-card');

    if (!cards || cards.length === 0) return;

    const cardWidth = cards[0].offsetWidth + 16; // Card width + gap
    const slidesToShow = getSlidesToShow();
    const maxSlide = cards.length - slidesToShow;

    // Update slide position
    currentSlide += direction;

    // Boundary checks
    if (currentSlide < 0) {
        currentSlide = 0;
    } else if (currentSlide > maxSlide) {
        currentSlide = maxSlide;
    }

    // Smooth scroll to position
    slider.scrollTo({
        left: currentSlide * cardWidth,
        behavior: 'smooth'
    });

    // Update arrow states
    updateArrows();
}

function updateArrows() {
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const slider = document.getElementById('doctorsSlider');
    const cards = slider?.querySelectorAll('.doctor-card');

    if (!cards || cards.length === 0 || !prevBtn || !nextBtn) return;

    const slidesToShow = getSlidesToShow();
    const maxSlide = cards.length - slidesToShow;

    prevBtn.disabled = currentSlide === 0;
    nextBtn.disabled = currentSlide >= maxSlide;
}

// Auto-play carousel
let autoplayInterval;

function startAutoplay() {
    autoplayInterval = setInterval(() => {
        const slider = document.getElementById('doctorsSlider');
        const cards = slider?.querySelectorAll('.doctor-card');

        if (!cards || cards.length === 0) return;

        const slidesToShow = getSlidesToShow();
        const maxSlide = cards.length - slidesToShow;

        if (currentSlide >= maxSlide) {
            currentSlide = 0;
        } else {
            currentSlide++;
        }

        slideCarousel(0);
    }, 4000); // 4 seconds
}

function stopAutoplay() {
    clearInterval(autoplayInterval);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    updateArrows();
    startAutoplay();

    const slider = document.getElementById('doctorsSlider');
    if (slider) {
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
    }

    window.addEventListener('resize', () => {
        updateArrows();
    });
});

// Touch swipe support
let touchStartX = 0;
let touchEndX = 0;

document.getElementById('doctorsSlider')?.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

document.getElementById('doctorsSlider')?.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > 50) {
        if (diff > 0) {
            slideCarousel(1); // Swipe left
        } else {
            slideCarousel(-1); // Swipe right
        }
    }
});



// function slideCarousel(direction) {
//   const slider = document.getElementById('doctorsSlider');
//   if (!slider) return;

//   const firstCard = slider.querySelector('.doctor-card');
//   const gap = 24; // ~1.5rem
//   const step = firstCard ? (firstCard.getBoundingClientRect().width + gap) : 320;

//   slider.scrollBy({ left: direction * step, behavior: 'smooth' });
// }

