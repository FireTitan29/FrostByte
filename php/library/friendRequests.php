<?php 
// This function looks through the DB to see if they users are friends or not
function alreadyFriendsCheck($sender_id, $receiver_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT user1_id, user2_id FROM friends WHERE (user1_id = :sender AND user2_id = :receiver) 
    OR (user2_id = :sender AND user1_id = :receiver)');

    $stmt->bindValue(':sender', $sender_id, PDO::PARAM_INT);
    $stmt->bindValue(':receiver', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $friends = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($friends) {
        return true;
    }

    return false;
}

// This function looks through the DB to see if there is a request
function alreadySentFriendRequest($sender_id, $receiver_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT sender_id, user_id FROM friend_requests WHERE (sender_id = :sender AND user_id = :receiver) 
    OR (user_id = :sender AND sender_id = :receiver)');

    $stmt->bindValue(':sender', $sender_id, PDO::PARAM_INT);
    $stmt->bindValue(':receiver', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $requestSent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($requestSent) {
        return true;
    }

    return false;
}

// This function returns the id of a friend request
function getFriendRequestID($user1, $user2) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT request_id FROM friend_requests WHERE (sender_id = :user1 AND user_id = :user2) 
    OR (user_id = :user2 AND sender_id = :user1) LIMIT 1');

    $stmt->bindValue(':user1', $user1, PDO::PARAM_INT);
    $stmt->bindValue(':user2', $user2, PDO::PARAM_INT);
    $stmt->execute();
    $requestID = $stmt->fetchColumn();

    return $requestID ;
}

function sendFriendRequest($sender_id, $receiver_id) {
    $pdo = connectToDatabase();

    // Look to see if either person has already sent a request before
    $requestSent = alreadySentFriendRequest($sender_id, $receiver_id);

    // Look to see both parties are friends already
    $friends = alreadyFriendsCheck($sender_id, $receiver_id);

    // If they aren't friends and there is no request pending, add the request to the DB
    if (!$requestSent && !$friends) {
        $stmt = $pdo->prepare('INSERT INTO friend_requests (sender_id, user_id, created_at) VALUES (:sender, :receiver, NOW())');
            $stmt->bindValue(':sender', $sender_id, PDO::PARAM_INT);
            $stmt->bindValue(':receiver', $receiver_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

    } else {
        // Friend Request already exists OR They are already friends
        return false;
    }
}


function findAndDisplayFriendRequests($user_id) {
    $requests = getFriendRequests($user_id);

    foreach ($requests as $request) {
        $requestId = $request['request_id'];
        $senderId = $request['sender_id'];
        $sender = getUserDetailsID($senderId);
        $senderName = $sender['firstname'];
        $timeStamp = $request['created_at'];
        include "php/components/SingleFriendRequest.php";
    }
}

function removeFriendRequest($requestId) {
    $pdo = connectToDatabase();

    $stmt = $pdo->prepare('DELETE FROM friend_requests WHERE request_id = :id');
    $stmt->bindValue(':id', $requestId, PDO::PARAM_INT);
    $stmt->execute();
}



?>