<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
// Error Handling incase user tampers with link
if (cleanHTML($_GET['sendto']) == $_SESSION['user']['id'] || !isset($_GET['sendto']) || empty($_GET['sendto'])) {
    header("Location: index.php?view=messages");
    exit;
}
?>
<?php $thisUser = getUserDetailsID($_GET['sendto']);
    readchat($_SESSION['user']['id'], getConversationId($_GET['sendto'], $_SESSION['user']['id']));
?>
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
<div class="chat-area-holder">
    <div class="chat-area">
        <?php if (findAndDisplayMessages($_GET['sendto'], $_SESSION['user']['id'])): ?>
        <?php else: ?>
            <p class="no-messages">No Messages Yet...</p>
        <?php endif; ?>
    </div>
    <div>
        <div class="sendWrapper">
            <form class="searchBarForm" autocomplete="off">
                <input hidden id="sender" name="sender" value="<?php echo cleanHTML($_SESSION['user']['id']); ?>">
                <input hidden id="reciever" name="reciever" value="<?php echo cleanHTML($_GET['sendto']); ?>">
                <input id="textmessageinput" name="textmessage" type="text" class="sendBarInput" placeholder="Type message to <?php echo cleanHTML($thisUser['firstname'])?>...">
                <button onclick="sendMessage(event)" class="sendIcon searchIconInside"><img src="icons/Send<?php if ($_SESSION['user']['theme'] === 'dark'){ echo "-Dark";}?>.svg" alt="Send" class="sendIconImage"></button>
            </form>
        </div>
    </div>
</div>