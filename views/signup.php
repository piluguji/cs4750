<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Sign Up
        </div>
        <div class="card-body">
          <form method="POST" action="../controller.php">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
              <label for="height">Height (in cm)</label>
              <input type="number" class="form-control" id="height" name="height" required>
            </div>
            <div class="form-group">
              <label for="weight">Weight (in kg)</label>
              <input type="number" class="form-control" id="weight" name="weight" required>
            </div>
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <button type="submit" name = "signUp_button" class="btn btn-primary">Sign Up</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
