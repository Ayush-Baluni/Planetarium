const planets = [
    { 
        name: "Earth", 
        image: "earth.png", 
        bgimage: "earthbg.jpg",
        age: "4.54 Billion Years",
        temperature: "15°C (Average)"
    },
    { 
        name: "Mars", 
        image: "mars.png", 
        bgimage: "marsbg.jpg",
        age: "4.6 Billion Years",
        temperature: "-63°C (Average)"
    },
    { 
        name: "Jupiter", 
        image: "jupiter.png", 
        bgimage: "jupiterbg.jpg",
        age: "4.6 Billion Years",
        temperature: "-108°C (Average)"
    },
    { 
        name: "Saturn", 
        image: "saturn.png", 
        bgimage: "saturnbg.jpg",
        age: "4.5 Billion Years",
        temperature: "-178°C (Average)"
    },
    { 
        name: "Moon", 
        image: "moon.png", 
        bgimage: "moonbg.jpg",
        age: "4.53 Billion Years",
        temperature: "127°C (Day), -173°C (Night)"
    },
    { 
        name: "Sun", 
        image: "sun.png", 
        bgimage: "sunbg.jpg",
        age: "4.6 Billion Years",
        temperature: "5,500°C (Surface)"
    }
];

  let currentPlanetIndex = 0;

  function rotatePlanet(direction) {
    const planetName = document.querySelector(".planet-name");
    const planetImage = document.querySelector(".planet-image img");
    const planetAge = document.querySelector(".planet-details .planet-detail:first-child .detail-value");
    const planetTemperature = document.querySelector(".planet-details .planet-detail:last-child .detail-value");

    const body = document.body;

    if (!planetName || !planetImage || !planetAge || !planetTemperature) {
      console.error("Required elements not found");
      return;
    }

    // Disable navigation buttons during transition
    const buttons = document.querySelectorAll('.nav-arrow');
    buttons.forEach(button => button.disabled = true);

    // Determine rotation classes based on direction
    const rotateOutClass = direction > 0 ? 'rotate-out-right' : 'rotate-out-left';
    const rotateInClass = direction > 0 ? 'rotate-in-right' : 'rotate-in-left';

    // Add rotation and fade out animation
    planetName.style.animation = "fadeOut 0.5s ease-in-out";
    planetImage.classList.add(rotateOutClass);

    setTimeout(() => {
      // Update planet index
      currentPlanetIndex += direction;
      if (currentPlanetIndex < 0) currentPlanetIndex = planets.length - 1;
      if (currentPlanetIndex >= planets.length) currentPlanetIndex = 0;

      // Update planet details
      const planet = planets[currentPlanetIndex];
      planetName.textContent = planet.name;
      planetImage.src = planet.image;
      planetImage.alt = planet.name;
      planetAge.textContent = planet.age;
      planetTemperature.textContent = planet.temperature;

      // Update background
      body.style.backgroundImage = `linear-gradient(to bottom, rgba(0, 4, 40, 0.0), rgba(0, 78, 146, 0.0)), url('${planet.bgimage}')`;

      // Remove old rotation class and add new one
      planetImage.classList.remove(rotateOutClass);
      planetImage.classList.add(rotateInClass);

      // Fade in new planet name
      planetName.style.animation = "fadeIn 0.5s ease-in-out";

      // Clean up and re-enable buttons after animation
      setTimeout(() => {
        planetImage.classList.remove(rotateInClass);
        buttons.forEach(button => button.disabled = false);
      }, 500);
    }, 500);
  }

  // Preload images
  function preloadImages() {
    planets.forEach(planet => {
      const planetImg = new Image();
      planetImg.src = planet.image;
      const bgImg = new Image();
      bgImg.src = planet.bgimage;
    });
  }

  // Initialize preloading when page loads
  window.addEventListener('load', preloadImages);





  // Touch and mobile support
function addMobileSupport() {
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });

    document.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].clientX;
        if (touchStartX - touchEndX > 50) {
            rotatePlanet(1); // Swipe left
        }
        if (touchStartX - touchEndX < -50) {
            rotatePlanet(-1); // Swipe right
        }
    });

    // Disable image dragging on mobile
    const planetImage = document.querySelector('.planet-image img');
    planetImage.addEventListener('dragstart', (e) => e.preventDefault());
}

// Call this when page loads
window.addEventListener('load', addMobileSupport);




function handleOrientationChange() {
    const planetContainer = document.querySelector('.planet-container');
    const windowWidth = window.innerWidth;
    const windowHeight = window.innerHeight;

    if (windowWidth < windowHeight) {
        // Portrait mode adjustments
        planetContainer.style.flexDirection = 'column';
    } else {
        // Landscape mode
        planetContainer.style.flexDirection = 'row';
    }
}

window.addEventListener('resize', handleOrientationChange);
window.addEventListener('orientationchange', handleOrientationChange);



// Lazy load images
function lazyLoadImages() {
    const images = document.querySelectorAll('.planet-image img');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const image = entry.target;
                image.src = image.dataset.src;
                observer.unobserve(image);
            }
        });
    });

    images.forEach(image => imageObserver.observe(image));
}






// Advanced rotation with more control
function advancedRotatePlanet(direction, options = {}) {
    const {
      speed = 500,
      easing = 'cubic-bezier(0.25, 0.1, 0.25, 1)',
      withSound = false
    } = options;
  
    // Implement more sophisticated rotation logic
    // Add optional sound effects
    // More granular animation control
}

