<?php 
require('../db_functions.php'); // Ensure this path is correct.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $protein_goal = filter_input(INPUT_POST, 'protein_goal', FILTER_VALIDATE_INT);
    $calorie_goal = filter_input(INPUT_POST, 'calorie_goal', FILTER_VALIDATE_INT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING); // Note the lowercase 'date' here, it's the name of the form input field



    // Assuming you have a session variable called 'userID' where the user's ID is stored.
    $user_id = $_SESSION['userID'];

    $result = createNutrition($protein_goal, $calorie_goal, $date, $user_id);
    if ($result !== TRUE) {
        echo "<script>alert('Error: $result');</script>";  // Only show the alert if there's an error message
    } else {
        header('Location: index.php');
        exit();
    }
    
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Nutrition Goals</title>
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
    .btn-home {
      background-color: #343a40;
      color: #ffffff;
      margin-bottom: 10px;
    }
  </style>
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


  <div class="container mt-4">

    <div class="form-container">
      <h3 class="text-center mb-4">Add Daily Nutrition Goals</h3>
      <form id="add-nutrition-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
    <label for="date">Date</label>
    <input type="date" class="form-control" id="date" name="date" required>
</div>
        <div class="form-group">
            <label for="protein_goal">Protein Goal (grams)</label>
            <input type="number" class="form-control" id="protein_goal" name="protein_goal" required>
        </div>
        <div class="form-group">
            <label for="calorie_goal">Calorie Goal</label>
            <input type="number" class="form-control" id="calorie_goal" name="calorie_goal" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Submit Goals</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
</body>
</html>
