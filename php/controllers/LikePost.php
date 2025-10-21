<?php
    session_start();

    // Controller: Handles "like" and "unlike" actions for posts.
    // - Increments/decrements like counter in 'posts' table
    // - Inserts/removes record in 'post_likes' table to track user likes
    // Redirects to homepage if accessed directly without POST.

    include '../library/database.php';
    include '../library/notifications.php';
    include '../library/posts.php';

    // Ensure this script is only accessed via POST with a like button
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['like-button-id'])) {
        // Get DB connection and current user/post info
        $pdo = connectToDatabase();

        $post_id = $_POST['like-button-id'];
        $user_id = $_SESSION['user']['id'];

        // Check if this user has already liked the post
        $stmt = $pdo->prepare('SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->bindValue(':post_id', $post_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch();

        // If not yet liked: increment counter and insert like record
        if (!$result) {

            // Selecting from the posts table
            $stmt = $pdo->prepare('SELECT likes FROM posts WHERE post_id = :post_id LIMIT 1');
            $stmt->bindValue(':post_id', $post_id);

            $stmt->execute();

            $currentLikes = $stmt->fetchColumn();

            $newLikes = $currentLikes+1;

            // Update total likes for the post
            $stmt = $pdo->prepare('UPDATE posts SET likes = :newLikes WHERE post_id = :post_id');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':newLikes', $newLikes);
            $stmt->execute();

            // Record that this user has liked the post
            $stmt = $pdo->prepare('INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();

            // Adding a notification to the post user
            addNotification(getUserOfPost($post_id), $user_id, 'liked your post');
            
        } else {

            // If already liked: decrement counter and remove like record
            $stmt = $pdo->prepare('SELECT likes FROM posts WHERE post_id = :post_id LIMIT 1');
            $stmt->bindValue(':post_id', $post_id);

            $stmt->execute();

            $currentLikes = $stmt->fetchColumn();

            $newLikes = $currentLikes-1;

            // Update total likes for the post
            $stmt = $pdo->prepare('UPDATE posts SET likes = :newLikes WHERE post_id = :post_id');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':newLikes', $newLikes);
            $stmt->execute();

            // Updating the likes table to remove the like
            $stmt = $pdo->prepare('DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();

            addNotification(getUserOfPost($post_id), $user_id, 'unliked your post');
        }
        exit;
    } else {
        // Block direct URL access (no POST data), send user back to index (home)
        header("Location: ../../index.php");
        exit;
    }
?>