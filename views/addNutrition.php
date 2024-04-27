<?php 
require('../db_functions.php'); // Ensure this path is correct.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $protein_goal = filter_input(INPUT_POST, 'protein_goal', FILTER_VALIDATE_INT);
    $calorie_goal = filter_input(INPUT_POST, 'calorie_goal', FILTER_VALIDATE_INT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING); // Note the lowercase 'date' here, it's the name of the form input field



    // Assuming you have a session variable called 'userID' where the user's ID is stored.
    $user_id = $_SESSION['userID'];

    // Call the function to insert the goals into the database.
    $nutrition_id = createNutrition($protein_goal, $calorie_goal, $date, $user_id); // Ensure you capture the 'date' from the form

    // After inserting the data, redirect to the home page.
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Nutrition Goals - YourAppName</title>
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

  <div class="container mt-4">
    <div class="text-center mb-4">
      <a href="index.php" class="btn btn-secondary btn-home">Home</a>
      <a href="viewNutritionGoals.php" class="btn btn-info">View Nutrition Goals</a>
    </div>
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