<?php
    session_start();

    include '../library/database.php';
    include '../library/friendRequests.php';
    include '../library/posts.php';

    // Ensure this script is only accessed via POST with a like button
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['sender']) && isset($_POST['receiver'])) {
        sendFriendRequest($_POST['sender'], $_POST['receiver']);
    } else {
        // Block direct URL access (no POST data), send user back to index (home)
        header("Location: ../../index.php");
        exit;
    }
?>