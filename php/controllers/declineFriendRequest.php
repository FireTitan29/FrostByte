<?php
    // Controller: Decline Friend Request
    // - Handles the removal of a pending friend request
    // - Expects POST data with sender, user, and request IDs
    // - Calls removeFriendRequest() to delete the request
    // - Redirects to homepage if accessed directly without POST
    session_start();

    include '../library/database.php';
    include '../library/friendRequests.php';
    include '../library/posts.php';

    // Ensure this script is only accessed via POST with a like button
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['sender']) && isset($_POST['user'])&& isset($_POST['request'])) {
        $sender = $_POST['sender'];
        $user = $_POST['user'];
        $request = $_POST['request'];
        
        // removing the request
        removeFriendRequest($request);
        closeDatabase($pdo);

    } else {
        // Block direct URL access (no POST data), send user back to index (home)
        header("Location: ../../index.php");
        exit;
    }
?>