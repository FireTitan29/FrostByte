<div class="notification-text-holder" id="friendRequest_<?php echo cleanHTML($requestId)?>">
    <div class="message-icon-holder-notification">
        <img src="<?php echo cleanHTML($sender['profile_pic'])?>" class="friendRequest-icon" alt="like">
        <span><span class="notification-name"><?php echo cleanHTML($senderName)?></span> wants to be friends</span><br>
    </div>
    <form class="friendRequestForm" method="POST">
        <input id="requestSender" type="hidden" name="sender_id" value="<?php echo cleanHTML($senderId)?>">
        <input id="user" type="hidden" name="user_id" value="<?php echo cleanHTML($_SESSION['user']['id']); ?>">
        <input id="requestID" type="hidden" name="request_id" value="<?php echo cleanHTML($requestId); ?>">
        <button onclick="outcomeFriendRequest(event, 'accept')" type="button" class="friendRequestButton acceptRequest">Accept</button>
        <span style="margin-right: 8px;"></span>
        <button onclick="outcomeFriendRequest(event, 'decline')" type="button" class="friendRequestButton declineRequest">Decline</button>
    </form>
    <span style="margin-bottom: 5px;"></span>
    <span class="timestamp-notification"><?php echo cleanHTML($timeStamp)?></span>
</div>
