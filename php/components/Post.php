<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<!-- Post Component Blueprint 
     - Defines how a single post appears on the timeline/profile.
     - Populated dynamically via findAndDisplayPosts(). -->
<div class="window-Profile post" id="post-<?php echo cleanHTML($post_id); ?>">
    <div class="mainBlock-Post">
        <!-- Profile picture: links to the user's profile -->
        <a href="index.php?view=profileview&user=<?php echo cleanHTML($user_id)?>"><img class="smallProfileIcon-Post" src="<?php echo cleanHTML($profilePicture) ?>" alt="PostIcon"></a>

        <div>
             <!-- Username: links to the user's profile -->
            <p class="userName-Post"><a href="index.php?view=profileview&user=<?php echo urlencode($user_id) ?>"><?php echo cleanHTML($userName)?></a></p>

             <!-- Caption text -->
            <p class="post-Text"><?php echo cleanHTML($caption)?></p>

            <!-- Post image: only rendered if $imageName is not empty -->
            <div class="image-holder-Profile">
                <?php if ($imageName !== ''): ?>
                    <img class="post-image-Profile" src="<?php echo cleanHTML($imageName)?>" alt="Post_Image">
                <?php endif; ?>
            </div>

            <!-- Footer: timestamp and like button -->
            <div class="bottomHolder-Post">
                <p class="timeStamp-Post"><?php echo cleanHTML($timeStamp)?></p>

                <!-- Like system: counter, hidden submit, and clickable heart icon -->
                <div class="like-Holder-Post" id="post-<?php echo cleanHTML($post_id); ?>-holder" data-liked="<?php echo cleanHTML($likeBool) ? 'true' : 'false'; ?>">
                        <input hidden type="submit" name="like-button-id" id="like-button-<?php echo cleanHTML($post_id); ?>" value="<?php echo cleanHTML($post_id);?>">
                        <p class="like-Counter-Post" id="post-<?php echo cleanHTML($post_id); ?>-counter"><?php echo cleanHTML($likesCount)?></p>
                        
                        <label onclick="likePost(<?php echo cleanHTML($post_id); ?>)" for="like-button-<?php echo cleanHTML($post_id);?>">
                        <img id="like-heart-<?php echo cleanHTML($post_id); ?>" src="icons/<?php if (cleanHTML($likeBool)) { echo "Like-Active.svg"; } else {echo "Like.svg";} ?>"  class="like-Post" alt="like"></label>
                </div>
            </div>
        </div>
    </div>
</div>