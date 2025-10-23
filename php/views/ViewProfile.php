<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
// Error Handling incase user tampers with link
if (!isset($_GET['user']) || empty($_GET['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!-- If the user clicks their own name, then they are directed to their own profile -->
<?php if ((int)$_GET['user'] === $_SESSION['user']['id']) {
    header('Location: index.php?view=profile');
    exit;
} ?>
<!-- or they are sent to the other user's profile -->
<?php $thisUserID = $_GET['user'];
    $thisUser = getUserDetailsID($thisUserID); 
    $friends = alreadyFriendsCheck($_SESSION['user']['id'], $thisUserID);    
    $requestSent = alreadySentFriendRequest($_SESSION['user']['id'], $thisUserID);    
    ?>
<div class="div-holder-top-profile" style="margin-bottom: 15px;">
    <div class="window-Profile" style="margin-bottom: 0px;">
        <div class="picture-info-Profile">
            <img class="picture-profile" src="<?php echo $thisUser['profile_pic']?>" alt="profile-picture">
            <div class="name-bio-Profile">
                <div class="top-holder-Profile">
                    <div class="user-info">
                        <h3 class="username-Profile"><?php echo cleanHTML($thisUser['firstname'] . ' ' . $thisUser['surname'])?></h3>
                        <span class="gender-Profile">(<?php echo cleanHTML($thisUser['gender'])?>)</span><br>
                        <span class="email-Profile"><?php echo cleanHTML($thisUser['email'])?></span><br>
                    </div>
                </div>
                <p class="bio-Profile"><?php echo cleanHTML($thisUser['profile_bio'])?></p>
            </div>
        </div>
        <!-- Buttons Normal -->
        <div class="buttons-Profile">
            <form method="GET" action="index.php">
                <input hidden name="view" value="chat">
                <input hidden name="sendto" value="<?php echo cleanHTML($thisUserID)?>">
                <button type="submit" class="button-Profile">Message</button>
            </form>
            <?php if (!$friends && !$requestSent): ?>
            <form>
                <input hidden id="friendSender" name="sender" value="<?php echo cleanHTML($_SESSION['user']['id'])?>">
                <input hidden id="friendReceiver" name="receiver" value="<?php echo cleanHTML($thisUserID)?>">
                <button onclick="sendFriendRequest(event)" class="button-Profile">Add Friend</button>
            </form>
            <?php elseif (!$friends && $requestSent): ?>
                <form>
                    <input type="hidden" id="friendSenderPending" name="sender" value="<?php echo cleanHTML($_SESSION['user']['id'])?>">
                    <input type="hidden" id="requestIDPending" name="request" value="<?php echo cleanHTML(getFriendRequestID($_SESSION['user']['id'], $thisUserID))?>">
                    <input type="hidden" id="friendReceiverPending" name="receiver" value="<?php echo cleanHTML($thisUserID)?>">
                    <button onclick="cancelFriendRequest(event)" type="button" class="button-Profile">Pending</button>
                </form>
            <?php elseif ($friends): ?>
                <form>
                    <input type="hidden" id="friendSenderDelete" name="sender" value="<?php echo cleanHTML($_SESSION['user']['id'])?>">
                    <input type="hidden" id="friendReceiverDelete" name="receiver" value="<?php echo cleanHTML($thisUserID)?>">
                    <button onclick="unFriend(event)" type="button" class="button-Profile">Friends</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if(!findAndDisplayPosts($_GET['user'])): ?>
  <h3 class="noPosts-Text">No Posts Yet...</h3>
<?php endif; ?> 