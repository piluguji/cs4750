<?php
require("config/connect_db.php");
require("db_functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login_button'])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = checkLogin($username, $password);
        if ($result) {
            $_SESSION['userID'] = $result['userID']; // Assuming userID is returned from checkLogin function
            $_SESSION['username'] = $username;
            header("Location: views/index.php");
            // Redirect or do further processing
        } else {
            echo "Login failed";
            // Handle failed login
        }
    }else if (isset($_POST['signUp_button'])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $weight = $_POST["weight"];
        $height = $_POST["height"];
        $age = $_POST["age"];

        signUp($username, $password, $height, $age, $weight);
        header("Location: views/login.php");
    }else if(isset($_POST['submit_session_button'])){
        var_dump($_POST);
        $session_id = createSession($_POST['date'], $_POST['duration']);
        for($i = 1; $i <= count($_POST['exercise']); $i++) {
            $exerciseID = $_POST['exercise'][$i];
            $weight = $_POST['weight'][$i];
            $reps = $_POST['reps'][$i];
            $sets = $_POST['sets'][$i];

            create_exercise($session_id, $exerciseID, $weight, $reps, $sets);
        }
    }else{
        echo "Invalid request";
        var_dump($_POST);

    }
}
?>
