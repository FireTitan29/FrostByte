<!-- 
    ViewProfile.php
    This file displays the profile page of a user other than the currently logged-in user.
    It prevents direct access, handles redirection if a user tries to view their own profile via this page,
    fetches the profile data of the specified user, shows profile details including friend count and bio,
    and provides action buttons to message, add friend, cancel friend request, or unfriend.
    If the user has no posts, it displays a "No Posts Yet..." message.
-->
    
<!-- Prevent people from accessing the file directly -->
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
<!-- Handle self-profile redirect -->
<?php if ((int)$_GET['user'] === $_SESSION['user']['id']) {
    header('Location: index.php?view=profile');
    exit;
} ?>
<!-- Fetch user data and friendship status -->
<?php $thisUserID = $_GET['user'];
    $thisUser = getUserDetailsID($thisUserID); 
    $friends = alreadyFriendsCheck($_SESSION['user']['id'], $thisUserID);    
    $requestSent = alreadySentFriendRequest($_SESSION['user']['id'], $thisUserID);    
    ?>
<div class="div-holder-top-profile" style="margin-bottom: 15px;">
    <div class="window-Profile" style="margin-bottom: 0px;">
        <div class="picture-info-Profile">
            <!-- Profile header info: picture, name, gender, email -->
            <img class="picture-profile" src="<?php echo $thisUser['profile_pic']?>" alt="profile-picture">
            <div class="name-bio-Profile">
                <div class="top-holder-Profile">
                    <div class="user-info">
                        <h3 class="username-Profile"><?php echo cleanHTML($thisUser['firstname'] . ' ' . $thisUser['surname'])?></h3>
                        <span class="gender-Profile">(<?php echo cleanHTML($thisUser['gender'])?>)</span><br>
                        <span class="email-Profile"><?php echo cleanHTML($thisUser['email'])?></span><br>
                    </div>
                    <!-- Friend count display -->
                    <div style="display: flex; margin-right: 5px">
                    <img src="icons/FriendsCount.svg" alt="FriendsIcon" class="friendsIcon">
                    <div class="friends-Holder">
                        <span class="friends-counter"><?php echo cleanHTML(countFriends($thisUserID)) ?></span>
                        <span class="friends-text">Friends</span>
                    </div>
                    </div>
                </div>
                <!-- Bio section -->
                <p class="bio-Profile"><?php echo cleanHTML($thisUser['profile_bio'])?></p>
            </div>
        </div>
        <!-- Action buttons: Message, Add Friend, Pending, Friends -->
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
<!-- Display posts or "No Posts Yet" message -->
<?php if(!findAndDisplayPosts($_GET['user'])): ?>
  <h3 class="noPosts-Text">No Posts Yet...</h3>
<?php endif; ?> 