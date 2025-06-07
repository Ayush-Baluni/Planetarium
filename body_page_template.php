<?php
require_once 'counter.php';

// Get body ID from URL parameter
$body_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = isset($_GET['category']) ? $_GET['category'] : 'planet';

// Database connection
$conn = new mysqli("localhost", "root", "", "planetarium_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get body data
$table = $category . "s";
$sql = "SELECT * FROM $table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $body_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: categories.php");
    exit();
}

$body = $result->fetch_assoc();
$counter = new VisitorCounter('body_' . $body['name']);
$counter->trackVisitor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($body['name']); ?> - Cosmic Horizons</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
            --glow-pink: #FF00FF;
            --cosmic-black: #000000;
            --star-gold: #FFD700;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            min-height: 100vh;
            font-family: 'Roboto Mono', monospace;
            background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
            color: var(--starlight-white);
            overflow-x: hidden;
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

        .nav-container {
            position: sticky;
            top: 0;
            background: rgba(11, 23, 70, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--hologram-cyan);
            z-index: 100;
            padding: 1rem 0;
        }

        .nav-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-back {
            color: var(--hologram-cyan);
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            padding: 0.5rem 1rem;
            border: 1px solid var(--hologram-cyan);
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-back:hover {
            background: var(--hologram-cyan);
            color: var(--deep-space-blue);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .body-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .body-title {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            color: var(--hologram-cyan);
            text-shadow: 
                0 0 20px rgba(0,255,212,0.7),
                0 0 40px rgba(0,255,212,0.5);
            margin-bottom: 1rem;
            animation: pulse 4s infinite alternate;
        }

        .body-subtitle {
            font-size: 1.2rem;
            color: var(--starlight-white);
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .body-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .body-image-container {
            position: relative;
            width: 100%;
        }

        .body-image {
            width: 100%;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.3s ease;
            aspect-ratio: 1;
        }

        .body-image:hover {
            transform: scale(1.02);
        }

        .body-info {
            background: rgba(11, 23, 70, 0.6);
            padding: 1.2rem;
            border-radius: 15px;
            border: 1px solid var(--hologram-cyan);
            backdrop-filter: blur(10px);
            box-shadow: 0 0 30px rgba(0,255,212,0.1);
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .info-section {
            margin-bottom: 0.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .info-title {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            flex-shrink: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 5%;
            margin: 2%;
            flex-grow: 1;
            height: 100%;
        }

        .info-item {
            background: rgba(0,255,212,0.1);
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid rgba(0,255,212,0.2);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100%;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,255,212,0.2);
        }

        .info-label {
            display: block;
            color: var(--hologram-cyan);
            font-size: 0.75rem;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            display: block;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .description-section {
            grid-column: 1 / -1;
            background: rgba(0,0,0,0.3);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(0,255,212,0.2);
            margin-top: 2rem;
        }

        .description-text {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            max-width: 100%;
        }

        @keyframes pulse {
            from { text-shadow: 0 0 20px rgba(0,255,212,0.7), 0 0 40px rgba(0,255,212,0.5); }
            to { text-shadow: 0 0 30px rgba(0,255,212,0.9), 0 0 60px rgba(0,255,212,0.7); }
        }

        /* Responsive Font Sizing */
        @media screen and (min-width: 1200px) {
            .info-title {
                font-size: 1.4rem;
            }
            .info-label {
                font-size: 0.9rem;
            }
            .info-value {
                font-size: 1.1rem;
            }
        }

        @media screen and (min-width: 1600px) {
            .info-title {
                font-size: 1.6rem;
            }
            .info-label {
                font-size: 1rem;
            }
            .info-value {
                font-size: 1.3rem;
            }
        }

        @media screen and (max-width: 900px) {
            .info-label {
                font-size: 0.7rem;
            }
            .info-value {
                font-size: 0.85rem;
            }
        }

        /* Responsive Design */
        @media screen and (max-width: 640px) {
            .body-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .body-info {
                aspect-ratio: unset;
                display: block;
                width: 90%;
                margin: auto;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                flex-grow: unset;
            }

            .info-item {
                min-height: unset;
                display: block;
                padding: 0.8rem;
            }

            .info-title {
                font-size: 1.5rem;
                margin-bottom: 1rem;
                flex-shrink: unset;
            }

            .container {
                padding: 2rem 1rem;
            }

            .nav-content {
                padding: 0 1rem;
            }

            .body-image{
                width: 90%;
                height: auto;
                display: block;
                margin: auto;
            }
        }

        @media screen and (max-width: 480px) {
            .body-title {
                font-size: 2rem;
            }

            .body-info {
                padding: 1.5rem;
            }
        }

        
        @media screen and (min-width: 960px) {
            .info-label{
                font-size: 1.4rem;
            }

            .info-value{
                font-size: 1.2rem;
            }
        }

        @media screen and (min-width: 900px) and (max-width: 960px) {
            .info-label{
                font-size: 1.3rem;
            }

            .info-value{
                font-size: 1.1rem;
            }
        }

        @media screen and (min-width: 860px) and (max-width: 900px) {
            .info-label{
                font-size: 1.1rem;
            }

            .info-value{
                font-size: 1rem;
            }
        }

        @media screen and (min-width: 720px) and (max-width: 860px) {
            .info-label{
                font-size: 1rem;
            }

            .info-value{
                font-size: 0.9rem;
            }
        }

        @media screen and (min-width: 640px) and (max-width: 720px) {
            .info-label{
                font-size: 0.8rem;
            }

            .info-value{
                font-size: 0.7rem;
            }
        }


    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    
    <nav class="nav-container">
        <div class="nav-content">
            <a href="#" onclick="history.back(); return false;" class="nav-back">← Back to <?php echo ucfirst($category); ?>s</a>
        </div>
    </nav>

    <div class="container">
        <div class="body-header">
            <h1 class="body-title"><?php echo htmlspecialchars($body['name']); ?></h1>
            <p class="body-subtitle"><?php echo ucfirst($category); ?></p>
        </div>

        <div class="body-content">
            <div class="body-image-container">
                <img src="<?php echo htmlspecialchars($body['image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($body['name']); ?>" 
                     class="body-image">
            </div>

            <div class="body-info">
                <div class="info-section">
                    <h2 class="info-title">Properties</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Temperature</span>
                            <span class="info-value"><?php echo htmlspecialchars($body['temperature']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Age</span>
                            <span class="info-value"><?php echo htmlspecialchars($body['age']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Distance</span>
                            <span class="info-value"><?php echo htmlspecialchars($body['distance']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Type</span>
                            <span class="info-value"><?php echo ucfirst($category); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="description-section">
            <h2 class="info-title">About <?php echo htmlspecialchars($body['name']); ?></h2>
            <p class="description-text">
                <?php echo nl2br(htmlspecialchars($body['description'])); ?>
            </p>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const infoItems = document.querySelectorAll('.info-item');
            
            infoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.background = 'rgba(0,255,212,0.2)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.background = 'rgba(0,255,212,0.1)';
                });
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?> 