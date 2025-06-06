<?php
require_once 'counter.php';
$counter = new VisitorCounter('category');
$counter->trackVisitor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cosmic Horizons Planetarium - Categories</title>

  <!-- Font Imports -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">

  <style>
    :root {
        --deep-space-blue: #0B1746;
        --nebula-purple: #4A2C5D;
        --hologram-cyan: #00FFD4;
        --starlight-white: #E6E6FA;
        --glow-pink: #FF00FF;
        --cosmic-black: #000000;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body, html {
        height: 100%;
        font-family: 'Roboto Mono', monospace;
        background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
        color: var(--starlight-white);
        overflow-x: hidden;
        line-height: 1.6;
        position: relative;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: repeating-linear-gradient(
            0deg,
            rgba(0, 0, 0, 0.15),
            rgba(0, 0, 0, 0.15) 1px,
            transparent 1px,
            transparent 2px
        );
        pointer-events: none;
        z-index: 10;
    }

    .cosmic-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 30% 50%, rgba(0,255,212,0.1) 0%, transparent 50%),
            radial-gradient(circle at 70% 30%, rgba(255,0,255,0.1) 0%, transparent 50%),
            linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
        background-attachment: fixed;
        opacity: 0.8;
        z-index: -1;
    }

    .cosmic-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .holographic-header {
        font-family: 'Orbitron', sans-serif;
        color: var(--hologram-cyan);
        text-align: center;
        font-size: 3.5rem;
        margin-bottom: 3rem;
        text-shadow: 
            0 0 10px rgba(0,255,212,0.7),
            0 0 20px rgba(0,255,212,0.5),
            0 0 30px rgba(0,255,212,0.3);
        animation: pulse 4s infinite alternate;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        flex-grow: 1;
    }

    .category-box {
        background: rgba(20, 30, 80, 0.6);
        border-left: 6px solid var(--hologram-cyan);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        box-shadow: 
            0 10px 20px rgba(0,0,0,0.2),
            0 0 20px rgba(0,255,212,0.2);
    }

    .category-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg, 
            transparent, 
            rgba(0,255,212,0.1), 
            transparent
        );
        transform: rotate(-45deg);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .category-box:hover::before {
        opacity: 1;
    }

    .category-box:hover {
        transform: scale(1.05);
        box-shadow: 
            0 15px 30px rgba(0,0,0,0.3),
            0 0 30px rgba(0,255,212,0.3);
    }

    .category-title {
        font-family: 'Orbitron', sans-serif;
        color: var(--hologram-cyan);
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
    }

    .category-description {
        flex-grow: 1;
        margin-bottom: 1.5rem;
        color: var(--starlight-white);
        opacity: 0.9;
    }

    .category-button {
        align-self: flex-start;
        background: var(--hologram-cyan);
        color: var(--deep-space-blue);
        font-family: 'Orbitron', sans-serif;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .category-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            120deg, 
            transparent, 
            rgba(255,255,255,0.3), 
            transparent
        );
        transition: all 0.4s ease;
        z-index: -1;
    }

    .category-button:hover::before {
        left: 100%;
    }

    .category-button:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(0,255,212,0.5);
    }

    @keyframes pulse {
        from { transform: scale(1); }
        to { transform: scale(1.02); }
    }

    @media screen and (max-width: 1200px) {
        .cosmic-container {
            max-width: 1000px;
            padding: 1.5rem;
        }

        .holographic-header {
            font-size: 3rem;
        }

        .category-grid {
            gap: 1.5rem;
        }
    }

    @media screen and (max-width: 992px) {
        .cosmic-container {
            max-width: 90%;
        }

        .category-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .holographic-header {
            font-size: 2.75rem;
            margin-bottom: 2.5rem;
        }

        .category-box {
            padding: 1.75rem;
        }
    }

    @media screen and (max-width: 768px) {
        .cosmic-container {
            max-width: 95%;
            padding: 1.25rem;
        }

        .holographic-header {
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        .category-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .category-box {
            padding: 1.5rem;
        }

        .category-title {
            font-size: 2rem;
        }

        .category-description {
            font-size: 0.95rem;
        }

        .category-button {
            padding: 0.875rem 1.75rem;
            font-size: 1.1rem;
        }
    }

    @media screen and (max-width: 480px) {
        .cosmic-container {
            padding: 1rem;
        }

        .holographic-header {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .category-box {
            padding: 1.25rem;
        }

        .category-title {
            font-size: 1.75rem;
            margin-bottom: 1.25rem;
        }

        .category-description {
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

        .category-button {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            width: 100%;
            text-align: center;
        }
    }

    @media screen and (max-width: 360px) {
        .holographic-header {
            font-size: 1.75rem;
            margin-bottom: 1.25rem;
        }

        .category-box {
            padding: 1rem;
        }

        .category-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .category-button {
            padding: 0.625rem 1.25rem;
        }
    }
  </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>

    <div class="cosmic-container">
        <h1 class="holographic-header">Explore Celestial Bodies</h1>

        <div class="category-grid">
            <div class="category-box">
                <h2 class="category-title">Planets</h2>
                <p class="category-description">Discover the vast worlds orbiting stars, each with its unique characteristics and secrets waiting to be explored. Traverse through diverse planetary landscapes and unravel the mysteries of celestial formations.</p>
                <a href = "planets.php"><button class="category-button">Explore Planets</button></a>
            </div>
            <div class="category-box">
                <h2 class="category-title">Moons</h2>
                <p class="category-description">Delve into the moons of the solar system, their enigmatic surfaces, and hidden mysteries. Explore the unique environments that orbit planets, each with its own geological and astronomical significance.</p>
                <a href = "moons.php"><button class="category-button">Explore Moons</button></a>
            </div>
            <div class="category-box">
                <h2 class="category-title">Stars</h2>
                <p class="category-description">Journey through the cosmos to uncover the stories of the stars, the light of distant galaxies. Witness the birth, life, and spectacular deaths of stellar bodies that illuminate the vast cosmic canvas.</p>
                <a href = "stars.php"><button class="category-button">Explore Stars</button></a>
            </div>
        </div>
    </div>
</body>
</html>