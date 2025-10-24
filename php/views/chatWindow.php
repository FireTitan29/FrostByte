<!-- Chat window view for private one-to-one messaging -->
<?php
// Security check: Prevent direct access to the file
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
// Validation: Prevent self-messaging or tampered links
if (cleanHTML($_GET['sendto']) == $_SESSION['user']['id'] || !isset($_GET['sendto']) || empty($_GET['sendto'])) {
    header("Location: index.php?view=messages");
    exit;
}
// Fetch recipient details and mark chat as read
$thisUser = getUserDetailsID($_GET['sendto']);
readchat($_SESSION['user']['id'], getConversationId($_GET['sendto'], $_SESSION['user']['id']));
?>
<!-- Receiver info header with back link and profile details -->
<div class="reciever-info-window">
    <a class="link-back-messages" href="index.php?view=messages">Back to Inbox</a>
    <a class="goToProfile" href="index.php?view=profileview&user=<?php echo cleanHTML($_GET['sendto'])?>">
        <div class="picture-info-Profile">
            <img class="profile-picture-message" src="<?php echo cleanHTML($thisUser['profile_pic'])?>" alt="profile-picture">
                <div class="name-bio-Profile">
                    <div class="profile-name-bio-message">
                        <div class="user-info">
                            <h3 class="username-chat"><?php echo cleanHTML($thisUser['firstname'] . ' ' . $thisUser['surname'])?></h3>
                            <span class="gender-Profile">(<?php echo cleanHTML($thisUser['gender'])?>)</span><br>
                            <span class="email-Profile"><?php echo cleanHTML($thisUser['email'])?></span><br>
                        </div>
                    </div>
            </div>
        </div>
    </a>
</div>
<!-- Chat area showing past messages or "No Messages Yet" -->
<div class="chat-area-holder">
    <div class="chat-area">
        <?php if (findAndDisplayMessages($_GET['sendto'], $_SESSION['user']['id'])): ?>
        <?php else: ?>
            <p class="no-messages">No Messages Yet...</p>
        <?php endif; ?>
    </div>
    <!-- Message input form with hidden sender/receiver IDs and message box -->
    <div>
        <div class="sendWrapper">
            <form class="searchBarForm" autocomplete="off">
                <!-- Hidden input for sender user ID -->
                <input hidden id="sender" name="sender" value="<?php echo cleanHTML($_SESSION['user']['id']); ?>">
                <!-- Hidden input for receiver user ID -->
                <input hidden id="reciever" name="reciever" value="<?php echo cleanHTML($_GET['sendto']); ?>">
                <!-- Text input for typing the message -->
                <input id="textmessageinput" name="textmessage" type="text" class="sendBarInput" placeholder="Type message to <?php echo cleanHTML($thisUser['firstname'])?>...">
                <!-- Send button triggers sendMessage(event) JavaScript function -->
                <button onclick="sendMessage(event)" class="sendIcon searchIconInside"><img src="icons/Send<?php if ($_SESSION['user']['theme'] === 'dark'){ echo "-Dark";}?>.svg" alt="Send" class="sendIconImage"></button>
            </form>
        </div>
    </div>
</div>