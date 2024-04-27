<?php
require('../db_functions.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
  header('Location: login.php');
  exit();
}

// Fetch sessions to display
$sessions = getSessions($_SESSION['userID']);


if (isset($_POST['delete_session'])) {
    $sessionID = $_POST['sessionID']; // Ensure correct parameter name
    deleteSession($sessionID, $_SESSION['userID']); // Pass both sessionID and userID
    // Redirect after session deletion
    header('Location: viewWorkoutSessions.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <title>View Workout Sessions - XSplit</title>
  <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .list-group {
            list-style-type: none;
            padding: 0;
        }
        .list-group-item {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative; /* Add position relative */
        }
        .list-group-item a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .list-group-item a:hover {
            color: #666;
        }
        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>

</head>
<body>

<div class="container mt-4">
    <div class="text-center mb-4">
      <a href="index.php" class="btn btn-secondary">Home</a>
    </div>
  <h2>Your Workout Sessions</h2>
  <ul class="list-group">
    <?php foreach ($sessions as $session): ?>
      <li class="list-group-item">
        <form method="POST">
          <button type="submit" name="delete_session" class="btn btn-sm btn-danger delete-button">Delete</button>
          <a href="addFeedback.php" class="btn btn-sm btn-primary" style="color: white; background-color: blue;">Add Feedback</a>
          <a href="viewFeedback.php" class="btn btn-sm btn-primary" style="color: white; background-color: green;">View Feedback</a>
          <input type="hidden" name="sessionID" value="<?= $session['sessionID'] ?>">
        </form>
        <a href="sessionDetails.php?sessionID=<?= htmlspecialchars($session['sessionID']) ?>">
          Session on <?= htmlspecialchars($session['Date']) ?> - Duration: <?= htmlspecialchars($session['Duration']) ?> mins
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
<!-- Include your footer here -->

</body>
</html>
