<div class="notification-text-holder">
    <div class="message-icon-holder-notification">
        <img src="icons/<?php if (str_contains($notification['message'],'message')) { echo 'Message-Notify';} else { echo 'Like-Active';}?>.svg" class="notifications-icon" alt="like">
        <span><span class="notification-name"><?php echo htmlspecialchars($firstname)?></span> <?php echo htmlspecialchars($notification['message'])?></span><br>
    </div>
    <span class="timestamp-notification"><?php echo htmlspecialchars($notification['created_at'])?></span>
</div>