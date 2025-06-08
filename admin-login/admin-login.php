<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($email === 'scbaluni63094@gmail.com' && $password === '1234gold@8') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard/admin-dashboard.php");
        exit();
    } else {
        $error = "Access Denied: Invalid Credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Control Access - Cosmic Horizons</title>
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

        .login-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(11, 23, 70, 0.6);
            padding: 3rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--hologram-cyan);
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            padding: 0.8rem;
            background: rgba(0, 255, 212, 0.05);
            border: 1px solid var(--hologram-cyan);
            border-radius: 4px;
            color: var(--hologram-cyan);
            font-family: 'Share Tech Mono', monospace;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 255, 212, 0.1);
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.3);
            background: rgba(0, 255, 212, 0.1);
            border-color: var(--hologram-cyan);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        button {
            flex: 1;
            padding: 0.8rem;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
        }

        button[type="button"] {
            background: transparent;
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
        }

        button[type="submit"] {
            background: var(--hologram-cyan);
            border: 1px solid var(--hologram-cyan);
            color: var(--deep-space-blue);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.3);
        }

        .error {
            background: rgba(255, 0, 0, 0.1);
            border-left: 3px solid #ff0000;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            animation: errorShake 0.5s ease-in-out;
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        @media screen and (max-width: 768px) {
            .login-container {
                width: 85%;
                padding: 2rem;
            }

            h2 {
                font-size: 1.5em;
            }

            .button-group {
                flex-direction: column;
            }

            button {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        @media screen and (max-width: 480px) {
            .login-container {
                width: 80%;
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.2em;
            }

            input {
                padding: 0.6rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    <div class="login-container">
        <h2>Mission Control Access</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Administrator ID</label>
                <input type="email" id="email" name="email" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Access Code</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="button-group">
                <button type="button" onclick="history.back()">Abort</button>
                <button type="submit">Authenticate</button>
            </div>
        </form>
    </div>
</body>
</html>