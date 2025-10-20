<?php
// Because I am going to be connecting to the DB a lot, I wrote this to speed
// that process up a bit, and make the code easier to read.
// All this does it connect to the database, and then return the $pdo if
// the connection was successful. Otherwise it shoots out an error message
function connectToDatabase() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection to the DB failed, here's why: " . $e->getMessage());
    }
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
}

    // Getting User Details using their email
function getUserDetailsEmail($email) {

    $pdo = connectToDatabase();
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

// Getting a user's details using their user ID
function getUserDetailsID($user_id) {

    $pdo = connectToDatabase();
    
    $stmt = $pdo->prepare('SELECT firstname, surname, email, gender, profile_pic, profile_bio FROM users WHERE id = :user_id');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

?>