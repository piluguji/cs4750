<?php 
require('../db_functions.php'); // Ensure this path is correct.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Fetch nutrition goals from the database
$nutrition_goals = getNutritionGoals($_SESSION['userID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Nutrition Goals - YourAppName</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div class="text-center mb-4">
      <a href="index.php" class="btn btn-secondary btn-home">Home</a>
    </div>
    <div class="table-container">
      <h3 class="text-center mb-4">Nutrition Goals</h3>
      <table class="table">
      <thead>
  <tr>
    <th scope="col">#</th>
    <th scope="col">Protein Goal (g)</th>
    <th scope="col">Calorie Goal</th>
    <th scope="col">Date Set</th> <!-- This header corresponds to the 'Date' column in your DB -->
  </tr>
</thead>
<tbody>
  <?php foreach ($nutrition_goals as $index => $goal): ?>
    <tr>
      <th scope="row"><?php echo $index + 1; ?></th>
      <td><?php echo htmlspecialchars($goal['protein_goal']); ?></td>
      <td><?php echo htmlspecialchars($goal['calorie_goal']); ?></td>
      <td><?php echo htmlspecialchars($goal['date_set']); // Use 'date_set', which is the alias in your SELECT query ?></td>
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
