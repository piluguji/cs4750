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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php">XSplit</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <?php if(isset($_SESSION['userID'])): ?>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="favorites.php">Favorite Exercises</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="addWorkoutSession.php">Add Workout Session</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="addNutrition.php">Add Nutrition</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="viewNutrition.php">View Nutrition</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="viewWorkoutSessions.php">View Workout Sessions</a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link btn btn-danger" href="logout.php"><b>Sign Out</b></a>
          </li>
        </ul>
      <?php else: ?>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="signup.php">Sign Up</a>
          </li>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container text-center">
  
  <font size="40">  
    <b><p style="font-family:verdana">Welcome to XSplit<?php if(isset($_SESSION['username'])) { echo ", " . htmlspecialchars($_SESSION['username']); } ?> </p> </b> 
  </font>  
  <!-- <h1 class="mt-4">Welcome to XSplit<?php if(isset($_SESSION['username'])) { echo ", " . htmlspecialchars($_SESSION['username']); } ?></h1> -->
  <font size="6"> 
  <p>Your ultimate workout tracker</p>
  </font> 
  <img src="FitnessImage.jpg" alt="XSplit" height="75%" width="75%">

</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>