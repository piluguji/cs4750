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

function createSession($date, $duration){
    global $db;
    $query = "INSERT INTO workout_session (date, duration, userID) VALUES (:date, :duration, :user_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':date', $date);
    $statement->bindValue(':duration', $duration);
    $statement->bindValue(':user_id', $_SESSION['userID']);
    $result = $statement->execute();
    $statement->closeCursor();
    return $db->lastInsertId();;
}

function fetch_exercises(){
    global $db;
    $query = "SELECT ID, Title FROM Exercise"; // Remove the limit 1 to fetch all exercises
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC); // Specify fetching as associative array
    $statement->closeCursor();
    $exercises = array(); // Initialize an empty array to store tuples
    foreach ($result as $row) {
        // echo $row['ID'];
        // echo $row['Title'];
        $exercises[] = array($row['ID'], $row['Title']); // Add each tuple to the array
    }
    return $exercises;
}

function create_exercise($session_id, $exerciseID, $weight, $reps, $sets){
    global $db;
    $query = "INSERT INTO exercise_instance (sets, reps, weight, exerciseID, sessionID) VALUES ($sets, :reps, :weight, :exercise_id, :session_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':reps', $reps);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':exercise_id', $exerciseID);
    $statement->bindValue(':session_id', $session_id);
    $result = $statement->execute();
    $statement->closeCursor();
    return $result;
}


?> 