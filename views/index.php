<?php 
require('../db_functions.php');
$exercises = json_encode(fetch_exercises());
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main Page</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Custom CSS for consistent color palette */
    body {
      background-color: #f8f9fa; /* Light grey */
    }
    .top-half {
      height: 50vh;
      background-color: #343a40; /* Dark grey */
    }
    .bottom-half {
      height: 50vh;
      background-color: #6c757d; /* Grey */
    }
    .form-container {
      background-color: #007bff; /* Blue */
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div class="container-fluid">
    <!-- Top Half -->
    <div class="row top-half">
      <div class="col-md-6">
        <!-- Left half with table -->
        <!-- Add your table here, which will be populated with dates from the database -->
        <!-- For now, it's left empty -->
        <table class="table table-dark">
          <thead>
            <tr>
              <th scope="col">Date</th>
              <th scope="col">Duration</th> 
            </tr>
          </thead>
          <?php 
            $session_list = getSessions($_SESSION['userID']);
            foreach ($session_list as $session): ?>
          <tbody>
            <td><?php echo $session['Date']; ?></td>
            <td><?php echo $session['Duration']; ?></td>
          </tbody>
          <?php endforeach; ?>  
        </table>
      </div>
      <div class="col-md-6">
        <!-- Right half -->
        <!-- Content will go here -->
      </div>
    </div>
    
    <!-- Bottom Half -->
<div class="row bottom-half">
  <div class="col-md-12">
    <!-- Form for adding exercise/session -->
    <div class="form-container">
      <h3 class="text-center text-light mb-3">Add Exercise/Session</h3>
      <form id="add-session-form" method="POST" action="../controller.php">
        <div class="container">
          <div class="row">
            <div class="col-md-6 offset-md-3">
              <div id="exercises-container">
                <!-- Exercise fields will be dynamically added here -->
                <!-- For now, only the duration input is present -->
                
                <div class="form-group">
                  <label for="date">Date:</label>
                  <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                  <label for="duration">Duration:</label>
                  <input type="number" class="form-control" name="duration" placeholder="Enter duration in mins" min="0">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 offset-md-3">
              <div class="text-center mb-3">
                <button type="button" class="btn btn-light" id="add-exercise-btn">Add Exercise</button>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 offset-md-3">
              <div class="text-center">
                <button type="submit" name="submit_session_button" class="btn btn-light">Submit Session</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function() {
  let exerciseCounter = 0;

  function addExercise() {
    exerciseCounter++;
    const exercisesContainer = document.getElementById("exercises-container");

    // Create exercise container div
    const exerciseDiv = document.createElement("div");
    exerciseDiv.classList.add("exercise");

    // Create exercise dropdown
    const exerciseSelect = document.createElement("select");
    exerciseSelect.name = `exercise[${exerciseCounter}]`;
    exerciseSelect.classList.add("form-control");
    // Populate exercise dropdown options from the database using PHP
    // You'll need to replace the placeholder options with actual options retrieved from the database
    const exerciseOptions = <?php echo $exercises ?>; 

    exerciseOptions.forEach(option => {
      const exerciseOption = document.createElement("option");
      exerciseOption.value = option[0];
      exerciseOption.textContent = option[1];
      exerciseSelect.appendChild(exerciseOption);
    });
    exerciseDiv.appendChild(exerciseSelect);

    // Create sets input
    const setsInput = document.createElement("input");
    setsInput.type = "number";
    setsInput.name = `sets[${exerciseCounter}]`;
    setsInput.classList.add("form-control");
    setsInput.placeholder = "Sets";
    exerciseDiv.appendChild(setsInput);

    // Create reps input
    const repsInput = document.createElement("input");
    repsInput.type = "number";
    repsInput.name = `reps[${exerciseCounter}]`;
    repsInput.classList.add("form-control");
    repsInput.placeholder = "Reps";
    exerciseDiv.appendChild(repsInput);

    // Create weight input
    const weightInput = document.createElement("input");
    weightInput.type = "number";
    weightInput.name = `weight[${exerciseCounter}]`;
    weightInput.classList.add("form-control");
    weightInput.placeholder = "Weight (lbs)";
    exerciseDiv.appendChild(weightInput);

    // Append exercise container to exercises container
    exercisesContainer.appendChild(exerciseDiv);
  }

  const addExerciseBtn = document.getElementById("add-exercise-btn");
  addExerciseBtn.addEventListener("click", addExercise);
});
</script>
