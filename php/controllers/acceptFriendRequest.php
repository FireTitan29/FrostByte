<?php
// Controller: acceptFriendRequest.php
// Purpose: Handles accepting friend requests between users.
// - Accepts POST requests with sender, user, and request ID parameters.
// - Inserts a new record into the `friends` table to establish friendship.
// - Removes the corresponding friend request from the `friend_requests` table.
// - Redirects to the homepage if accessed directly without required POST data.

    session_start();

    include '../library/database.php';
    include '../library/friendRequests.php';
    include '../library/posts.php';

    // Ensure this script is only accessed via POST with a like button
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['sender']) && isset($_POST['user'])&& isset($_POST['request'])) {
        $sender = $_POST['sender'];
        $user = $_POST['user'];
        $request = $_POST['request'];

        // Adding the users to the friends table to show that they are friends
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare('INSERT INTO friends (user1_id, user2_id, created_at) VALUES (:sender, :user_id, NOW())');
        $stmt->bindValue(':sender', $sender);
        $stmt->bindValue(':user_id', $user);
        $stmt->execute();

        // removing the request
        removeFriendRequest($request);
        closeDatabase($pdo);

    } else {
        // Block direct URL access (no POST data), send user back to index (home)
        header("Location: ../../index.php");
        exit;
    }
?>