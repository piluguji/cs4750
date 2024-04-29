<?php 
require('../db_functions.php'); 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}


$nutrition_goals = getNutritionGoals($_SESSION['userID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Nutrition Goals - YourAppName</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

  <style>
    body {
      background-color: #f8f9fa;
    }
    .table-container {
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

  <div class="container mt-4">

    <div class="table-container">
      <h3 class="text-center mb-4">Nutrition Goals</h3>
      <table class="table">
      <thead>
  <tr>
    <th scope="col">#</th>
    <th scope="col">Protein Goal (g)</th>
    <th scope="col">Calorie Goal</th>
    <th scope="col">Date</th> 
  </tr>
</thead>
<tbody>
<?php foreach ($nutrition_goals as $index => $goal): ?>
    <tr>
        <th scope="row"><?php echo $index + 1; ?></th>
        <td><?php echo htmlspecialchars($goal['protein_goal']); ?></td>
        <td><?php echo htmlspecialchars($goal['calorie_goal']); ?></td>
        <td><?php echo htmlspecialchars($goal['date_set']); ?></td>
        <td>
            <a href="updateNutrition.php?nutritionID=<?= htmlspecialchars($goal['nutritionID']); ?>" class="btn btn-primary">Update</a>
        </td>
    </tr>
<?php endforeach; ?>


</tbody>

      </table>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
</body>
</html>
