<?php
require_once 'counter.php';
$counter = new VisitorCounter('stars');
$counter->trackVisitor();

// Database connection
$conn = new mysqli("localhost", "root", "", "planetarium_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once 'celestial_template.php';
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
            --star-gold:#C0C0C0;
            --glow-blue: #4169E1;
            --cosmic-black: #000000;
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
        }

        .nav-back {
            color: var(--star-gold);
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border: 1px solid var(--star-gold);
            border-radius: 4px;
        }

        .nav-back:hover {
            background: var(--star-gold);
            color: var(--deep-space-blue);
        }

        .page-title {
            text-align: center;
            color: var(--star-gold);
            font-family: 'Orbitron', sans-serif;
            padding: 3rem 1rem 1rem;
            font-size: 2rem;
            text-shadow: 0 0 10px rgba(192,192,192,0.5);
            position: relative;
        }

        .page-title::after {
            content: '✧';
            position: absolute;
            top: 50%;
            margin-left: 0.5rem;
            font-size: 1.2rem;
            animation: twinkle 1.5s infinite alternate;
        }

        .celestial-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .celestial-block {
            background: rgba(20, 30, 80, 0.6);
            border-left: 4px solid var(--star-gold);
            margin: 1rem auto;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-radius: 8px;
            transition: transform 0.3s ease;
            height: 220px;
        }

        .celestial-image {
            flex: 0 0 200px;
            height: 200x;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }

        .celestial-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid var(--star-gold);
        }

        .celestial-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }

        .info-header h2 {
            color: var(--star-gold);
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 5px rgba(0,255,212,0.3);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .info-item {
            background: rgba(192,192,192,0.1);
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid rgba(192,192,192,0.1);
        }

        .info-label {
            display: block;
            font-size: 0.8rem;
            color: var(--star-gold);
            margin-bottom: 0.25rem;
        }

        .info-value {
            display: block;
            font-size: 0.9rem;
        }



        .celestial-block:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 8px 20px rgba(0,0,0,0.3),
                0 0 20px rgba(192,192,192,0.2);
        }

        @keyframes twinkle {
            from { opacity: 0.3; }
            to { opacity: 1; }
        }



        @media screen and (max-width: 992px) {
            .celestial-container {
                padding: 0 0.75rem;
            }

            .celestial-block {
                height: auto;
                min-height: 200px;
            }
        }

        @media screen and (max-width: 768px) {
            .celestial-image {
                flex: 0 0 160px;
                height: 160px;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 480px) {
            .page-title {
                font-size: 1.5rem;
                padding: 3.5rem 0.5rem 1rem;
            }

            .celestial-block {
                padding: 0.75rem;
                gap: 0.75rem;
            }

            .celestial-image {
                flex: 0 0 140px;
                height: 140px;
            }

            .info-grid {
                grid-template-columns: repeat(1, 1fr);
                gap: 0.5rem;
            }

            .info-header h2 {
                font-size: 1.2rem;
            }


        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    <nav class="nav-container">
        <div class="nav-content">
            <a href="#" onclick="history.back(); return false;" class="nav-back">← Back to Categories</a>
        </div>
    </nav>

    <h1 class="page-title">Explore Moons</h1>
    <div class="celestial-container">
        <?php
        $bodies = getCelestialBodies('moon', $conn);
        
        if ($bodies->num_rows > 0) {
            while($row = $bodies->fetch_assoc()) {
                // Generate the individual page filename
                $safe_name = preg_replace('/[^a-zA-Z0-9\-_]/', '', str_replace(' ', '-', $row['name']));
                $page_filename = $safe_name . "-" . $row['id'] . ".php";
                
                echo '<div class="celestial-block">
                        <div class="celestial-image">
                            <img src="'.$row['image_path'].'" alt="'.$row['name'].'" loading="lazy">
                        </div>
                        <div class="celestial-info">
                            <div class="info-header">
                                <h2><a href="'.$page_filename.'" style="color: var(--star-gold); text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.textShadow=\'0 0 10px rgba(192,192,192,0.8)\'" onmouseout="this.style.textShadow=\'0 0 5px rgba(192,192,192,0.3)\'">'.$row['name'].'</a></h2>
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
    });
    </script>
</body>
</html>