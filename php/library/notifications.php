<?php
// Library: notifications.php
// Purpose: Manage user notifications (CRUD, display, count unread, etc)
// - countUnreadNotifications($user_id): Returns the number of unread notifications for a user.
// - getNotifications($user_id): Retrieves all unread notifications for a user.
// - findAndDisplayNotifications($user_id): Finds unread notifications and displays each with user details.
// - addNotification($user_id, $whoDidIt, $message): Adds a new notification for a user.
 
 // Counts the number of unread notifications for a given user
function countUnreadNotifications($user_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        SELECT COUNT(*) 
        FROM notifications 
        WHERE user_id = :userid 
        AND is_read = 0
    ');
    $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    closeDatabase($pdo);
    return (int)$stmt->fetchColumn();
}

 // Retrieves all unread notifications for a given user
function getNotifications($user_id) {
        $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        SELECT * 
        FROM notifications 
        WHERE user_id = :userid AND is_read = 0
        ORDER BY created_at DESC
    ');
    $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    closeDatabase($pdo);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

 // Finds unread notifications and includes display components for each
function findAndDisplayNotifications($user_id) {
    $notifications = getNotifications($user_id);

    foreach ($notifications as $notification) {
        $whoDidIt = getUserDetailsID($notification['who_did_it']);
        $firstname = $whoDidIt['firstname'];
        include "php/components/SingleNotification.php";
    }
}

 // Adds a new notification record for a user
function addNotification($user_id, $whoDidIt, $message) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        INSERT INTO notifications (user_id, who_did_it, message, is_read, created_at) 
        VALUES (:user_id, :who, :message, 0, NOW())');
        
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':who', $whoDidIt, PDO::PARAM_INT);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
    closeDatabase($pdo);
}

?>