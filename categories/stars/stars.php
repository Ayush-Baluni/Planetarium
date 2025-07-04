<?php
require_once '../../counter.php';
$counter = new VisitorCounter('stars');
$counter->trackVisitor();

// Database connection
$conn = new mysqli("localhost", "root", "", "planetarium_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once '../../celestial_template.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stars - Cosmic Horizons Planetarium</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
            --glow-pink: #FF00FF;
            --cosmic-black: #000000;
            --star-gold: #FFD700;
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

        .nav-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(11, 23, 70, 0.9);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 0.75rem;
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-back {
            color: var(--star-gold);
            background: transparent;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(0.8rem, 2vw, 0.9rem);
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border: 1px solid var(--star-gold);
            border-radius: 4px;
            white-space: nowrap;
            cursor: pointer;
        }

        .nav-back:hover {
            background: var(--star-gold);
            color: var(--deep-space-blue);
        }

        .page-title {
            text-align: center;
            color: var(--star-gold);
            font-family: 'Orbitron', sans-serif;
            padding: 4rem 1rem 2rem;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            text-shadow: 0 0 10px rgba(255,215,0,0.5);
            position: relative;
            word-wrap: break-word;
        }

        .page-title::after {
            content: '✧';
            position: absolute;
            top: 50%;
            margin-left: 0.5rem;
            font-size: clamp(1rem, 3vw, 1.2rem);
            animation: twinkle 1.5s infinite alternate;
        }

        .celestial-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem 2rem;
        }

        .celestial-block {
            background: rgba(20, 30, 80, 0.6);
            border-left: 4px solid var(--star-gold);
            margin: 1rem auto;
            padding: 1rem;
            display: flex;
            flex-direction: row;
            gap: 1rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-radius: 0px;
            transition: transform 0.3s ease;
            min-height: 200px;
            width: 100%;
        }

        .celestial-image {
            flex: 0 0 200px;
            height: 200px;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }

        .celestial-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .celestial-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }

        .info-header h2 {
            color: var(--star-gold);
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            margin-bottom: 0.5rem;
            text-shadow: 0 0 5px rgba(255,215,0,0.3);
            word-wrap: break-word;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .info-item {
            background: rgba(255,215,0,0.1);
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid rgba(255,215,0,0.2);
            min-width: 0;
        }

        .info-label {
            display: block;
            font-size: clamp(0.7rem, 2vw, 0.8rem);
            color: var(--star-gold);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info-value {
            display: block;
            font-size: clamp(0.8rem, 2.5vw, 0.9rem);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .celestial-block:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 8px 20px rgba(0,0,0,0.3),
                0 0 20px rgba(255,215,0,0.2);
        }

        @keyframes twinkle {
            from { opacity: 0.3; }
            to { opacity: 1; }
        }

        /* Tablet styles */
        @media screen and (max-width: 992px) {
            .celestial-container {
                padding: 0 0.75rem 2rem;
            }

            .celestial-block {
                margin: 1rem 0;
                padding: 1rem;
            }

            .celestial-image {
                flex: 0 0 180px;
                height: 180px;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
        }

        /* Mobile landscape and small tablets */
        @media screen and (max-width: 768px) {
            .nav-container {
                padding: 0.5rem;
            }

            .page-title {
                padding: 3.5rem 1rem 1.5rem;
            }

            .celestial-block {
                flex-direction: column;
                align-items: center;
                text-align: center;
                min-height: auto;
                padding: 1rem;
            }

            .celestial-image {
                flex: none;
                width: 200px;
                height: 200px;
                margin-bottom: 1rem;
            }

            .celestial-info {
                width: 100%;
            }

            .info-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                justify-items: center;
            }

            .info-item {
                text-align: center;
                width: 100%;
            }
        }

        /* Mobile portrait */
        @media screen and (max-width: 480px) {
            .nav-container {
                padding: 0.5rem;
            }

            .nav-back {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .page-title {
                padding: 3.5rem 0.5rem 1rem;
            }

            .celestial-container {
                padding: 0 0.5rem 1rem;
            }

            .celestial-block {
                margin: 0.75rem 0;
                padding: 0.75rem;
                gap: 0.75rem;
            }

            .celestial-image {
                width: 160px;
                height: 160px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                margin-top: 0.75rem;
            }

            .info-item {
                padding: 0.75rem 0.5rem;
            }
        }

        /* Extra small screens */
        @media screen and (max-width: 360px) {
            .celestial-block {
                margin: 0.5rem 0;
                padding: 0.5rem;
            }

            .celestial-image {
                width: 140px;
                height: 140px;
            }

            .page-title {
                padding: 3rem 0.25rem 0.75rem;
            }

            .celestial-container {
                padding: 0 0.25rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    <nav class="nav-container">
        <div class="nav-content">
            <button onclick="history.back()" class="nav-back">← Back</button>
            <!-- Search Container -->
            <div style="position: relative; width: min(300px, 40vw);">
                <div style="position: relative; 
                           border-left: 4px solid var(--star-gold);
                           background: rgba(20, 30, 80, 0.6);
                           backdrop-filter: blur(10px);
                           box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                    <input type="text" id="searchInput" placeholder="Search stars..." 
                           style="width: 100%; 
                                  padding: 0.5rem 3.5rem 0.5rem 1rem; 
                                  background: transparent;
                                  border: none;
                                  color: var(--starlight-white); 
                                  font-family: 'Roboto Mono', monospace; 
                                  font-size: clamp(0.8rem, 2vw, 0.9rem);">
                    <style>
                        @media screen and (max-width: 768px) {
                            #searchInput::placeholder {
                                font-size: 0.8rem;
                            }
                        }
                        @media screen and (max-width: 480px) {
                            #searchInput {
                                padding-right: 3rem;
                            }
                            #searchInput::placeholder {
                                content: "Search...";
                            }
                        }
                    </style>
                    <button id="clearSearch" 
                            style="position: absolute; 
                                   right: 2rem; 
                                   top: 50%; 
                                   transform: translateY(-50%);
                                   background: none;
                                   border: none;
                                   color: var(--star-gold);
                                   cursor: pointer;
                                   display: none;
                                   padding: 0.25rem;
                                   font-size: 1.2rem;
                                   line-height: 1;">×</button>
                    <span style="position: absolute; 
                                right: 0.75rem; 
                                top: 50%; 
                                transform: translateY(-50%); 
                                color: var(--star-gold);
                                font-size: 0.9rem;">⌕</span>
                </div>
            </div>
        </div>
    </nav>

    <h1 class="page-title">Explore Stars</h1>
    
    <div class="celestial-container">
        <?php
        $bodies = getCelestialBodies('star', $conn);
        
        if ($bodies->num_rows > 0) {
            while($row = $bodies->fetch_assoc()) {
                // Generate the individual page filename
                $safe_name = preg_replace('/[^a-zA-Z0-9\-_]/', '', str_replace(' ', '-', $row['name']));
                $page_filename = $safe_name . "-" . $row['id'] . ".php";
                
                echo '<div class="celestial-block">
                        <div class="celestial-image">
                            <img src="../../'.$row['image_path'].'" alt="'.$row['name'].'" loading="lazy">
                        </div>
                        <div class="celestial-info">
                            <div class="info-header">
                                <h2><a href="'.$page_filename.'" style="color: var(--star-gold); text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow=\'0 0 10px rgba(255,215,0,0.8)\'" onmouseout="this.style.textShadow=\'0 0 5px rgba(255,215,0,0.3)\'">'.$row['name'].'</a></h2>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Temperature</span>
                                        <span class="info-value">'.$row['temperature'].'</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Age</span>
                                        <span class="info-value">'.$row['age'].'</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Distance</span>
                                        <span class="info-value">'.$row['distance'].'</span>
                                    </div>
                                                            </div>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<div style="text-align: center; padding: 2rem; color: var(--star-gold);">
                    <p>No stars have been discovered yet...</p>
                </div>';
        }
        
        $conn->close();
        ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.celestial-block').forEach(block => {
            observer.observe(block);
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const clearButton = document.getElementById('clearSearch');
        const celestialBlocks = document.querySelectorAll('.celestial-block');

        // Show/hide clear button based on input
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            clearButton.style.display = searchTerm.length > 0 ? 'block' : 'none';
            
            celestialBlocks.forEach(block => {
                const starName = block.querySelector('h2 a').textContent.toLowerCase();
                
                if (starName.includes(searchTerm)) {
                    block.style.display = 'flex';
                    block.style.animation = 'fadeIn 0.3s ease';
                } else {
                    block.style.display = 'none';
                }
            });

            // Show "no results" message if no blocks are visible
            const visibleBlocks = Array.from(celestialBlocks).filter(block => block.style.display !== 'none');
            let noResultsMsg = document.getElementById('noResultsMsg');
            
            if (visibleBlocks.length === 0 && searchTerm !== '') {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMsg';
                    noResultsMsg.style.cssText = 'text-align: center; padding: 2rem; color: var(--star-gold); font-family: "Orbitron", sans-serif;';
                    noResultsMsg.innerHTML = '<p>No stars found matching "' + searchTerm + '"</p>';
                    document.querySelector('.celestial-container').appendChild(noResultsMsg);
                } else {
                    noResultsMsg.innerHTML = '<p>No stars found matching "' + searchTerm + '"</p>';
                    noResultsMsg.style.display = 'block';
                }
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        });

        // Clear search functionality
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });
    });
    </script>
</body>
</html>