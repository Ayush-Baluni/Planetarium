<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($email === 'scbaluni63094@gmail.com' && $password === '1234gold@8') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin-dashboard.php");
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
            --neon-blue: #00f3ff;
            --deep-space: #090b15;
            --hologram: rgba(0, 243, 255, 0.1);
            --grid-color: rgba(0, 243, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--deep-space);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Share Tech Mono', monospace;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        /* Grid Background Animation */
        .grid {
            position: absolute;
            width: 200%;
            height: 200%;
            background-image: 
                linear-gradient(var(--grid-color) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
            background-size: 50px 50px;
            transform: perspective(300px) rotateX(45deg);
            animation: grid-move 20s linear infinite;
            z-index: 1;
        }

        @keyframes grid-move {
            0% {
                transform: perspective(300px) rotateX(45deg) translateY(0);
            }
            100% {
                transform: perspective(300px) rotateX(45deg) translateY(-50px);
            }
        }

        .login-container {
            background: rgba(9, 11, 21, 0.9);
            padding: 3rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
            border: 1px solid var(--neon-blue);
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.2),
                        inset 0 0 20px rgba(0, 243, 255, 0.1);
            animation: container-glow 2s ease-in-out infinite alternate;
        }

        @keyframes container-glow {
            from {
                box-shadow: 0 0 20px rgba(0, 243, 255, 0.2),
                            inset 0 0 20px rgba(0, 243, 255, 0.1);
            }
            to {
                box-shadow: 0 0 25px rgba(0, 243, 255, 0.3),
                            inset 0 0 25px rgba(0, 243, 255, 0.2);
            }
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            color: var(--neon-blue);
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: 2px;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--neon-blue), transparent);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--neon-blue);
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        input {
            width: 100%;
            padding: 0.8rem;
            background: rgba(0, 243, 255, 0.05);
            border: 1px solid var(--neon-blue);
            color: #fff;
            font-family: 'Share Tech Mono', monospace;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);
            background: rgba(0, 243, 255, 0.1);
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
            background: transparent;
            border: 1px solid var(--neon-blue);
            color: var(--neon-blue);
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(0, 243, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button[type="submit"] {
            background: var(--neon-blue);
            color: var(--deep-space);
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
    </style>
</head>
<body>
    <div class="grid"></div>
    <div class="login-container">
        <h2>MISSION CONTROL ACCESS</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">ADMINISTRATOR ID</label>
                <input type="email" id="email" name="email" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">ACCESS CODE</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="button-group">
                <a href="index.php"><button type="button">ABORT</button></a>
                <button type="submit">AUTHENTICATE</button>
            </div>
        </form>
    </div>
</body>
</html>