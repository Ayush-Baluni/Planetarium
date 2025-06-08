<?php
require_once '../../counter.php';
// 30-minute session timeout (standard web practice)
$counter = new VisitorCounter('about', 30);
$counter->trackVisitor();
$total_visits = $counter->getTotalVisits();
$today_visits = $counter->getTodayVisits();
$session_info = $counter->getSessionInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmic Horizons Planetarium</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Share+Tech+Mono:wght@400;700&display=swap');

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
            font-family: 'Share Tech Mono', monospace;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .cosmic-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            perspective: 1000px;
        }

        .section {
            background: rgba(20, 30, 80, 0.5);
            border-left: 4px solid var(--hologram-cyan);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .section:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.3);
        }

        .visitor-data {
            background: rgba(10, 20, 70, 0.6);
            border: 2px solid var(--hologram-cyan);
            padding: 1.5rem;
            text-align: center;
            margin: 0 auto 2rem auto;
            backdrop-filter: blur(10px);
            word-wrap: break-word;
            max-width: 400px;
        }

        .holographic-header {
            font-family: 'Orbitron', sans-serif;
            text-align: center;
            color: var(--hologram-cyan);
            text-shadow: 0 0 10px rgba(0, 255, 212, 0.7);
            font-size: 2.5em;
            margin-bottom: 2rem;
            margin-top: 10px;
            transform: translateZ(50px);
            word-wrap: break-word;
        }

        .section h2 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            margin-bottom: 1rem;
            font-size: 1.8em;
        }

        .terminal-text {
            font-family: 'Share Tech Mono', monospace;
            color: var(--hologram-cyan);
            margin: 0.5rem 0;
            word-break: break-word;
        }

        .terminal-text.white-text {
            color: var(--starlight-white);
        }

        .terminal-text a {
            color: var(--hologram-cyan);
            text-decoration: none;
        }

        .terminal-text a:hover {
            text-decoration: underline;
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

        .capabilities-list {
            list-style-type: none;
            padding: 0;
            margin: 1rem 0;
        }

        .capabilities-list li {
            color: var(--hologram-cyan);
            margin: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
        }

        .capabilities-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--hologram-cyan);
        }

        .contact-info {
            display: grid;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .contact-label {
            color: var(--starlight-white);
            margin-right: 0.5rem;
        }

        .contact-value {
            color: var(--hologram-cyan);
            word-break: break-all;
        }

        @media screen and (max-width: 768px) {
            .cosmic-container {
                padding: 1.5rem;
            }

            .visitor-data {
                max-width: 350px;
                padding: 1.2rem;
                margin: 0 auto 1.5rem auto;
            }

            .holographic-header {
                font-size: 2em;
                margin-bottom: 1.5rem;
            }

            .section {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .section h2 {
                font-size: 1.5em;
            }
        }

        @media screen and (max-width: 480px) {
            .cosmic-container {
                padding: 1rem;
            }

            .visitor-data {
                max-width: 300px;
                padding: 1rem;
                margin: 0 auto 1rem auto;
            }

            .holographic-header {
                font-size: 1.5em;
                margin-bottom: 1rem;
            }

            .section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .section h2 {
                font-size: 1.2em;
            }

            .terminal-text {
                font-size: 0.9em;
            }

            .capabilities-list li {
                padding-left: 1rem;
            }

            .contact-info {
                gap: 0.3rem;
            }
        }

        @media screen and (max-width: 360px) {
            .cosmic-container {
                padding: 0.8rem;
            }

            .visitor-data {
                max-width: 280px;
                padding: 0.8rem;
            }

            .holographic-header {
                font-size: 1.3em;
            }

            .section {
                padding: 0.8rem;
            }

            .terminal-text {
                font-size: 0.8em;
            }
        }

        @media screen and (max-width: 500px) {
            .holographic-header {
                margin-top: 25px;
            }
        }

        @media screen and (max-width: 365px) {
            .holographic-header {
                margin-top: 40px;
            }
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
            <p class="terminal-text white-text">Greetings. Our directive: Bridge the infinite divide between terrestrial consciousness and the vast, unexplored realms of universal understanding.</p>
        </div>

        <div class="section">
            <h2>PLANETARIUM CAPABILITIES</h2>
            <p class="terminal-text white-text">Advanced sensory simulation chambers include:</p>
            <ul class="capabilities-list">
                <li>Informative learning through sci-fi visuals</li>
                <li>Easy Navigation Interfaces</li>
                <li>Categorically stored celestial bodies</li>
            </ul>
        </div>

        <div class="section">
            <h2>COMMUNICATION PROTOCOLS</h2>
            <div class="contact-info">
                <div>
                    <span class="contact-label">EMAIL CHANNEL:</span>
                    <span class="contact-value">scbaluni63094@gmail.com</span>
                </div>
                <div>
                    <span class="contact-label">CALL BEACON:</span>
                    <span class="contact-value">+91 8534877643</span>
                </div>
                <div>
                    <span class="contact-label">COORDINATES:</span>
                    <span class="contact-value">(30.27270741823115, 78.00069808959962), Dehradun, Uttarakhand, India, Earth</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>