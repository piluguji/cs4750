<?php 
require('../db_functions.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Get the sessionID from the URL
$sessionID = $_GET['sessionID'] ?? null;



// Fetch feedback for the specific session
$feedbacks = getFeedback($sessionID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Feedback - XSplit</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
  <h2>Feedback</h2>
  <?php if ($feedbacks): ?>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Satisfaction</th>
          <th scope="col">Difficulty</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($feedbacks as $index => $feedback): ?>
          <tr>
            <th scope="row"><?= $index + 1 ?></th>
            <td><?= htmlspecialchars($feedback['satisfaction']) ?></td>
            <td><?= htmlspecialchars($feedback['difficulty']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No feedback found for this session.</p>
  <?php endif; ?>
  <a href="viewWorkoutSessions.php" class="btn btn-secondary">Back to Sessions</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
