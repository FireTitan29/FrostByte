<?php 
// Library: friendRequests.php
// Purpose: Provides functions to manage friend requests in the application
// - getFriendRequests(): Retrieves all friend requests for a given user
// - countFriendRequests(): Counts the number of friend requests for a user
// - alreadyFriendsCheck(): Checks if two users are already friends
// - alreadySentFriendRequest(): Checks if a friend request has already been sent
// - getFriendRequestID(): Retrieves the ID of a friend request between two users
// - sendFriendRequest(): Sends a friend request if no existing request or friendship exists
// - findAndDisplayFriendRequests(): Finds and displays all friend requests for a user
// - removeFriendRequest(): Removes a friend request from the database by its ID

// Retrieves all friend requests for a given user.
function getFriendRequests($user_id) {
        $pdo = connectToDatabase();
    $stmt = $pdo->prepare('
        SELECT * 
        FROM friend_requests 
        WHERE user_id = :userid
        ORDER BY created_at DESC
    ');
    $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    closeDatabase($pdo);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Counts the number of friend requests for a given user.
function countFriendRequests($user_id) {
    $pdo = connectToDatabase();

    $stmt = $pdo->prepare('
        SELECT COUNT(*) 
        FROM friend_requests 
        WHERE user_id = :userid 
    ');
    $stmt->bindValue(':userid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    closeDatabase($pdo);
    return (int)$stmt->fetchColumn();
}

// Checks if two users are already friends.
function alreadyFriendsCheck($sender_id, $receiver_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT user1_id, user2_id FROM friends WHERE (user1_id = :sender AND user2_id = :receiver) 
    OR (user2_id = :sender AND user1_id = :receiver)');

    $stmt->bindValue(':sender', $sender_id, PDO::PARAM_INT);
    $stmt->bindValue(':receiver', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $friends = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDatabase($pdo);

    if ($friends) {
        return true;
    }

    return false;
}

// Checks if a friend request has already been sent between two users.
function alreadySentFriendRequest($sender_id, $receiver_id) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT sender_id, user_id FROM friend_requests WHERE (sender_id = :sender AND user_id = :receiver) 
    OR (user_id = :sender AND sender_id = :receiver)');

    $stmt->bindValue(':sender', $sender_id, PDO::PARAM_INT);
    $stmt->bindValue(':receiver', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $requestSent = $stmt->fetch(PDO::FETCH_ASSOC);
    closeDatabase($pdo);

    if ($requestSent) {
        return true;
    }

    return false;
}

// Retrieves the ID of a friend request between two users.
function getFriendRequestID($user1, $user2) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare('SELECT request_id FROM friend_requests WHERE (sender_id = :user1 AND user_id = :user2) 
    OR (user_id = :user2 AND sender_id = :user1) LIMIT 1');

    $stmt->bindValue(':user1', $user1, PDO::PARAM_INT);
    $stmt->bindValue(':user2', $user2, PDO::PARAM_INT);
    $stmt->execute();
    $requestID = $stmt->fetchColumn();
    closeDatabase($pdo);

    return $requestID ;
}

// Sends a friend request from one user to another if no existing request or friendship exists.
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
            closeDatabase($pdo);
            return true;
    } else {
        // Friend Request already exists OR They are already friends
        return false;
    }
}

// Finds and displays all friend requests for a user by including a component for each.
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

// Removes a friend request from the database by its ID.
function removeFriendRequest($requestId) {
    $pdo = connectToDatabase();

    $stmt = $pdo->prepare('DELETE FROM friend_requests WHERE request_id = :id');
    $stmt->bindValue(':id', $requestId, PDO::PARAM_INT);
    $stmt->execute();
    closeDatabase($pdo);
}


?>