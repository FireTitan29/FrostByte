<?php
// Controller: Unfriend Users
// This controller handles the logic for unfriending users
// The script deletes the friendship record from the database. 
// If the script is accessed directly (not via POST),
// it redirects the user to the home page to prevent unauthorized access.

    session_start();

    include '../library/database.php';
    include '../library/friendRequests.php';
    include '../library/posts.php';

    // Ensure this script is only accessed via POST with a like button
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['user1']) && isset($_POST['user2'])) {
        $user1 = $_POST['user1'];
        $user2 = $_POST['user2'];

        $pdo = connectToDatabase();

        $stmt = $pdo->prepare('DELETE FROM friends 
                            WHERE (user1_id = :user1 AND user2_id = :user2) 
                            OR (user1_id = :user2 AND user2_id = :user1)');

        $stmt->bindValue(':user1', $user1, PDO::PARAM_INT);
        $stmt->bindValue(':user2', $user2, PDO::PARAM_INT);
        $stmt->execute();
        closeDatabase($pdo);

    } else {
        // Block direct URL access (no POST data), send user back to index (home)
        header("Location: ../../index.php");
        exit;
    }
?>