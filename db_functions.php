<?php
require('config/connect_db.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function deleteSession($sessionID, $userID) {
    global $db;

    // Begin transaction
    $db->beginTransaction();

    try {
        // Delete records from 'provides' where 'feedbackID' is linked to the 'sessionID'
        $queryProvides = "DELETE FROM provides WHERE feedbackID IN 
                          (SELECT feedbackID FROM workout_feedback WHERE sessionID = :session_id)";
        $statementProvides = $db->prepare($queryProvides);
        $statementProvides->bindValue(':session_id', $sessionID);
        $statementProvides->execute();

        // Delete records from 'progress' where 'feedbackID' is linked to the 'sessionID'
        $queryProgress = "DELETE FROM progress WHERE feedbackID IN 
                          (SELECT feedbackID FROM workout_feedback WHERE sessionID = :session_id)";
        $statementProgress = $db->prepare($queryProgress);
        $statementProgress->bindValue(':session_id', $sessionID);
        $statementProgress->execute();

        // Delete feedback from the 'workout_feedback' table
        $queryFeedback = "DELETE FROM workout_feedback WHERE sessionID = :session_id";
        $statementFeedback = $db->prepare($queryFeedback);
        $statementFeedback->bindValue(':session_id', $sessionID);
        $statementFeedback->execute();

        // Delete associated exercise instances
        $queryExercises = "DELETE FROM exercise_instance WHERE sessionID = :session_id";
        $statementExercises = $db->prepare($queryExercises);
        $statementExercises->bindValue(':session_id', $sessionID);
        $statementExercises->execute();

        // Finally, delete the session from 'workout_session'
        $querySession = "DELETE FROM workout_session WHERE sessionID = :session_id AND userID = :user_id";
        $statementSession = $db->prepare($querySession);
        $statementSession->bindValue(':session_id', $sessionID);
        $statementSession->bindValue(':user_id', $userID);
        $statementSession->execute();

        // Commit transaction
        $db->commit();

        // Close all statement cursors
        $statementProvides->closeCursor();
        $statementProgress->closeCursor();
        $statementFeedback->closeCursor();
        $statementExercises->closeCursor();
        $statementSession->closeCursor();

        return true;
    } catch (PDOException $e) {
        // Rollback transaction on error
        $db->rollBack();
        error_log("Error in deleteSession: " . $e->getMessage());
        return false; // or return the error message if you want to display it
    }
}

function checkLogin($username) {
    global $db;   
    $query = "SELECT userID, password FROM User WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function signUp($username, $password, $height, $age, $weight) {
    global $db;
    // Insert the user into the database
    $query = "INSERT INTO User (username, password) VALUES (:username, :password)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->execute();
    $userID = $db->lastInsertId(); // Get the last inserted ID
    
    // Insert personal info into the database
    addPersonalInfo($userID, $height, $weight, $age);
    return $userID; // Return the new user's ID
}

function addPersonalInfo($userId, $height, $weight, $age){
    global $db;
    $query = "INSERT INTO user_personal_info (userID, height, weight, age) VALUES (:user_id, :height, :weight, :age)";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->bindValue(':height', $height);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':age', $age);
    $statement->execute();
    $statement->closeCursor();
}

