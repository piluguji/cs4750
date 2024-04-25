<?php
require('config/connect_db.php');
session_start();

function checkLogin($username, $password) {
    global $db;   
    $query = "SELECT userID FROM User WHERE username='$username' AND password='$password'";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    
    return $result;
}

function addUser($username, $password){
    global $db;
    $query = "INSERT INTO User (username, password) VALUES (:username, :password)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $result = $statement->execute();
    $statement->closeCursor();
}

function addPersonalInfo($userId, $height, $weight, $age){
    $query = "INSERT INTO user_personal_info (height, weight, age, userID) VALUES (:height, :weight, :age, :user_id )";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->bindValue(':height', $height);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':age', $age);
    $result = $statement->execute();
    $statement->closeCursor();
}

function signUp($username, $password, $height, $age, $weight) {
    global $db;
    
    if(checkLogin($username, $password)){
        return false;
    }
    addUser($username, $password);
    $userId = $db->lastInsertId();
    addPersonalInfo($userId, $height, $weight, $age);
}

function getSessions($userId){
    global $db;
    $query = "SELECT * FROM workout_session WHERE userID = :user_id ORDER BY date DESC"; // Assuming 'date' is the column containing the date
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

?> 