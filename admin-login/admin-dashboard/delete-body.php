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

    // Get body info before deletion
    $sql = "SELECT name, image_path FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $body_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image_path = $row['image_path'];
        $body_name = $row['name'];
        
        // Delete from database
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $body_id);
        
        if ($stmt->execute()) {
            // Delete image file (adjust path relative to admin-dashboard location)
            $full_image_path = "../../" . $image_path;
            if (file_exists($full_image_path)) {
                unlink($full_image_path);
            }
            
            // Delete individual page file from correct category directory
            $safe_name = preg_replace('/[^a-zA-Z0-9\-_]/', '', str_replace(' ', '-', $body_name));
            $page_filename = $safe_name . "-" . $body_id . ".php";
            $category_dir = "../../categories/" . $category . "s/";
            $full_page_path = $category_dir . $page_filename;
            
            if (file_exists($full_page_path)) {
                unlink($full_page_path);
            }
            
            $success_message = "Celestial body deleted successfully! Files cleaned up: image and page file removed.";
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

        .delete-btn {
            background: transparent;
            color: var(--danger-red);
            border: 1px solid var(--danger-red);
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background: rgba(255, 68, 68, 0.1);
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
            background: rgba(0, 255, 212, 0.1);
            border: 1px solid var(--hologram-cyan);
            color: var(--hologram-cyan);
        }

        .error {
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--danger-red);
            color: var(--danger-red);
        }

        @media (max-width: 540px) {
            .container {
                padding: 1rem;
                max-width: 95%;
            }

            .form-container {
                padding: 1.5rem;
                margin: 1rem auto;
            }

            h2 {
                font-size: 1.8em;
                margin-bottom: 1.5rem;
            }

            .search-filter {
                flex-direction: column;
                gap: 0.8rem;
            }

            .search-filter input,
            .search-filter select,
            .search-filter button {
                width: 100%;
            }

            .search-filter select {
                width: 100%;
                background-position: right 8px center;
            }

            .celestial-item {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }

            .celestial-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.3rem;
                width: 100%;
            }

            .celestial-name {
                font-size: 1.1em;
            }

            .delete-btn {
                align-self: flex-end;
                width: auto;
                min-width: 80px;
            }

            .button-group {
                flex-direction: column;
                gap: 0.8rem;
            }

            .control-btn {
                width: 100%;
                padding: 0.8rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0.5rem;
            }

            .form-container {
                padding: 1rem;
                border-radius: 8px;
            }

            h2 {
                font-size: 1.7em;
                margin-bottom: 1rem;
                letter-spacing: 1px;
                line-height: 1.2;
                max-width: 300px;
                margin-left: auto;
                margin-right: auto;
            }

            .search-filter {
                gap: 0.6rem;
            }

            .search-filter input,
            .search-filter select,
            .search-filter button {
                padding: 0.7rem;
                font-size: 0.9rem;
            }

            .celestial-list {
                gap: 0.8rem;
            }

            .celestial-item {
                padding: 0.8rem;
            }

            .celestial-name {
                font-size: 1rem;
            }

            .celestial-type {
                font-size: 0.85rem;
            }

            .delete-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .control-btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.85rem;
            }

            .message {
                padding: 0.8rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 360px) {
            .form-container {
                padding: 0.8rem;
            }

            h2 {
                font-size: 1.3em;
                margin-bottom: 0.8rem;
            }

            .search-filter input,
            .search-filter select,
            .search-filter button {
                padding: 0.6rem;
                font-size: 0.85rem;
            }

            .celestial-item {
                padding: 0.6rem;
            }

            .celestial-name {
                font-size: 0.95rem;
            }

            .celestial-type {
                font-size: 0.8rem;
            }

            .delete-btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }

            .control-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .search-filter input,
            .search-filter select,
            .search-filter button,
            .delete-btn,
            .control-btn {
                min-height: 44px;
                padding: 0.8rem;
            }

            .celestial-item {
                padding: 1rem;
            }

            .delete-btn:hover,
            .control-btn:hover,
            .search-filter button:hover {
                transform: none;
                box-shadow: none;
            }
        }

        /* Custom Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            animation: modalFadeIn 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-container {
            background: linear-gradient(135deg, rgba(11, 23, 70, 0.95), rgba(74, 44, 93, 0.95));
            border: 2px solid var(--hologram-cyan);
            border-radius: 10px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            box-shadow: 
                0 0 30px rgba(0, 255, 212, 0.3),
                inset 0 0 20px rgba(0, 255, 212, 0.1);
            animation: modalSlideIn 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        @keyframes modalSlideIn {
            from { 
                transform: scale(0.8) translateY(-50px);
                opacity: 0;
            }
            to { 
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(90deg, rgba(0, 255, 212, 0.2), rgba(255, 0, 255, 0.2));
            padding: 1.5rem;
            border-bottom: 1px solid var(--hologram-cyan);
        }

        .modal-header h3 {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            font-size: 1.4rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(0, 255, 212, 0.5);
        }

        .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .modal-body p {
            color: var(--starlight-white);
            margin-bottom: 1.5rem;
            font-family: 'Share Tech Mono', monospace;
            line-height: 1.6;
        }

        .deletion-details {
            background: rgba(0, 255, 212, 0.1);
            border: 1px solid var(--hologram-cyan);
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .body-name {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            font-size: 1.3rem;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(0, 255, 212, 0.3);
        }

        .body-type {
            color: var(--starlight-white);
            font-family: 'Share Tech Mono', monospace;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .warning-text {
            color: var(--danger-red) !important;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .modal-footer {
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(0, 255, 212, 0.3);
        }

        .modal-btn {
            flex: 1;
            padding: 0.8rem 1.5rem;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid;
        }

        .cancel-btn {
            background: transparent;
            border-color: var(--hologram-cyan);
            color: var(--hologram-cyan);
        }

        .cancel-btn:hover {
            background: rgba(0, 255, 212, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 212, 0.2);
        }

        .confirm-btn {
            background: var(--danger-red);
            border-color: var(--danger-red);
            color: white;
        }

        .confirm-btn:hover {
            background: #ff6666;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
        }

        @media (max-width: 480px) {
            .modal-container {
                width: 95%;
                margin: 1rem;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-header h3 {
                font-size: 1.2rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                flex-direction: column;
                padding: 1rem;
            }

            .modal-btn {
                margin-bottom: 0.5rem;
            }
        }

    </style>
    <script>
        // Function to update content without page reload
        function updateContent(formData) {
            const url = 'delete-body.php?' + new URLSearchParams(formData).toString();
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    document.querySelector('.celestial-list').innerHTML = doc.querySelector('.celestial-list').innerHTML;
                    // Update URL without adding to history
                    window.history.replaceState({}, '', url);
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

        // Modal functions
        let currentForm = null;

        function showDeleteConfirmation(event, bodyName, bodyType) {
            event.preventDefault();
            currentForm = event.target.closest('form');
            
            document.getElementById('bodyName').textContent = bodyName;
            document.getElementById('bodyType').textContent = `Type: ${bodyType}`;
            document.getElementById('deleteModal').classList.add('show');
            
            return false;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            currentForm = null;
        }

        function confirmDeletion() {
            if (currentForm) {
                // Create a temporary form element to submit
                const tempForm = document.createElement('form');
                tempForm.method = 'POST';
                tempForm.action = '';
                
                // Copy all hidden inputs from the original form
                const inputs = currentForm.querySelectorAll('input[type="hidden"]');
                inputs.forEach(input => {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = input.name;
                    newInput.value = input.value;
                    tempForm.appendChild(newInput);
                });
                
                // Add the delete button input
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete';
                deleteInput.value = '1';
                tempForm.appendChild(deleteInput);
                
                // Append to body and submit
                document.body.appendChild(tempForm);
                tempForm.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="form-container">
            <h2>Delete Celestial Body</h2>

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
                            <form method="POST" class="delete-form" onsubmit="return showDeleteConfirmation(event, '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo ucfirst($row['category']); ?>');">
                                <input type="hidden" name="body_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="category" value="<?php echo $row['category']; ?>">
                                <button type="submit" name="delete" class="delete-btn">Delete</button>
                            </form>
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

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Deletion Warning</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this body?</p>
                <div class="deletion-details">
                    <span class="body-name" id="bodyName"></span>
                    <span class="body-type" id="bodyType"></span>
                </div>
                <p class="warning-text">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="modal-btn confirm-btn" onclick="confirmDeletion()">Delete</button>
            </div>
        </div>
    </div>
</body>
</html> 