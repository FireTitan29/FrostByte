<?php
    // Controller: Handles updating a user's theme preference.
    // - Receives theme value from JavaScript via POST
    // - Updates 'theme' column in 'users' table for the current session user
    // - Updates session variable so theme applies immediately
    // - Redirects to homepage if accessed directly without POST

    session_start();

    include '../library/database.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['theme'])) {
        // Getting values
        $theme = $_POST['theme'];
        $user_id = $_SESSION['user']['id'];

        $pdo = connectToDatabase();

        $stmt = $pdo->prepare("UPDATE users SET theme = :theme WHERE id = :id");
        $stmt->bindValue(':theme', $theme);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['user']['theme'] = $theme;
        closeDatabase($pdo);
        exit;
    } else {
        // If the user comes to this file directly (no post), then send them back to the index page
        header("Location: ../../index.php");
        exit;
    }
?>