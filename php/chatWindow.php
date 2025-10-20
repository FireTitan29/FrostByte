<?php $thisUser = getUserDetailsID($_GET['sendto']);?>
<div class="reciever-info-window">
    <a class="link-back-messages" href="index.php?view=messages">Back to Inbox</a>
    <a class="goToProfile" href="index.php?view=profileview&user=<?php echo $_GET['sendto']?>">
        <div class="picture-info-Profile">
            <img class="profile-picture-message" src="<?php echo $thisUser['profile_pic']?>" alt="profile-picture">
                <div class="name-bio-Profile">
                    <div class="profile-name-bio-message">
                        <div class="user-info">
                            <h3 class="username-chat"><?php echo htmlspecialchars($thisUser['firstname'] . ' ' . $thisUser['surname'])?></h3>
                            <span class="gender-Profile">(<?php echo htmlspecialchars($thisUser['gender'])?>)</span><br>
                            <span class="email-Profile"><?php echo htmlspecialchars($thisUser['email'])?></span><br>
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
                <input hidden id="sender" name="sender" value="<?php echo $_SESSION['user']['id']; ?>">
                <input hidden id="reciever" name="reciever" value="<?php echo $_GET['sendto']; ?>">
                <input id="textmessageinput" name="textmessage" type="text" class="sendBarInput" placeholder="Type message to <?php echo $thisUser['firstname'] ?>...">
                <button onclick="sendMessage(event)" class="sendIcon searchIconInside"><img src="icons/Send<?php if ($_SESSION['user']['theme'] === 'dark'){ echo "-Dark";}?>.svg" alt="Send" class="sendIconImage"></button>
            </form>
        </div>
    </div>
</div>

<script>
    // When page loads, go to the bottom of the messages so that the user
    // doesn't have to scroll all the way down.
    const chatArea = document.querySelector('.chat-area');
    chatArea.scrollTop = chatArea.scrollHeight;
    document.getElementById("textmessageinput").value = "";

    function sendMessage(event) {
        event.preventDefault();
        
        let message = document.getElementById("textmessageinput").value;
        let userId = document.getElementById("sender").value;
        let receiverId = document.getElementById("reciever").value;
        
        fetch('php/sendMessage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 
            'user_id=' + encodeURIComponent(userId) +
            '&receiver_id=' + encodeURIComponent(receiverId) +
            '&textmessage=' + encodeURIComponent(message)
        }).then(() => {
        location.reload();});
    }
</script>