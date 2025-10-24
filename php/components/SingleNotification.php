<!-- Component: SingleNotification.php
Purpose: Displays a single notification item in the dropdown/panel
    - Chooses icon type based on the notification message content
    - Shows the triggering user’s firstname alongside the notification text
    - Includes the timestamp of when the notification was created
    - Uses cleanHTML() to escape dynamic output for security -->
    
<div class="notification-text-holder">
    <div class="message-icon-holder-notification">
        <img src="icons/<?php if (str_contains(cleanHTML($notification['message']),'message')) { echo 'Message-Notify';} else { echo 'Like-Active';}?>.svg" class="notifications-icon" alt="like">
        <span><span class="notification-name"><?php echo cleanHTML($firstname)?></span> <?php echo cleanHTML($notification['message'])?></span><br>
    </div>
    <span class="timestamp-notification"><?php echo cleanHTML($notification['created_at'])?></span>
</div>