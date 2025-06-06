<?php
require_once 'counter.php';
$counter = new VisitorCounter('home');
$counter->trackVisitor();
$total_visits = $counter->getTotalVisits();
$today_visits = $counter->getTodayVisits();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Planetarium</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="events.php">Events</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li class="title-container">
            <span class="title">Planetarium</span>
        </li>
        <li><a href="about.php">About</a></li>
        <li><a href="admin-login.php">Admin Login</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="planet-container">
      <div class="planet-content">
        <h1 class="planet-name">Earth</h1>
        <div class="planet-details">
          <div class="planet-detail">
            <span class="detail-label">Age</span>
            <span class="detail-value">4.54 Billion Years</span>
          </div>
          <div class="planet-detail">
            <span class="detail-label">Temp</span>
            <span class="detail-value">15°C (Average)</span>
          </div>
        </div>
        <div class="arrow"></div>
        <div class="planet-image">
          <img src="earth.png" alt="Earth">
        </div>
      </div>
      <button class="nav-arrow left-arrow" onclick="rotatePlanet(-1)" aria-label="Previous planet">&#10094;</button>
      <button class="nav-arrow right-arrow" onclick="rotatePlanet(1)" aria-label="Next planet">&#10095;</button>
    </div>
  </main>

  <script src="app.js"></script>
</body>
</html>