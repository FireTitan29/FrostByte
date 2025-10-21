<?php 
    session_start();
    include '../library/database.php';
    include '../library/notifications.php';

    // Controller: SendMessage
    // 1. Check if conversation already exists between the two users
    // 2. If not, create a new conversation
    // 3. Reset "is_last_message" flag on previous messages
    // 4. Insert the new message as the latest one
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdo = connectToDatabase();
        
        $user_id = $_POST['user_id'];
        $receiver_id = $_POST['receiver_id'];
        $textMessage = $_POST['textmessage'];

        // seeing if a conversation exists
        $stmt = $pdo->prepare('SELECT conversation_id 
                       FROM conversations 
                       WHERE (user1_id = :user1 AND user2_id = :user2) 
                          OR (user1_id = :user2 AND user2_id = :user1)
                       LIMIT 1');
        $stmt->bindValue(':user1', $user_id);
        $stmt->bindValue(':user2', $receiver_id);
        $stmt->execute();

        $conversation_id = $stmt->fetchColumn();

        // if no conversation, we create one
        if (!$conversation_id) {
            $stmt = $pdo->prepare('INSERT INTO conversations (user1_id, user2_id) 
                                VALUES (:user1, :user2)');

            $stmt->bindValue(':user1', $user_id);
            $stmt->bindValue(':user2', $receiver_id);

            $stmt->execute();
            $conversation_id = $pdo->lastInsertId();
        }

        // Setting the previous last message to 0
        $stmt = $pdo->prepare('UPDATE messages 
                       SET is_last_message = 0 
                       WHERE conversation_id = :conversation_id 
                         AND is_last_message = 1');

        $stmt->bindValue(':conversation_id',$conversation_id);

        $stmt->execute();
        
        // inserting the new message
        $stmt = $pdo->prepare('INSERT INTO messages 
        (conversation_id, sender_id, text_body, created_at, is_read, is_last_message) 
        VALUES (:conversation_id, :sender, :textmessage, NOW(), 0, 1)');

        $stmt->bindValue(':conversation_id', $conversation_id);
        $stmt->bindValue(':sender', $user_id);
        $stmt->bindValue(':textmessage', $textMessage);
        $stmt->execute();

        addNotification($receiver_id, $user_id, 'sent you a message');

    } else {
         // Block direct URL access (no POST data), send user back to homepage (index)
        header("Location: ../../index.php");
        exit;
    }
?>