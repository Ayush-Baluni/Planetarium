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
    <title>Edit Celestial Body - Cosmic Horizons</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
            --glow-pink: #FF00FF;
            --cosmic-black: #000000;
            --edit-green: #00FF7F;
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
            position: relative;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 100;
        }

        .form-container {
            background: rgba(11, 23, 70, 0.6);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid var(--hologram-cyan);
            box-shadow: 0 0 20px rgba(0, 255, 212, 0.2);
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2em;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(0, 255, 212, 0.5),
                         0 0 20px rgba(0, 255, 212, 0.3);
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .search-filter input,
        .search-filter select {
            padding: 0.8rem;
            background: rgba(0, 255, 212, 0.05);
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
            font-family: 'Share Tech Mono', monospace;
            border-radius: 4px;
        }

        .search-filter select {
            width: 150px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2300FFD4' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 35px;
        }

        .search-filter select option {
            background: var(--deep-space-blue);
            color: var(--hologram-cyan);
            padding: 0.8rem;
        }

        .search-filter input {
            flex: 1;
        }

        .search-filter button {
            padding: 0.8rem 1.5rem;
            background: var(--hologram-cyan);
            border: none;
            color: var(--deep-space-blue);
            font-family: 'Orbitron', sans-serif;
            cursor: pointer;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-filter button:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.5);
        }

        .celestial-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .celestial-item {
            background: rgba(11, 23, 70, 0.8);
            padding: 1rem;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(0, 255, 212, 0.2);
        }

        .celestial-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .celestial-name {
            color: var(--hologram-cyan);
            font-size: 1.2em;
            font-family: 'Orbitron', sans-serif;
        }

        .celestial-type {
            color: var(--starlight-white);
            opacity: 0.8;
        }

        .edit-btn {
            background: transparent;
            color: var(--edit-green);
            border: 1px solid var(--edit-green);
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .edit-btn:hover {
            background: rgba(0, 255, 127, 0.1);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .control-btn {
            flex: 1;
            padding: 0.8rem;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px;
            background: transparent;
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 15px rgba(0, 255, 212, 0.3);
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

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid #00ff00;
            color: #00ff00;
        }

        .error {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff0000;
            color: #ff0000;
        }
    </style>
    <script>
        // Function to update content without page reload
        function updateContent(formData) {
            const url = 'edit-body.php?' + new URLSearchParams(formData).toString();
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    document.querySelector('.celestial-list').innerHTML = doc.querySelector('.celestial-list').innerHTML;
                    // Don't modify browser history - this was causing the loop issue
                });
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.search-filter');
            
            // Handle select change
            form.querySelector('select[name="filter"]').addEventListener('change', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                updateContent(formData);
            });

            // Handle form submit (search button)
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                updateContent(formData);
            });
        });
    </script>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="form-container">
            <h2>Edit Celestial Body</h2>

            <?php if ($success_message): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="GET" class="search-filter">
                <input type="text" name="search" placeholder="Search by name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select name="filter">
                    <option value="all" <?php echo (!isset($_GET['filter']) || $_GET['filter'] === 'all') ? 'selected' : ''; ?>>All Bodies</option>
                    <option value="planet" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'planet') ? 'selected' : ''; ?>>Planets</option>
                    <option value="moon" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'moon') ? 'selected' : ''; ?>>Moons</option>
                    <option value="star" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'star') ? 'selected' : ''; ?>>Stars</option>
                </select>
                <button type="submit">Search</button>
            </form>

            <div class="celestial-list">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="celestial-item">
                            <div class="celestial-info">
                                <span class="celestial-name"><?php echo htmlspecialchars($row['name']); ?></span>
                                <span class="celestial-type">Type: <?php echo ucfirst($row['category']); ?></span>
                            </div>
                            <a href="edit-form.php?id=<?php echo $row['id']; ?>&category=<?php echo $row['category']; ?>" class="edit-btn">Edit</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: var(--hologram-cyan);">No celestial bodies found.</p>
                <?php endif; ?>
            </div>

            <div class="button-group">
                <button class="control-btn" onclick="history.back()">Back</button>
            </div>
        </div>
    </div>
</body>
</html>