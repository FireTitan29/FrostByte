<div class="single-border-Messaging">
<form method="GET" action="index.php">
    <label for="button-<?php echo $thisUserID?>">
    <div class="single-contact-Messaging">
        <div class="picture-info-Profile">
            <img class="picture-profile-Message" src="<?php echo $profilePicture ?>" alt="profile-picture">
            <div class="name-bio-Profile">
                <div class="top-holder-Message">
                    <div class="user-info">
                        <h3 class="username-Message"><?php echo htmlspecialchars($sendTo['firstname'] . ' ' . $sendTo['surname'])?></h3>
                        <span class="email-Profile"><?php echo htmlspecialchars($sendTo['email'])?></span>
                    </div>

                    <input hidden name="view" value="chat"></input>
                    <input hidden name="sendto" value="<?php echo $thisUserID?>"></input>
                    <button id="button-<?php echo $thisUserID?>" type="submit" class="button-Message">Message</button>

                </div>
                <div class="lastMessage-Holder">
                    <p class="last-Message"><?php echo $textBody?></p><div class="ts"><p class="timeStamp-Message"><?php echo $formattedTime?></p></div>
                </div>
            </div>
        </div>
    </div>
    </label>
</form>
</div>
