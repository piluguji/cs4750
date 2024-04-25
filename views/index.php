<?php 
require('../db_functions.php');
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
          <form>
            <div class="form-group">
              <label for="exercise">Exercise:</label>
              <input type="text" class="form-control" id="exercise" placeholder="Enter exercise">
            </div>
            <div class="form-group">
              <label for="duration">Duration (minutes):</label>
              <input type="number" class="form-control" id="duration" placeholder="Enter duration">
            </div>
            <button type="submit" class="btn btn-light">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
