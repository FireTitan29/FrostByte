<?php 
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

    return (int)$stmt->fetchColumn();
}

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

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findAndDisplayNotifications($user_id) {
    $notifications = getNotifications($user_id);

    foreach ($notifications as $notification) {
        $whoDidIt = getUserDetailsID($notification['who_did_it']);
        $firstname = $whoDidIt['firstname'];
        include "php/components/SingleNotification.php";
    }
}

function addNotification($user_id, $whoDidIt, $message) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        INSERT INTO notifications (user_id, who_did_it, message, is_read, created_at) 
        VALUES (:user_id, :who, :message, 0, NOW())');
        
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':who', $whoDidIt, PDO::PARAM_INT);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->execute();
}

?>