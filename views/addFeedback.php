<?php 
require('../db_functions.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
  header('Location: login.php');
  exit();
}

$sessionID = $_GET['sessionID']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Feedback - XSplit</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }
    .errors {
      background-color: #f8d7da;
      color: #721c24;
      border-color: #f5c6cb;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="container mt-4">
    <div class="form-container">
      <h3 class="text-center mb-4">Add Feedback</h3>
      <?php
      $errors = [];
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && $sessionID) {
          $rating = $_POST['rating'] ?? 0;
          $comments = $_POST['comments'] ?? '';

          if ($rating < 1 || $rating > 10) {
              $errors[] = 'Rating must be between 1 and 10.';
          }
          if (empty($comments)) {
              $errors[] = 'Comments field is required.';
          }

          if (empty($errors) && isset($sessionID)) {
            // Add feedback to the database


            if (addFeedback($sessionID, $rating, $comments)) {
                // Redirect on successful insertion
                header('Location: viewWorkoutSessions.php');
                exit();
            } else {
                // Handle insertion error
                $errors[] = 'Failed to add feedback. Please try again.';
            }
        } elseif (!isset($sessionID)) {
            $errors[] = 'Session ID is required.';
        }
      }
      ?>

      <?php if (!empty($errors)): ?>
          <div class="errors">
              <?php foreach ($errors as $error): ?>
                  <p><?php echo htmlspecialchars($error); ?></p>
              <?php endforeach; ?>
          </div>
      <?php endif; ?>

      <form method="POST" action="addFeedback.php?sessionID=<?= $sessionID ?>">
          <div class="form-group">
              <label for="rating">Rating (1-10):</label>
              <input type="number" class="form-control" name="rating" id="rating" min="1" max="10" required>
          </div>
          <div class="form-group">
              <label for="comments">Comments:</label>
              <textarea class="form-control" name="comments" id="comments" required></textarea>
          </div>
          <div class="text-center">
              <button type="submit" class="btn btn-success">Submit Feedback</button>
              <a href="viewWorkoutSessions.php" class="btn btn-info" role="button">Back</a>


          </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
