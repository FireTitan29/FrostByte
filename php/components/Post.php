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
<div class="window-Profile" id="post-<?php echo $post_id; ?>">
    <div class="mainBlock-Post">
        <!-- Profile picture: links to the user's profile -->
        <a href="index.php?view=profileview&user=<?php echo $user_id?>"><img class="smallProfileIcon-Post" src="<?php echo $profilePicture ?>" alt="PostIcon"></a>

        <div>
             <!-- Username: links to the user's profile -->
            <p class="userName-Post"><a href="index.php?view=profileview&user=<?php echo $user_id?>"><?php echo htmlspecialchars($userName)?></a></p>

             <!-- Caption text -->
            <p class="post-Text"><?php echo htmlspecialchars($caption)?></p>

            <!-- Post image: only rendered if $imageName is not empty -->
            <div class="image-holder-Profile">
                <?php if ($imageName !== ''): ?>
                    <img class="post-image-Profile" src="<?php echo htmlspecialchars($imageName)?>" alt="<?php echo htmlspecialchars($imageName)?>">
                <?php endif; ?>
            </div>

            <!-- Footer: timestamp and like button -->
            <div class="bottomHolder-Post">
                <p class="timeStamp-Post"><?php echo htmlspecialchars($timeStamp)?></p>

                <!-- Like system: counter, hidden submit, and clickable heart icon -->
                <div class="like-Holder-Post" id="post-<?php echo $post_id; ?>-holder" data-liked="<?php echo $likeBool ? 'true' : 'false'; ?>">
                        <input hidden type="submit" name="like-button-id" id="like-button-<?php echo htmlspecialchars($post_id); ?>" value="<?php echo htmlspecialchars($post_id);?>">
                        <p class="like-Counter-Post" id="post-<?php echo $post_id; ?>-counter"><?php echo htmlspecialchars($likesCount)?></p>
                        
                        <label onclick="likePost(<?php echo $post_id; ?>)" for="like-button-<?php echo htmlspecialchars($post_id);?>">
                        <img id="like-heart-<?php echo $post_id; ?>" src="icons/<?php if ($likeBool) { echo "Like-Active.svg"; } else {echo "Like.svg";} ?>"  class="like-Post" alt="like"></label>
                </div>
            </div>
        </div>
    </div>
</div>