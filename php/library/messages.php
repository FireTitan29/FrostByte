<?php
function getConversationId($sender_id, $receiver_id) {
    $pdo = connectToDatabase();

    $stmt = $pdo->prepare('
        SELECT conversation_id 
        FROM conversations
        WHERE (user1_id = :sender AND user2_id = :receiver)
        OR (user1_id = :receiver AND user2_id = :sender)
        LIMIT 1
    ');
    $stmt->bindValue(':sender', $sender_id);
    $stmt->bindValue(':receiver', $receiver_id);
    $stmt->execute();

    return $stmt->fetchColumn();
}

function includeMessage($sender_id, $textBody, $timeStamp, $is_read, $addDateLine) {
    $date = date('d M Y', strtotime($timeStamp));
    $timeStamp = date('H:i', strtotime($timeStamp));

    $user_id = $_SESSION['user']['id'];
    if ($sender_id === $user_id) {
        $sent = true;
    } else {
        $sent = false;
    }
    include "php/components/message-bubble.php";
}

function findAndDisplayMessages($send_to, $user_id)
{
    $chatID = getConversationId($user_id, $send_to);
    if (!$chatID) {
        return false;
    } else {
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare('SELECT * FROM messages WHERE conversation_id = :chat_id');
        $stmt->bindValue(':chat_id',$chatID);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lastDate = null;

        foreach ($messages AS $message) {
            $addDateLine = false;

            $currentDate = date('d M Y', strtotime($message['created_at']));

            if ($lastDate !== $currentDate) {
                $addDateLine = true;
                $lastDate = $currentDate;
            }
            includeMessage($message['sender_id'], $message['text_body'],
                $message['created_at'], $message['is_read'], $addDateLine);
        }
        return true;
    }
}

function findAndDisplayActiveChats($user_id) {
$pdo = connectToDatabase();
// Get all conversations this user is part of
$stmt = $pdo->prepare('
    SELECT conversation_id, user1_id, user2_id 
    FROM conversations 
    WHERE user1_id = :user_id OR user2_id = :user_id
');
$stmt->bindValue(':user_id', $user_id);
$stmt->execute();
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$conversations) {
    return false;
} else {
    foreach ($conversations as $conv) {
        // Get the last message for this conversation
        $stmt = $pdo->prepare('
            SELECT * FROM messages 
            WHERE conversation_id = :chat_id 
            ORDER BY created_at DESC 
            LIMIT 1
        ');
        $stmt->bindValue(':chat_id', $conv['conversation_id']);
        $stmt->execute();
        $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lastMessage) {
            $rawTime = $lastMessage['created_at'];
            $messageTime = strtotime($rawTime);

            // check if it's today, if it is, show time, otherwise, show date
            if (date('Y-m-d') === date('Y-m-d', $messageTime)) {
                $formattedTime = date('H:i A', $messageTime);
            } else {
                $formattedTime = date('d M Y', $messageTime);
            }
            
            $profilePicture = '';

            // Figure out who the "other" user is
            if ($conv['user1_id'] == $user_id) {
                $sendTo = getUserDetailsID($conv['user2_id']);
                $profilePicture = $sendTo['profile_pic'];
                $thisUserID = $conv['user2_id'];
            } else {
                $sendTo = getUserDetailsID($conv['user1_id']);
                $profilePicture = $sendTo['profile_pic'];
                $thisUserID = $conv['user1_id'];
            }
            $textBody = htmlspecialchars($lastMessage['text_body']);
            $senderId = htmlspecialchars($lastMessage['sender_id']);
            // Pass message + user info to your include
            include 'php/components/SingleMessage.php';
        }
    }
}
}
?>