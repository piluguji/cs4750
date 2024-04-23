<?php

function checkLogin($username, $password) {
    global $db;   
    $query = "SELECT * FROM User WHERE username='$username' AND password='$password'";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    if($result) {
        return true;
    } else {
        return false;
    }
}

function signUp($username, $password, $height, $age, $weight) {
    global $db;
    
    if(checkLogin($username, $password)){
        return false;
    }
    $query = "INSERT INTO User (username, password) VALUES (:username, :password)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $result = $statement->execute();
    $statement->closeCursor();

    $userId = $db->lastInsertId();
    $query = "INSERT INTO user_personal_info (height, weight, age, userID) VALUES (:height, :weight, :age, :user_id )";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->bindValue(':height', $height);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':age', $age);
    $result = $statement->execute();
    $statement->closeCursor();

    var_dump($result);
    if($result) {
        return true;
    } else {
        return false;
    }
}

?> 