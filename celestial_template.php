<?php
require_once 'counter.php';

function getCelestialBodies($category, $conn) {
    $table = $category . "s";
    $sql = "SELECT * FROM $table ORDER BY name ASC";
    $result = $conn->query($sql);
    return $result;
}

function generateCelestialBlock($row) {
    return "
    <div class='celestial-block animate-on-scroll'>
        <div class='celestial-image'>
            <img src='{$row['image_path']}' alt='{$row['name']}' loading='lazy'>
            <div class='image-overlay'></div>
        </div>
        <div class='celestial-info'>
            <h2 class='body-name'>{$row['name']}</h2>
            <div class='info-grid'>
                <div class='info-item'>
                    <span class='info-label'>Temperature</span>
                    <span class='info-value'>{$row['temperature']}</span>
                </div>
                <div class='info-item'>
                    <span class='info-label'>Age</span>
                    <span class='info-value'>{$row['age']}</span>
                </div>
                <div class='info-item'>
                    <span class='info-label'>Distance</span>
                    <span class='info-value'>{$row['distance']}</span>
                </div>
            </div>
            <div class='description-container'>
                <p class='description'>{$row['description']}</p>
            </div>
        </div>
    </div>
    ";
}
?>

<style>
/* Common Responsive Styles for All Category Pages */
:root {
    --deep-space-blue: #0B1746;
    --nebula-purple: #4A2C5D;
    --hologram-cyan: #00FFD4;
    --starlight-white: #E6E6FA;
    --glow-pink: #FF00FF;
    --cosmic-black: #000000;
    --container-width-xl: 1400px;
    --container-width-lg: 1200px;
    --container-width-md: 960px;
    --container-width-sm: 95%;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body, html {
    min-height: 100vh;
    font-family: 'Roboto Mono', monospace;
    background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
    color: var(--starlight-white);
    overflow-x: hidden;
}

.page-container {
    width: var(--container-width-xl);
    margin: 0 auto;
    padding: 2rem;
}

.page-title {
    font-family: 'Orbitron', sans-serif;
    text-align: center;
    font-size: clamp(2rem, 5vw, 3.5rem);
    margin: 2rem 0;
    padding: 1rem;
    color: var(--hologram-cyan);
    text-shadow: 0 0 15px rgba(0,255,212,0.5);
}

.celestial-block {
    background: rgba(20, 30, 80, 0.6);
    border-left: 6px solid var(--hologram-cyan);
    margin: 2rem auto;
    padding: clamp(1rem, 3vw, 2rem);
    display: flex;
    gap: clamp(1rem, 3vw, 2rem);
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    border-radius: 10px;
    opacity: 0;
    transform: translateY(20px);
}

.celestial-block.visible {
    opacity: 1;
    transform: translateY(0);
}

.celestial-image {
    flex: 0 0 clamp(200px, 30vw, 400px);
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.celestial-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.5s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(0,255,212,0.2),
        transparent
    );
    opacity: 0;
    transition: opacity 0.3s ease;
}

.celestial-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.body-name {
    font-size: clamp(1.5rem, 3vw, 2.5rem);
    color: var(--hologram-cyan);
    font-family: 'Orbitron', sans-serif;
    margin-bottom: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.info-item {
    background: rgba(0,255,212,0.1);
    padding: 1rem;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.info-label {
    display: block;
    font-size: clamp(0.8rem, 1.5vw, 1rem);
    color: var(--hologram-cyan);
    margin-bottom: 0.5rem;
}

.info-value {
    display: block;
    font-size: clamp(0.9rem, 1.8vw, 1.1rem);
}

.description-container {
    background: rgba(0,0,0,0.2);
    padding: clamp(1rem, 2vw, 1.5rem);
    border-radius: 8px;
    margin-top: auto;
}

.description {
    font-size: clamp(0.9rem, 1.8vw, 1.1rem);
    line-height: 1.6;
}

/* Hover Effects */
.celestial-block:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
}

.celestial-block:hover .celestial-image img {
    transform: scale(1.05);
}

.celestial-block:hover .image-overlay {
    opacity: 1;
}

.info-item:hover {
    transform: translateY(-2px);
}

/* Responsive Design */
@media screen and (max-width: 1440px) {
    .page-container {
        width: var(--container-width-lg);
    }
}

@media screen and (max-width: 1200px) {
    .page-container {
        width: var(--container-width-md);
    }
}

@media screen and (max-width: 992px) {
    .celestial-block {
        flex-direction: column;
    }

    .celestial-image {
        flex: 0 0 300px;
        width: 100%;
    }
}

@media screen and (max-width: 768px) {
    .page-container {
        width: var(--container-width-sm);
        padding: 1rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .celestial-image {
        flex: 0 0 250px;
    }
}

@media screen and (max-width: 480px) {
    .page-title {
        font-size: 1.8rem;
    }

    .celestial-block {
        padding: 1rem;
    }

    .celestial-image {
        flex: 0 0 200px;
    }

    .body-name {
        font-size: 1.5rem;
    }
}

/* Animation for scroll reveal */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-on-scroll {
    animation: fadeInUp 0.6s ease forwards;
}
</style>

<script>
// Intersection Observer for scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.celestial-block').forEach(block => {
        observer.observe(block);
    });
});
</script>