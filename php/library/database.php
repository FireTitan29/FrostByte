<?php
// Library: database.php
// Purpose: Centralized functions for DB connections and queries
// - connectToDatabase(): Opens a PDO connection with error handling
// - getUserDetailsEmail(): Fetches user info via email
// - getUserDetailsID(): Fetches user info via ID
// - readChat(): Marks unread messages in a chat as read
// - countUnreadMessages(): Counts unread messages in a conversation

// Opens a PDO connection to the database
// Returns the PDO object on success, or stops execution with an error message if the connection fails
function connectToDatabase() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection to the DB failed, here's why: " . $e->getMessage());
    }
}

// Getting User Details using their email
function getUserDetailsEmail($email) {

    $pdo = connectToDatabase();
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}

// Getting a user's details using their user ID
function getUserDetailsID($user_id) {

    $pdo = connectToDatabase();
    
    $stmt = $pdo->prepare('SELECT firstname, surname, email, gender, profile_pic, profile_bio FROM users WHERE id = :user_id');
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

// When a chat is opened, this reads all of the unread messages in the chat
// that were sent from the other person
function readChat($user_id, $conversation_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('UPDATE messages SET is_read = 1 WHERE sender_id != :userid AND conversation_id = :convo');
    $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':convo', $conversation_id, PDO::PARAM_INT);
    $stmt->execute();
}

// This function counts all the unread messages
// to display a notification in the inbox messages view
function countUnreadMessages($conversation_id, $user_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        SELECT COUNT(*) 
        FROM messages 
        WHERE sender_id != :userid 
          AND conversation_id = :convo 
          AND is_read != 1
    ');
    $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':convo', $conversation_id, PDO::PARAM_INT);
    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

// This function counts all active conversations for a user
function countConversations($user_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        SELECT COUNT(*) 
        FROM conversations 
        WHERE user1_id = :id 
        OR user2_id = :id
    ');
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

?>