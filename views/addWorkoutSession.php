<?php 
require('../db_functions.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
  header('Location: login.php');
  exit();
}

$exercises = json_encode(fetch_exercises());
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Workout Session - XSplit</title>
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
    .form-container {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }
    .exercise-container {
      background-color: #e9ecef;
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
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

    </div>
    <div class="form-container">
      <h3 class="text-center mb-4">Add Workout Session</h3>
            <form id="add-session-form" method="POST" action="../controller.php">
        <div class="form-group">
            <label for="session-date">Date</label>
            <input type="date" class="form-control" id="session-date" name="date" required>
        </div>
        <div class="form-group">
            <label for="session-duration">Duration (mins)</label>
            <input type="number" class="form-control" id="session-duration" name="duration" min="1" required>
        </div>
        <div id="exercises-container">
            
        </div>
        <div class="text-center mb-3">
            <button type="button" class="btn btn-primary" id="add-exercise-btn">Add Exercise</button>
        </div>
        <div class="text-center">
            <button type="submit" name="submit_session_button" class="btn btn-success">Submit Session</button>
        </div>
        </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
document.addEventListener("DOMContentLoaded", function() {
  let exerciseCounter = 0;

  function addExercise() {
    exerciseCounter++;
    const exercisesContainer = document.getElementById("exercises-container");
    exerciseAdded = true; 

    const exerciseDiv = document.createElement("div");
    exerciseDiv.classList.add("exercise-entry", "mb-3");
    exerciseDiv.id = "exercise-entry-" + exerciseCounter;

    const selectList = document.createElement("select");
    selectList.name = "exercise[" + exerciseCounter + "]";
    selectList.classList.add("form-control", "mb-2");

    const exercises = <?php echo $exercises; ?>;
    exercises.forEach(function(exercise) {
      const option = document.createElement("option");
      option.value = exercise[0];
      option.text = exercise[1];
      selectList.appendChild(option);
    });

    exerciseDiv.appendChild(selectList);

    const inputSets = document.createElement("input");
    inputSets.type = "number";
    inputSets.name = "sets[" + exerciseCounter + "]";
    inputSets.classList.add("form-control", "mb-2");
    inputSets.placeholder = "Sets";
    exerciseDiv.appendChild(inputSets);

    const inputReps = document.createElement("input");
    inputReps.type = "number";
    inputReps.name = "reps[" + exerciseCounter + "]";
    inputReps.classList.add("form-control", "mb-2");
    inputReps.placeholder = "Reps";
    exerciseDiv.appendChild(inputReps);

    const inputWeight = document.createElement("input");
    inputWeight.type = "number";
    inputWeight.name = "weight[" + exerciseCounter + "]";
    inputWeight.classList.add("form-control", "mb-2");
    inputWeight.placeholder = "Weight (kg)";
    exerciseDiv.appendChild(inputWeight);

    
    const favoriteSelect = document.createElement("select");
    favoriteSelect.name = "favorite[" + exerciseCounter + "]";
    favoriteSelect.classList.add("form-control", "mb-2");

    const yesOption = document.createElement("option");
    yesOption.value = "yes";
    yesOption.text = "Make Favorite Exercise";
    favoriteSelect.appendChild(yesOption);

    const noOption = document.createElement("option");
    noOption.value = "no";
    noOption.text = "Don't Make Favorite Exercise";
    favoriteSelect.appendChild(noOption);

    exerciseDiv.appendChild(favoriteSelect);

    const removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.innerText = "Remove";
    removeButton.classList.add("btn", "btn-danger", "btn-sm");
    removeButton.onclick = function() {
      exercisesContainer.removeChild(exerciseDiv);
    };
    exerciseDiv.appendChild(removeButton);

    exercisesContainer.appendChild(exerciseDiv);


  }

    document.getElementById("add-exercise-btn").addEventListener("click", addExercise);
    
  document.getElementById('add-session-form').addEventListener('submit', function(event) {
    
    if (exerciseCounter === 0) {
      alert("Please add at least one exercise to the workout session.");
      event.preventDefault(); 
      return;
    }

   
    const exerciseEntries = document.querySelectorAll('.exercise-entry');
    for (let i = 0; i < exerciseEntries.length; i++) {
      const inputs = exerciseEntries[i].querySelectorAll('input');
      const selects = exerciseEntries[i].querySelectorAll('select');
      
     
      for (let input of inputs) {
        if (input.value === '') {
          alert("Please fill in all fields for each added exercise.");
          event.preventDefault();
          return;
        }
      }
      for (let select of selects) {
        if (select.value === '') {
          alert("Please make a selection for each added exercise.");
          event.preventDefault();
          return;
        }
      }
    }
  });
});
  </script>
</body>
</html>