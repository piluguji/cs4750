<?php
require("config/connect_db.php");
require("db_functions.php");
// session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_button'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $user = checkLogin($username);
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['username'] = $username;
        header("Location: views/index.php");
        exit();
    } else {
        // session_start();
        $_SESSION['login_error'] = 'Invalid username or password. Please try again.';
        header("Location: views/login.php");
        exit();
    }
}

// Handling the sign up
if (isset($_POST['signUp_button'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $height = trim($_POST['height']);
    $weight = trim($_POST['weight']);
    $age = trim($_POST['age']);
    
    if (usernameExists($username)) {
        // Username already taken
        $_SESSION['signup_error'] = 'Username already taken. Please choose a different one.';
        header("Location: views/signup.php");
        exit();
    } else {
        // Username is unique, proceed with signup
        $userID = signUp($username, $password, $height, $age, $weight);
        // Set session variables and log the user in
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] = $username;
        // Redirect to the home page
        header("Location: views/index.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form for adding to favorites was submitted.
    if (isset($_POST['exerciseID'])) {
        // The user's ID should be in the session from when they logged in.
        $userID = $_SESSION['userID'];
        $exerciseID = $_POST['exerciseID'];

        // Call the function to add the favorite.
        $result = add_to_favorites($userID, $exerciseID);

        // Based on the result, you can redirect or output a message.
        if ($result) {
            // Redirect back to the favorites page with a success message.
            $_SESSION['message'] = 'Exercise added to favorites!';
            header('Location: views/favorites.php');
        } else {
            // Redirect back or output an error message.
            $_SESSION['error'] = 'Could not add the exercise to favorites.';
            header('Location: views/favorites.php');
        }
        exit();
    }
}



if (isset($_POST['submit_session_button'])) {
    $date = $_POST['date'];
    $duration = $_POST['duration'];

    // Create the workout session
    $sessionID = createSession($date, $duration);

    // Process the exercise instances
    if (isset($_POST['exercise'])) {
        foreach ($_POST['exercise'] as $index => $exerciseID) {
            $sets = $_POST['sets'][$index];
            $reps = $_POST['reps'][$index];
            $weight = $_POST['weight'][$index];

            create_exercise_instance($sessionID, $exerciseID, $weight, $reps, $sets);
            if ($_POST['favorite'][$index] == 'yes') {
                add_to_favorites($_SESSION['userID'], $exerciseID);
            }
            else { //if user responds with no
                //First check if the exercise is already in favorites
                if (inFavorites($_SESSION['userID'], $exerciseID)) {
                    
                    remove_from_favorites($_SESSION['userID'], $exerciseID);
                } 
            }
        }
    }

    // Redirect or show a success message
    header('Location: views/viewWorkoutSessions.php');
    exit();
}







?>