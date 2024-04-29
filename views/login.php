<?php
require('../config/connect_db.php');

session_start();


$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Login
        </div>
        <div class="card-body">
          <?php if($login_error): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $login_error; ?>
            </div>
          <?php endif; ?>
          <form method="POST" action="../controller.php">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login_button" class="btn-default btn-block btn-primary">Login</button>
             Don't have an account? <a href="signup.php">Sign up</a>


          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>