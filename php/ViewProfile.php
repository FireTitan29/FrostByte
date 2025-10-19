<!-- If the user clicks their own name, then they are directed to their own profile -->
<?php if ((int)$_GET['user'] === $_SESSION['user']['id']) {
    header('Location: index.php?view=profile');
    exit;
} ?>
<!-- or they are sent to the other user's profile -->
<?php $thisUserID = $_GET['user'];
    $thisUser = getUserDetailsID($thisUserID); ?>
<div class="div-holder-top-profile" style="margin-bottom: 15px;">
    <div class="window-Profile" style="margin-bottom: 0px;">
        <div class="picture-info-Profile">
            <img class="picture-profile" src="<?php echo $thisUser['profile_pic']?>" alt="profile-picture">
            <div class="name-bio-Profile">
                <div class="top-holder-Profile">
                    <div class="user-info">
                        <h3 class="username-Profile"><?php echo htmlspecialchars($thisUser['firstname'] . ' ' . $thisUser['surname'])?></h3>
                        <span class="gender-Profile">(<?php echo htmlspecialchars($thisUser['gender'])?>)</span><br>
                        <span class="email-Profile"><?php echo htmlspecialchars($thisUser['email'])?></span><br>
                    </div>
                </div>
                <p class="bio-Profile"><?php echo htmlspecialchars($thisUser['profile_bio'])?></p>
            </div>
        </div>
        <!-- Buttons Normal -->
        <div class="buttons-Profile">
            <form method="GET" action="index.php">
                <input hidden name="view" value="chat"></input>
                <input hidden name="sendto" value="<?php echo $thisUserID?>"></input>
                <button type="submit" class="button-Profile">Message</button>
            </form>
            <form>
                <button type="submit" class="button-Profile">Add Friend</button>
            </form>
        </div>
    </div>
</div>
<?php if(!findAndDisplayPosts($_GET['user'])): ?>
  <h3 class="noPosts-Text">No Posts Yet...</h3>
<?php endif; ?> 

<script src="js/like_post.js"></script>