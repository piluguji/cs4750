<?php
require("config/connect_db.php");
require("db_functions.php");

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

            create_exercise($sessionID, $exerciseID, $weight, $reps, $sets);
        }
    }

    // Redirect or show a success message
    header('Location: views/viewWorkoutSessions.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login_button'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $result = checkLogin($username, $password);
        if ($result) {
            $_SESSION['userID'] = $result['userID']; // Assuming userID is returned from checkLogin function
            $_SESSION['username'] = $username;
            header("Location: views/index.php"); // Redirect or do further processing
        } else {
            echo "Login failed"; // Handle failed login
        }
    } else if (isset($_POST['signUp_button'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $weight = $_POST["weight"];
        $height = $_POST["height"];
        $age = $_POST["age"];
        signUp($username, $password, $height, $age, $weight);
        header("Location: views/login.php");
    } else {
        echo "Invalid request";
        var_dump($_POST);
    }
}
?>