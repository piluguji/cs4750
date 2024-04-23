<?php
require("config/connect_db.php");
require("db_functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    if (isset($_POST['login_button'])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = checkLogin($username, $password);
        if ($result) {
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

        $result = signUp($username, $password, $height, $age, $weight);
        if ($result) {
            header("Location: views/index.php");
            // Redirect or do further processing
        } else {
            echo "Sign up failed";
            // Handle failed sign up
        }
    }
}
?>
