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
    <title>Add Celestial Body - Cosmic Horizons Planetarium</title>
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
            padding: 20px;
            background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
            min-height: 100vh;
            font-family: 'Orbitron', sans-serif;
            color: var(--starlight-white);
        }

        .form-container {
            background: rgba(11, 23, 70, 0.8);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.3);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--hologram-cyan);
        }

        input, select, textarea {
            width: 100%;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--hologram-cyan);
            color: var(--starlight-white);
            border-radius: 4px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            background: var(--hologram-cyan);
            color: var(--deep-space-blue);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            margin-right: 10px;
        }

        button:hover {
            opacity: 0.9;
        }

        .back-btn {
            background: transparent;
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
        }

        .success {
            color: #4CAF50;
            margin-bottom: 1rem;
        }

        .error {
            color: #ff4444;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2 style="text-align: center; color: var(--hologram-cyan);">Add Celestial Body</h2>
    
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

        <div class="form-group" style="display: flex; gap: 10px;">
            <button type="button" class="back-btn" onclick="window.location.href='admin-dashboard.php'">Back to Dashboard</button>
            <button type="submit">Add Body</button>
        </div>
    </form>
</div>
</body>
</html>