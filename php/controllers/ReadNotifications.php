<?php 
    // Controller: MarkNotificationsRead.php
    // Purpose: Marks all notifications for the logged-in user as "read"
    // - Accepts POST requests only
    // - Uses the current session user ID to update notifications
    // - Sets 'is_read' = 1 in the 'notifications' table for that user
    // - Performs no output (silent update)
    // - Redirects to homepage if accessed directly without POST

    session_start();

    include '../library/database.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']['id'])) {

        $pdo = connectToDatabase();
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = :userid');
        $stmt->bindValue(':userid', $_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->execute();
        closeDatabase($pdo);
    } else {
    header("Location: ../../index.php");
    exit;
}
?>