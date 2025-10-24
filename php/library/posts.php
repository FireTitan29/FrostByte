<?php 

// Library: posts.php
// Purpose: Functions for handling post-related logic
// - didUserLike(): Checks if a specific user has liked a given post
// - getUserOfPost(): Retrieves the owner (user_id) of a post
// - addPostToDB(): Inserts a new post record
// - includePost(): Decides the correct component to render (with/without image)
// - findAndDisplayPosts(): Fetches and displays posts (all or by user), ordered by latest


// Checks if a user has liked a specific post.
// Returns: true if liked, false otherwise.
function didUserLike($user_id, $post_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT 1 FROM post_likes WHERE post_id = :post_id AND user_id = :user_id LIMIT 1');
    $stmt->bindValue(':post_id', $post_id);
    $stmt->bindValue(':user_id', $user_id);

    $stmt->execute();
    $result =  $stmt->fetchColumn();
    closeDatabase($pdo);

    if (!$result) {
        return false;
    }

    return true;

}

// Finds the userID of the person who posted the post via the post's ID
function getUserOfPost($post_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE post_id = :post_id');
    $stmt->bindValue(':post_id', $post_id);
    $stmt->execute();
    $result =  $stmt->fetchColumn();
    closeDatabase($pdo);

    return $result;
}

// This function adds the posts to the DB using a SQL insert
function addPostToDB($caption, $imagePath = '') {
    // connecting to the DB
    $pdo = connectToDatabase();

    $stmt = $pdo->prepare(
    'INSERT INTO posts (user_id, caption, image_path)
    VALUES (:user_id, :caption, :image_path)');

    // stopping SQL injection
    $stmt->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
    $stmt->bindValue(':caption', $caption, PDO::PARAM_STR);
    $stmt->bindValue(':image_path', $imagePath, PDO::PARAM_STR);
    
    $stmt->execute();
    closeDatabase($pdo);
}

// this function includes a post depending on whether it has an image
// or not. It puts it in the correct format.
function includePost($userName, $profilePicture, $timeStamp, $post_id ,$imageName = '', $caption = '', $likesCount = 0) {
    $likeBool = didUserLike($_SESSION['user']['id'], $post_id);
    $user_id = getUserOfPost($post_id);
    include "php/components/Post.php";
}

// This function finds all posts relating to a user / all users
// and then displays them on the page
function findAndDisplayPosts($user_id = '')
{

    // connecting to the DB
    $pdo = connectToDatabase();
    
    // This is the part where we see if we are on the timeline (select *), or on the user's profile (select where user_id))
    $stmt = '';   
    if ($user_id === '') {
        $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC');
    } else {
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC');
        $stmt->bindValue(':user_id', $user_id);
    }
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    closeDatabase($pdo);
    
    // After we fetch them all, only if there are posts to be displayed, do we display them
    // otherwise we return false
    if (sizeof($result) > 0) {
        // This is now the part when the posts are actually created
        foreach ($result AS $post) {
            $user = getUserDetailsID($post['user_id']);
            $fullName = $user['firstname'] . ' ' . $user['surname'];
            includePost($fullName, $user['profile_pic'], $post['created_at'], $post['post_id'], $post['image_path'], $post['caption'], $post['likes']);
        }
        return true;
    } else {
        return false;
    }
}

?>