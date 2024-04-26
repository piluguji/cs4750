<?php
require('config/connect_db.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function deleteSession($sessionID, $userID) {
    global $db;
    
    // Delete session from workout_session table
    $query = "DELETE FROM workout_session WHERE sessionID = :session_id AND userID = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':session_id', $sessionID);
    $statement->bindValue(':user_id', $userID);
    $result = $statement->execute();
    $statement->closeCursor();

    // Delete associated exercise instances
    $query = "DELETE FROM exercise_instance WHERE sessionID = :session_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':session_id', $sessionID);
    $result = $statement->execute();
    $statement->closeCursor();

    return $result;
}
function checkLogin($username, $password) {
    global $db;   
    $query = "SELECT userID FROM User WHERE username='$username' AND password='$password'";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    
    return $result;
}
function getExercisesForSession($sessionID, $userID)
{
    global $db;
    $query = "SELECT e.Title, ei.sets, ei.reps, ei.weight
              FROM exercise_instance ei
              JOIN Exercise e ON ei.exerciseID = e.ID
              JOIN workout_session ws ON ei.sessionID = ws.sessionID
              WHERE ws.sessionID = :session_id AND ws.userID = :user_id";

    $statement = $db->prepare($query);
    $statement->bindValue(':session_id', $sessionID, PDO::PARAM_INT);
    $statement->bindValue(':user_id', $userID, PDO::PARAM_INT);
    $statement->execute();
    $exercises = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $exercises;
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

// Fetch the list of user's favorite exercises
function fetch_favorites($userID){
    global $db;
    $query = "SELECT f.exerciseID, e.Title as name
              FROM favorites f
              JOIN Exercise e ON f.exerciseID = e.ID
              WHERE f.userID = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userID);
    $statement->execute();
    $favorites = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $favorites;
}

// Add an exercise to the user's favorites
function add_to_favorites($userID, $exerciseID) {
    global $db;
    $query = "INSERT INTO favorites (userID, exerciseID) VALUES (:user_id, :exercise_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userID);
    $statement->bindValue(':exercise_id', $exerciseID);
    $result = $statement->execute();
    $statement->closeCursor();
    return $result;
}

// Remove an exercise from the user's favorites
function remove_from_favorites($userID, $exerciseID){
    global $db;
    $query = "DELETE FROM favorites WHERE userID = :user_id AND exerciseID = :exercise_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userID);
    $statement->bindValue(':exercise_id', $exerciseID);
    $result = $statement->execute();
    $statement->closeCursor();
    return $result;
}
?>

?> 