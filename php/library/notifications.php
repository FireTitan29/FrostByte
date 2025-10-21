<?php 
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