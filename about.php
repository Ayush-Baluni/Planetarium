<?php
require_once 'counter.php';
$counter = new VisitorCounter('about');
$total_visits = $counter->getTotalVisits();
$today_visits = $counter->getTodayVisits();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cosmic Horizons Planetarium</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto+Mono:wght@300;400&display=swap');

        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
        }

        body, html {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
            color: var(--starlight-white);
            font-family: 'Roboto Mono', monospace;
            line-height: 1.6;
            min-height: 100vh;
        }

        .cosmic-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            perspective: 1000px;
        }

        .holographic-header {
            font-family: 'Orbitron', sans-serif;
            text-align: center;
            color: var(--hologram-cyan);
            text-shadow: 0 0 10px rgba(0, 255, 212, 0.7);
            font-size: 2.5em;
            margin-bottom: 30px;
            transform: translateZ(50px);
        }

        .visitor-data {
            background: rgba(10, 20, 70, 0.6);
            border: 2px solid var(--hologram-cyan);
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .section {
            background: rgba(20, 30, 80, 0.5);
            border-left: 4px solid var(--hologram-cyan);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .section:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.3);
        }

        .section h2 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            margin-bottom: 15px;
        }

        .terminal-text {
            font-family: 'Roboto Mono', monospace;
            color: var(--hologram-cyan);
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

        .terminal-text a {
            color: var(--hologram-cyan); /* Match the existing text color */
            text-decoration: none; /* Remove underline */
        }

        .terminal-text a:hover {
            text-decoration: underline; /* Optional: Add subtle hover effect */
        }


    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-container">
        <div class="visitor-data">
            <h3 class="terminal-text">COSMIC CONNECTION METRICS</h3>
            <p>TOTAL CELESTIAL VIEWS: <?php echo $total_visits; ?></p>
            <p>CELESTIAL VIEWS TODAY: <?php echo $today_visits; ?></p>
        </div>

        <h1 class="holographic-header">COSMIC HORIZONS PLANETARIUM</h1>

        <div class="section">
            <h2>MISSION</h2>
            <p>Greetings. Our directive: Bridge the infinite divide between terrestrial consciousness and the vast, unexplored realms of universal understanding.</p>
        </div>

        <div class="section">
            <h2>PLANETARIUM CAPABILITIES</h2>
            <p>Advanced sensory simulation chambers include:</p>
            <ul class="terminal-text">
                <li>Informative learning through sci fi visuals</li>
                <li>Easy Navigation Interfaces</li>
                <li>Categorically stored celestial bodies</li>
            </ul>
        </div>

        <div class="section">
            <h2>COMMUNICATION PROTOCOLS</h2>
            <p>
                EMAIL CHANNEL: <span  class="terminal-text"><a href = "mailto:scbaluni63094@gmail.com">scbaluni63094@gmail.com</a></span><br>
                CALL BEACON: <span  class="terminal-text"><a href="tel:+918534077643">+91 8534077643</a></span><br>
                COORDINATES: <span  class="terminal-text">(30.27270741823115, 78.00069808959962), Dehradun, Uttarakhand, India, Earth</span>
            </p>
        </div>
    </div>
</body>
</html>