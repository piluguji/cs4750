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
    }
}
?>
