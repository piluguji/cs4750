<?php
require("config/connect_db.php");
require("db_functions.php");
// session_start();
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
            header('Location: favorites.php');
        } else {
            // Redirect back or output an error message.
            $_SESSION['error'] = 'Could not add the exercise to favorites.';
            header('Location: favorites.php');
        }
        exit();
    }
}

if (isset($_POST['remove_from_favorites'])) {
    $userID = $_SESSION['userID'];
    $exerciseID = $_POST['exerciseID'];
    $result = remove_from_favorites($userID, $exerciseID);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        // Handle error
        echo json_encode(['error' => 'Could not remove from favorites']);
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

            create_exercise($sessionID, $exerciseID, $weight, $reps, $sets);
            if ($_POST['favorite'][$index] == 'yes') {
                add_to_favorites($_SESSION['userID'], $exerciseID);
            }
            else {
                // Optionally remove from favorites if it's already there
                remove_from_favorites($_SESSION['userID'], $exerciseID);
            }
        }
    }

    // Redirect or show a success message
    header('Location: views/viewWorkoutSessions.php');
    exit();
}


// You'll need to create this function to fetch the exercise name based on its ID
function fetch_exercise_name_by_id($exerciseID) {
    global $db;
    $query = "SELECT Title FROM Exercise WHERE ID = :exercise_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':exercise_id', $exerciseID);
    $statement->execute();
    $exercise = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $exercise['Title'];
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp_button'])) {
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashing the password
        $height = $_POST["height"];
        $weight = $_POST["weight"];
        $age = $_POST["age"];
        signUp($username, $password, $height, $age, $weight); // Adjust the signUp function to handle the hashed password
        header("Location: views/login.php");
    }
    // Other POST handlers remain unchanged
}




?>