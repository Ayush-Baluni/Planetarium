<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "planetarium_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $temperature = $conn->real_escape_string($_POST['temperature']);
    $age = $conn->real_escape_string($_POST['age']);
    $description = $conn->real_escape_string($_POST['description']);
    $distance = $conn->real_escape_string($_POST['distance']);
    $category = $conn->real_escape_string($_POST['category']);

    // Handle file upload
    $target_dir = "uploads/";
    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $new_filename = $name . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into appropriate table based on category
        $table = $category . "s"; // planets, moons, or stars
        $sql = "INSERT INTO $table (name, image_path, temperature, age, description, distance) 
                VALUES ('$name', '$target_file', '$temperature', '$age', '$description', '$distance')";

        if ($conn->query($sql) === TRUE) {
            $success = "New celestial body added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Celestial Body - Cosmic Horizons</title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
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
            z-index: 1;
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

        .form-container {
            background: rgba(11, 23, 70, 0.6);
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            margin: 2rem auto;
            backdrop-filter: blur(10px);
            border: 1px solid var(--hologram-cyan);
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.2);
            position: relative;
            z-index: 2;
        }

        .form-group {
            margin-bottom: 1.5rem;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 0.8rem;
            color: var(--hologram-cyan);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
            font-family: 'Orbitron', sans-serif;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.8rem;
            background: rgba(0, 255, 212, 0.05);
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        select {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2300FFD4' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            background-size: 1.2em;
            padding-right: 2.5rem;
        }

        select option {
            background: var(--deep-space-blue);
            color: var(--hologram-cyan);
            padding: 0.8rem;
        }

        input[type="file"] {
            padding: 0.5rem;
            background: transparent;
            border: none;
            color: var(--hologram-cyan);
        }

        input[type="file"]::-webkit-file-upload-button {
            background: transparent;
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            cursor: pointer;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background: var(--hologram-cyan);
            color: var(--deep-space-blue);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.3);
            background: rgba(0, 255, 212, 0.1);
        }

        textarea {
            height: 100px;
            resize: vertical;
            min-height: 60px;
            max-height: 200px;
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

        .success, .error {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            color: var(--hologram-cyan);
            background: rgba(0, 255, 212, 0.1);
            border: 1px solid var(--hologram-cyan);
        }

        .error {
            color: #ff4444;
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff4444;
        }

        @media screen and (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .form-container {
                margin: 1rem auto;
                padding: 1.5rem;
                width: 100%;
            }

            h2 {
                font-size: 1.5em;
                margin-bottom: 1.5rem;
            }

            .button-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            textarea {
                height: 80px;
            }
        }

        @media screen and (max-width: 480px) {
            body {
                padding: 0.5rem;
            }

            .form-container {
                padding: 1rem;
                margin: 0.5rem auto;
            }

            h2 {
                font-size: 1.2em;
                margin-bottom: 1rem;
            }

            label {
                font-size: 0.8rem;
                margin-bottom: 0.4rem;
            }

            input, select, textarea {
                padding: 0.6rem;
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 0.8rem;
            }

            input[type="file"] {
                font-size: 0.8rem;
            }

            input[type="file"]::-webkit-file-upload-button {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            button {
                padding: 0.6rem;
                font-size: 0.9rem;
            }

            textarea {
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    <div class="form-container">
        <h2>Add Celestial Body</h2>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="planet">Planet</option>
                    <option value="moon">Moon</option>
                    <option value="star">Star</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="image">Image (PNG only)</label>
                <input type="file" id="image" name="image" accept=".png" required>
            </div>

            <div class="form-group">
                <label for="temperature">Temperature</label>
                <input type="text" id="temperature" name="temperature" required>
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="text" id="age" name="age" required>
            </div>

            <div class="form-group">
                <label for="distance">Distance from Earth</label>
                <input type="text" id="distance" name="distance" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="button-group">
                <button type="button" onclick="history.back()">Return to Control</button>
                <button type="submit">Add Body</button>
            </div>
        </form>
    </div>
</body>
</html>