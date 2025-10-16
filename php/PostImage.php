<div class="window-Profile" id="post-<?php echo $post_id; ?>">
    <div class="mainBlock-Post">
        <img class="smallProfileIcon-Post" src="<?php echo $profilePicture ?>" alt="PostIcon">
        <div>
            <p class="userName-Post"><?php echo htmlspecialchars($userName)?></p>
            <p class="post-Text"><?php echo htmlspecialchars($caption)?></p>
            <div class="image-holder-Profile">
                <img class="post-image-Profile" src="<?php echo htmlspecialchars($imageName)?>" alt="<?php echo htmlspecialchars($imageName)?>">
            </div>
            <div class="bottomHolder-Post">
                <p class="timeStamp-Post"><?php echo htmlspecialchars($timeStamp)?></p>
                <form method="POST">
                    <div class="like-Holder-Post">
                        <input hidden onchange="this.form.submit()" type="checkbox" name="like-button-id" id="like-button-<?php echo htmlspecialchars($post_id); ?>" value="<?php echo htmlspecialchars($post_id);?>">
                        <p class="like-Counter-Post"><?php echo htmlspecialchars($likesCount)?></p><label for="like-button-<?php echo htmlspecialchars($post_id);?>"><img src="icons/<?php if ($likeBool) { echo "Like-Active.svg"; } else {echo "Like.svg";} ?>" class="like-Post" alt="like"></label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
