<?php 
// Start the session at the beginning of the script
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to XSplit</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 text-center">
  <h1>Welcome to XSplit<?php if(isset($_SESSION['username'])) { echo ", " . $_SESSION['username']; } ?></h1>
  <p>Your ultimate workout tracker</p>
  <div class="row mt-4">
    <div class="col-12">
      <?php if(isset($_SESSION['userID'])): ?>
        <!-- User is logged in, show Sign out and Add Exercise -->
        <a href="favorites.php" class="btn btn-info btn-lg mr-2">View Favorite Exercises</a>
        <a href="addWorkoutSession.php" class="btn btn-info btn-lg mr-2">Add Workout Session</a>
        <a href="addNutrition.php" class="btn btn-info btn-lg mr-2">Add Nutrition</a>
        <a href="viewNutrition.php" class="btn btn-info btn-lg mr-2">View Nutrition</a>
        <a href="viewWorkoutSessions.php" class="btn btn-info btn-lg mr-2">View Workout Sessions</a>
        <a href="viewWorkoutSessions.php" class="btn btn-info btn-lg mr-2">Add Feedback</a>
        <a href="viewWorkoutSessions.php" class="btn btn-info btn-lg mr-2">View Feedback</a>
        <a href="logout.php" class="btn btn-danger btn-lg">Sign Out</a>
      <?php else: ?>
        <!-- No user is logged in, show Login and Sign Up -->
        <a href="login.php" class="btn btn-primary btn-lg mr-2">Login</a>
        <a href="signup.php" class="btn btn-success btn-lg">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>