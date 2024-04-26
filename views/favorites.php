<?php 
require('../db_functions.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
  header('Location: login.php');
  exit();
}

// Fetch the user's favorite exercises
$favorites = fetch_favorites($_SESSION['userID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Favorites - XSplit</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="text-center mb-4">
      <a href="index.php" class="btn btn-secondary">Home</a>
    </div>
    <h3 class="text-center mb-4">Your Favorite Exercises</h3>
    <ul class="list-group">
        <?php foreach ($favorites as $favorite): ?>
            <li class="list-group-item"><?= htmlspecialchars($favorite['name']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>