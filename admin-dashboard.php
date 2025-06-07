<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Control Dashboard - Cosmic Horizons</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            min-height: 100vh;
            font-family: 'Share Tech Mono', monospace;
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
            z-index: 1000;
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

        .dashboard-container {
            background: rgba(11, 23, 70, 0.6);
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            backdrop-filter: blur(10px);
            border: 1px solid var(--hologram-cyan);
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.2);
            z-index: 100;
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-size: 2em;
            text-shadow: 0 0 10px rgba(0, 255, 212, 0.5);
        }

        .dashboard-option {
            background: rgba(0, 255, 212, 0.05);
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--hologram-cyan);
        }

        .dashboard-option:hover {
            background: rgba(0, 255, 212, 0.1);
            transform: translateX(10px);
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.3);
        }

        .dashboard-option h3 {
            color: var(--hologram-cyan);
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .dashboard-option p {
            color: var(--starlight-white);
            font-size: 0.9em;
        }

        button {
            width: 100%;
            padding: 0.8rem;
            margin-top: 1rem;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
            background: var(--hologram-cyan);
            border: 1px solid var(--hologram-cyan);
            color: var(--deep-space-blue);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.3);
        }

        @media screen and (max-width: 768px) {
            .dashboard-container {
                width: 85%;
                padding: 2rem;
            }

            h2 {
                font-size: 1.5em;
            }

            .dashboard-option {
                margin: 0.8rem 0;
                padding: 0.8rem;
            }

            button {
                padding: 0.7rem;
            }
        }

        @media screen and (max-width: 480px) {
            .dashboard-container {
                width: 80%;
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.2em;
                margin-bottom: 1.5rem;
            }

            .dashboard-option h3 {
                font-size: 1em;
            }

            .dashboard-option p {
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    <div class="dashboard-container">
        <h2>Mission Control Dashboard</h2>
        
        <a href="add-body.php" style="text-decoration: none; color: inherit;">
            <div class="dashboard-option">
                <h3>Add Body</h3>
                <p>Add new celestial bodies to the database</p>
            </div>
        </a>

        <a href="delete-body.php" style="text-decoration: none; color: inherit;">
            <div class="dashboard-option">
                <h3>Delete Body</h3>
                <p>Remove existing celestial bodies</p>
            </div>
        </a>

        <form method="POST" class="logout-form">
            <button type="button" onclick="history.back()">Terminate Session</button>
        </form>
    </div>
</body>
</html>