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

$success_message = '';
$error_message = '';

// Handle deletion
if (isset($_POST['delete']) && isset($_POST['body_id']) && isset($_POST['category'])) {
    $body_id = $conn->real_escape_string($_POST['body_id']);
    $category = $conn->real_escape_string($_POST['category']);
    $table = $category . "s"; // planets, moons, or stars

    // Get image path before deletion
    $sql = "SELECT image_path FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $body_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image_path = $row['image_path'];
        
        // Delete from database
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $body_id);
        
        if ($stmt->execute()) {
            // Delete image file
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            $success_message = "Celestial body deleted successfully!";
        } else {
            $error_message = "Error deleting celestial body: " . $conn->error;
        }
    }
}

// Get search and filter parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : 'all';

// Prepare base queries for each table
$queries = [];
if ($filter === 'all' || $filter === 'planet') {
    $queries[] = "SELECT *, 'planet' as category FROM planets" . ($search ? " WHERE name LIKE '%$search%'" : "");
}
if ($filter === 'all' || $filter === 'moon') {
    $queries[] = "SELECT *, 'moon' as category FROM moons" . ($search ? " WHERE name LIKE '%$search%'" : "");
}
if ($filter === 'all' || $filter === 'star') {
    $queries[] = "SELECT *, 'star' as category FROM stars" . ($search ? " WHERE name LIKE '%$search%'" : "");
}

// Combine queries
$sql = implode(" UNION ", $queries) . " ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Celestial Body - Cosmic Horizons</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
            --glow-pink: #FF00FF;
            --cosmic-black: #000000;
            --danger-red: #FF4444;
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
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(0,255,212,0.5);
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .search-filter input,
        .search-filter select {
            padding: 0.75rem;
            border: 2px solid var(--hologram-cyan);
            background: rgba(11, 23, 70, 0.8);
            color: var(--starlight-white);
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .search-filter input:focus,
        .search-filter select:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(0,255,212,0.3);
            border-color: var(--hologram-cyan);
        }

        .search-filter input {
            flex: 1;
            min-width: 200px;
        }

        .search-filter select {
            width: 150px;
            cursor: pointer;
        }

        .search-filter select option {
            background: var(--deep-space-blue);
            color: var(--starlight-white);
        }

        .search-filter button {
            padding: 0.75rem 1.5rem;
            background: transparent;
            color: var(--hologram-cyan);
            border: 2px solid var(--hologram-cyan);
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .search-filter button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--hologram-cyan);
            transition: all 0.3s ease;
            z-index: -1;
        }

        .search-filter button:hover {
            color: var(--deep-space-blue);
        }

        .search-filter button:hover::before {
            left: 0;
        }

        .bodies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .body-card {
            background: rgba(11, 23, 70, 0.8);
            border: 2px solid var(--hologram-cyan);
            padding: 1.5rem;
            border-radius: 8px;
            position: relative;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .body-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,255,212,0.2);
        }

        .body-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 2px solid var(--hologram-cyan);
            transition: all 0.3s ease;
        }

        .body-card:hover img {
            transform: scale(1.02);
        }

        .body-info h3 {
            color: var(--hologram-cyan);
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 0.5rem;
        }

        .body-info p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .delete-btn {
            background: transparent;
            color: var(--danger-red);
            border: 2px solid var(--danger-red);
            padding: 0.5rem 1rem;
            font-family: 'Share Tech Mono', monospace;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .delete-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--danger-red);
            transition: all 0.3s ease;
            z-index: -1;
        }

        .delete-btn:hover {
            color: var(--starlight-white);
        }

        .delete-btn:hover::before {
            left: 0;
        }

        .category-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.25rem 0.75rem;
            background: var(--hologram-cyan);
            color: var(--deep-space-blue);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 0 10px rgba(0,255,212,0.3);
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
            font-family: 'Share Tech Mono', monospace;
            letter-spacing: 1px;
            animation: fadeIn 0.3s ease;
        }

        .success {
            background: rgba(0, 255, 212, 0.1);
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
        }

        .error {
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--danger-red);
            color: var(--danger-red);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-link {
            display: inline-block;
            color: var(--hologram-cyan);
            text-decoration: none;
            font-family: 'Share Tech Mono', monospace;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            text-shadow: 0 0 10px rgba(0,255,212,0.5);
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .search-filter {
                flex-direction: column;
            }

            .search-filter input,
            .search-filter select,
            .search-filter button {
                width: 100%;
            }

            .bodies-grid {
                grid-template-columns: 1fr;
            }

            .body-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Delete Celestial Body</h1>
            <a href="admin-dashboard.php" class="back-link">← Back to Dashboard</a>
        </div>

        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="GET" class="search-filter">
            <input type="text" name="search" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="filter">
                <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Bodies</option>
                <option value="planet" <?php echo $filter === 'planet' ? 'selected' : ''; ?>>Planets</option>
                <option value="moon" <?php echo $filter === 'moon' ? 'selected' : ''; ?>>Moons</option>
                <option value="star" <?php echo $filter === 'star' ? 'selected' : ''; ?>>Stars</option>
            </select>
            <button type="submit">Search</button>
        </form>

        <div class="bodies-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="body-card">
                        <span class="category-badge"><?php echo ucfirst($row['category']); ?></span>
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <div class="body-info">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p><strong>Temperature:</strong> <?php echo htmlspecialchars($row['temperature']); ?></p>
                            <p><strong>Age:</strong> <?php echo htmlspecialchars($row['age']); ?></p>
                            <p><strong>Distance:</strong> <?php echo htmlspecialchars($row['distance']); ?></p>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this celestial body? This action cannot be undone.');">
                                <input type="hidden" name="body_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="category" value="<?php echo $row['category']; ?>">
                                <button type="submit" name="delete" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: var(--hologram-cyan);">No celestial bodies found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Add fade-in animation for success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => message.remove(), 500);
                }, 3000);
            });
        });
    </script>
</body>
</html> 