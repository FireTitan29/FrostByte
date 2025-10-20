<?php
    session_start();

    // What happens when a post is liked... I used a simple post method.
    // This function works by connecting to the DB, fetching the 
    // like counter associated with the post_id and then increments it by +1, 
    // and then sends to back to the DB, but only if the user hasn't like the post previously.
    // Otherwise it decreases the post count

    if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['like-button-id'])) {
        $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $post_id = $_POST['like-button-id'];
        $user_id = $_SESSION['user']['id'];

        // Selecting from the post_likes table so that we can
        // check if this user already liked this post
        $stmt = $pdo->prepare('SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->bindValue(':post_id', $post_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch();

        // If the post has not be liked before
        if (!$result) {
            // Selecting from the posts table
            $stmt = $pdo->prepare('SELECT likes FROM posts WHERE post_id = :post_id LIMIT 1');
            $stmt->bindValue(':post_id', $post_id);

            $stmt->execute();

            $currentLikes = $stmt->fetchColumn();

            $newLikes = $currentLikes+1;

            // updating the posts table
            $stmt = $pdo->prepare('UPDATE posts SET likes = :newLikes WHERE post_id = :post_id');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':newLikes', $newLikes);
            $stmt->execute();

            // Updating the likes table
            $stmt = $pdo->prepare('INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
        } else {
            // This will remove the like if the like button is selected again (basically if a user unlikes a post)
            // Selecting from the posts table
            $stmt = $pdo->prepare('SELECT likes FROM posts WHERE post_id = :post_id LIMIT 1');
            $stmt->bindValue(':post_id', $post_id);

            $stmt->execute();

            $currentLikes = $stmt->fetchColumn();

            $newLikes = $currentLikes-1;

            // updating the posts table
            $stmt = $pdo->prepare('UPDATE posts SET likes = :newLikes WHERE post_id = :post_id');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':newLikes', $newLikes);
            $stmt->execute();

            // Updating the likes table to remove the like
            $stmt = $pdo->prepare('DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
        }
        exit;
    }
?>