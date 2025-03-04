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
    <title>Admin Dashboard - Cosmic Horizons Planetarium</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Orbitron', sans-serif;
            color: var(--starlight-white);
        }

        .dashboard-container {
            background: rgba(11, 23, 70, 0.8);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.3);
            width: 100%;
            max-width: 500px;
        }

        .dashboard-option {
            background: rgba(0, 255, 212, 0.1);
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dashboard-option:hover {
            background: rgba(0, 255, 212, 0.2);
            transform: translateX(10px);
        }

        .logout-form {
            margin-top: 2rem;
        }

        button {
            background: var(--hologram-cyan);
            color: var(--deep-space-blue);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            width: 100%;
            margin-top: 0.5rem;
        }

        button:hover {
            opacity: 0.9;
        }

        h2 {
            color: var(--hologram-cyan);
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        
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
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</body>
</html>