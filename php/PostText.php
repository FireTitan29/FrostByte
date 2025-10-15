<div class="window-Profile">
    <div class="mainBlock-Post">
        <img class="smallProfileIcon-Post" src="<?php echo htmlspecialchars($profilePicture) ?>" alt="PostIcon">
        <div>
            <p class="userName-Post"><?php echo htmlspecialchars($userName)?></p>
            <p class="post-Text"><?php echo htmlspecialchars($caption)?></p>
            <div class="image-holder-Profile">
            </div>
            <div class="bottomHolder-Post">
                <p class="timeStamp-Post"><?php echo htmlspecialchars($timeStamp)?></p>
                <div class="like-Holder-Post">
                    <p class="like-Counter-Post"><?php echo htmlspecialchars($likesCount)?></p><img src="icons/Like.svg" class="like-Post" alt="like">
                </div>
            </div>
        </div>
    </div>
</div>