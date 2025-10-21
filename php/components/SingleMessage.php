<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<!-- 
    This component is a single contact/chat preview in the messages tab.
    Each block represents an active chat with another user, showing:
    - Profile picture and basic user info
    - Last message snippet
    - Timestamp of last message
    - "Message" button to open the chat (But the block is also clickable like in whatsapp via using a label)
-->
<?php $unreadMessages = countUnreadMessages(getConversationId($thisUserID, $_SESSION['user']['id']), $_SESSION['user']['id']); ?>

<div class="single-border-Messaging">
<form method="GET" action="index.php">
    <!-- Label wraps the entire contact preview for accessibility -->
    <label for="button-<?php echo $thisUserID?>">
        <div class="single-contact-Messaging">
            <div class="picture-info-Profile">
                
                <!-- Contact's profile picture -->
                <div class="profile-pic-container">
                    <img class="picture-profile-Message" src="<?php echo $profilePicture ?>" alt="profile-picture">
                    <?php if ($unreadMessages != 0): ?>
                        <span class="notification-badge-messages"><?php echo htmlspecialchars($unreadMessages) ?></span>
                    <?php endif?>
                </div>
                <div class="name-bio-Profile">
                    <div class="top-holder-Message">

                        <!-- Contact’s name and email -->
                        <div class="user-info">
                            <h3 class="username-Message"><?php echo htmlspecialchars($sendTo['firstname'] . ' ' . $sendTo['surname'])?></h3>
                            <span class="email-Profile"><?php echo htmlspecialchars($sendTo['email'])?></span>
                        </div>

                        <!-- Hidden form inputs so that when user clicks "Message" it loads chat view -->
                        <input hidden name="view" value="chat"></input>
                        <input hidden name="sendto" value="<?php echo $thisUserID?>"></input>

                        <!-- Button to navigate into chat -->
                        <button id="button-<?php echo $thisUserID?>" type="submit" class="button-Message">Message</button>
                    </div>

                    <!-- Display of the last message and its timestamp -->
                    <div class="lastMessage-Holder">
                        <p class="last-Message"><?php echo $textBody?></p><div class="ts"><p class="timeStamp-Message"><?php echo $formattedTime?></p></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </label>
</form>
</div>
