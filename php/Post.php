<div class="window-Profile" id="post-<?php echo $post_id; ?>">
    <div class="mainBlock-Post">
        <img class="smallProfileIcon-Post" src="<?php echo $profilePicture ?>" alt="PostIcon">
        <div>
            <p class="userName-Post"><?php echo htmlspecialchars($userName)?></p>
            <p class="post-Text"><?php echo htmlspecialchars($caption)?></p>
            <div class="image-holder-Profile">
                <?php if ($imageName !== ''): ?>
                    <img class="post-image-Profile" src="<?php echo htmlspecialchars($imageName)?>" alt="<?php echo htmlspecialchars($imageName)?>">
                <?php endif; ?>
            </div>
            <div class="bottomHolder-Post">
                <p class="timeStamp-Post"><?php echo htmlspecialchars($timeStamp)?></p>
                <div class="like-Holder-Post" id="post-<?php echo $post_id; ?>-holder" data-liked="<?php echo $likeBool ? 'true' : 'false'; ?>">
                        <input hidden type="submit" name="like-button-id" id="like-button-<?php echo htmlspecialchars($post_id); ?>" value="<?php echo htmlspecialchars($post_id);?>">
                        <p class="like-Counter-Post" id="post-<?php echo $post_id; ?>-counter"><?php echo htmlspecialchars($likesCount)?></p><label onclick="likePost(<?php echo $post_id; ?>)" for="like-button-<?php echo htmlspecialchars($post_id);?>"><img id="like-heart-<?php echo $post_id; ?>" src="icons/<?php if ($likeBool) { echo "Like-Active.svg"; } else {echo "Like.svg";} ?>" class="like-Post" alt="like"></label>
                </div>
            </div>
        </div>
    </div>
</div>