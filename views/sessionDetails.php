<?php
require('../db_functions.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Check if the sessionID parameter is provided
if (!isset($_GET['sessionID'])) {
    header('Location: viewSessions.php');
    exit();
}

$sessionID = $_GET['sessionID'];

// Fetch the exercises for the selected session
$exercises = getExercisesForSession($sessionID, $_SESSION['userID']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Session Details - XSplit</title>
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="text-center mb-4">
            <a href="viewWorkoutSessions.php" class="btn btn-secondary">Back to Sessions</a>
        </div>
        <h2>Exercises for Session</h2>
        <?php if (!empty($exercises)): ?>
            <ul class="list-group">
                <?php foreach ($exercises as $exercise): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($exercise['Title']) ?></strong><br>
                        Sets: <?= htmlspecialchars($exercise['sets']) ?><br>
                        Reps: <?= htmlspecialchars($exercise['reps']) ?><br>
                        Weight: <?= htmlspecialchars($exercise['weight']) ?> lbs
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No exercises found for this session.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>