function usernameExists($username) {
    global $db;
    $query = "SELECT userID FROM User WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $user ? true : false;
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


function getSessions($userId){
    // global $db;
    // $query = "SELECT * FROM workout_session WHERE userID = :user_id ORDER BY date DESC"; // Assuming 'date' is the column containing the date
    // $statement = $db->prepare($query);
    // $statement->bindValue(':user_id', $userId);
    // $statement->execute();
    // $result = $statement->fetchAll();
    // $statement->closeCursor();
    // return $result;
    global $db;
    $query = "
        SELECT ws.sessionID, ws.date, ws.duration, IFNULL(AVG(wf.rating), 'No feedback yet') as avgRating
        FROM workout_session ws
        LEFT JOIN workout_feedback wf ON ws.sessionID = wf.sessionID
        WHERE ws.userID = :user_id
        GROUP BY ws.sessionID
        ORDER BY ws.date DESC
    ";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    $query = "SELECT ID, Title FROM Exercise ORDER BY Title ASC"; // Remove the limit 1 to fetch all exercises
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

function create_exercise_instance($session_id, $exerciseID, $weight, $reps, $sets){
    global $db;
    $query = "INSERT INTO exercise_instance (sets, reps, weight, exerciseID, sessionID) VALUES (:sets, :reps, :weight, :exercise_id, :session_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':sets', $sets, PDO::PARAM_INT);
    $statement->bindValue(':reps', $reps, PDO::PARAM_INT);
    $statement->bindValue(':weight', $weight);
    $statement->bindValue(':exercise_id', $exerciseID, PDO::PARAM_INT);
    $statement->bindValue(':session_id', $session_id, PDO::PARAM_INT);
    $result = $statement->execute();
    $statement->closeCursor();
    return $result;
}
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
    
    // Check if the entry already exists
    $checkQuery = "SELECT 1 FROM favorites WHERE userID = :user_id AND exerciseID = :exercise_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':user_id', $userID);
    $checkStmt->bindValue(':exercise_id', $exerciseID);
    $checkStmt->execute();

    // If the entry exists, fetch will return true
    if ($checkStmt->fetch()) {
        // The exercise is already in favorites, so we just return without doing anything
        return true; // You could also return false or some indicator that it already exists
    }
    
    // If we get here, the entry doesn't exist, so we can safely insert it
    $query = "INSERT INTO favorites (userID, exerciseID) VALUES (:user_id, :exercise_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userID);
    $statement->bindValue(':exercise_id', $exerciseID);
    $result = $statement->execute();
    $statement->closeCursor();
    
    return $result;
}

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

function inFavorites($userID, $exerciseID) {
    global $db;

    // Prepare the SQL query to check if the exercise exists in the user's favorites
    $query = "SELECT COUNT(*) FROM favorites WHERE userID = :user_id AND exerciseID = :exercise_id";

    // Prepare the statement
    $statement = $db->prepare($query);

    // Bind the user ID and exercise ID parameters
    $statement->bindValue(':user_id', $userID, PDO::PARAM_INT);
    $statement->bindValue(':exercise_id', $exerciseID, PDO::PARAM_INT);

    // Execute the query
    $statement->execute();

    // Fetch the count result
    $count = $statement->fetchColumn();

    // Close the cursor to free up the statement
    $statement->closeCursor();

    // Return true if count is more than 0, meaning the exercise is a favorite
    return $count > 0;
}


function addFeedback($sessionID, $rating, $comments) {
    global $db;

    // Begin transaction
    $db->beginTransaction();

    try {
        // Insert into workout_feedback
        $queryFeedback = "INSERT INTO workout_feedback (sessionID, rating, comments) VALUES (:sessionID, :rating, :comments)";
        $statementFeedback = $db->prepare($queryFeedback);
        $statementFeedback->bindValue(':sessionID', $sessionID);
        $statementFeedback->bindValue(':rating', $rating);
        $statementFeedback->bindValue(':comments', $comments);
        $statementFeedback->execute();

        // Get the last inserted feedback ID
        $feedbackID = $db->lastInsertId();


        // Insert into provides
        $queryProvides = "INSERT INTO provides (feedbackID, userID) VALUES (:feedbackID, :userID)";
        $statementProvides = $db->prepare($queryProvides);
        $statementProvides->bindValue(':feedbackID', $feedbackID);
        $statementProvides->bindValue(':userID', $_SESSION['userID']);
        $statementProvides->execute();

        // Commit transaction
        $db->commit();

        // Close all statement cursors
        $statementFeedback->closeCursor();
        $statementProvides->closeCursor();

        return true;
    } catch (PDOException $e) {
        // Rollback transaction on error
        $db->rollBack();
        error_log("Error in addFeedback: " . $e->getMessage());
        return false;
    }
}

function getFeedback($sessionID) {
    global $db;
    $query = "SELECT rating, comments FROM workout_feedback WHERE sessionID = :session_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':session_id', $sessionID);
    $statement->execute();
    return $statement->fetchAll();
}

function nutritionAlreadyOnDay($date, $userID){
    global $db;
    $query = "SELECT nutritionID FROM Nutrition WHERE `Date` = :date AND userID = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':date', $date);
    $statement->bindValue(':user_id', $userID);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result ? true : false;
}

function createNutrition($protein_goal, $calorie_goal, $date, $user_id){
    global $db;
    if (nutritionAlreadyOnDay($date, $user_id)){
        return "Nutrition for that date already exists"; 
    }else{
        $query = "INSERT INTO Nutrition (protein_goal, calorie_goal, `Date`, userID) VALUES (:protein_goal, :calorie_goal, :Date, :user_id)";
        $statement = $db->prepare($query);
        $statement->bindValue(':protein_goal', $protein_goal);
        $statement->bindValue(':calorie_goal', $calorie_goal);
        $statement->bindValue(':Date', $date); 
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        $statement->closeCursor();
        return TRUE;
    }
    
}



function getNutritionGoals($userID) {
    global $db;
    $query = "SELECT nutritionID, protein_goal, calorie_goal, `Date` as date_set FROM Nutrition WHERE userID = :user_id ORDER BY `Date` DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userID, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}




function getNutritionByID($nutritionID) {
    global $db;
    $query = "SELECT nutritionID, protein_goal, calorie_goal, `Date` as date_set FROM Nutrition WHERE nutritionID = :nutritionID";
    $statement = $db->prepare($query);
    $statement->bindValue(':nutritionID', $nutritionID, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC); 
}



function updateNutrition($nutritionID, $protein_goal, $calorie_goal, $date, $userID) {
    global $db;
    try {
        $query = "UPDATE Nutrition SET protein_goal = :protein_goal, calorie_goal = :calorie_goal, Date = :date WHERE nutritionID = :nutritionID AND userID = :userID";
        $statement = $db->prepare($query);
        $statement->bindValue(':protein_goal', $protein_goal);
        $statement->bindValue(':calorie_goal', $calorie_goal);
        $statement->bindValue(':date', $date);
        $statement->bindValue(':nutritionID', $nutritionID);
        $statement->bindValue(':userID', $userID);
        $result = $statement->execute();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        error_log("Update Nutrition Error: " . $e->getMessage());
        return false;
    }
}





?>