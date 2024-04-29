<?php 
require('../db_functions.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: viewNutrition.php');
    exit();
}

$nutritionID = isset($_GET['nutritionID']) ? intval($_GET['nutritionID']) : null;
if (!$nutritionID) {
    die('No nutrition goal ID provided.');
}

$nutrition_goal = getNutritionByID($nutritionID);

$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $protein_goal = $_POST['protein_goal'];
    
    $calorie_goal = $_POST['calorie_goal'];
    $date = $_POST['date']; 
    
    if (updateNutrition($nutritionID, $protein_goal, $calorie_goal, $date, $_SESSION['userID'])) {
        header('Location: viewNutrition.php');
        exit();
    } else {
        $error = 'Failed to update the nutrition goal.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Nutrition Goal</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <h3 class="card-title">Edit Nutrition Goal</h3>
      <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?nutritionID=<?= htmlspecialchars($nutritionID) ?>">
        <div class="form-group">
          <label for="protein_goal">Protein Goal (grams)</label>
          <input type="number" class="form-control" name="protein_goal" id="protein_goal" value="<?php echo $nutrition_goal['protein_goal']; ?>" required>
        </div>
        <div class="form-group">
          <label for="calorie_goal">Calorie Goal</label>
          <input type="number" class="form-control" name="calorie_goal" id="calorie_goal" value="<?php echo $nutrition_goal['calorie_goal']; ?>" required>
        </div>
        <div class="form-group">
          <label for="date">Date</label>
          <input type="date" class="form-control" name="date" id="date" value="<?php echo $nutrition_goal['date_set']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Goal</button>
        <a href="viewNutrition.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
