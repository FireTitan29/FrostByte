<div class="window-Profile">
    <div class="picture-info-Profile">
        <img class="picture-profile" src="<?php echo $_SESSION['user']['profile_pic'] ?>" alt="profile-picture">
        <div class="name-bio-Profile">
            <div class="top-holder-Profile">
                <div class="user-info">
                    <h3 class="username-Profile">Tyler Duggan</h3>
                    <span class="email-Profile">tylersduggan@gmail.com</span>
                </div>
                <div class="settings-holder">
                    <button class="button-Profile settings-button"><img src="icons/Settings.svg" alt="Settings" class="settings-icon"></button>
                </div>
            </div>
            <p class="bio-Profile">Hello this is my bio and I am very excited to be doing this! This is my profile, thank you a very much!</p>
        </div>
    </div>
    <div class="buttons-Profile">
        <button class="button-Profile">Edit Profile</button>
        <form method="get" action="index.php">
            <input type="hidden" name="view" value="logout">
        <button type="submit" class="button-Profile">Logout</button>
</form>
    </div>
</div>

  <?php includePost('Tyler Duggan','test-image.jpg', 'Very excited to see if this will work, I guess we shall find out!', 3) ?>
  <?php includePost('Tyler Duggan','test-image-2.jpg', 'Yay, this tastes great! Super nice!', 14) ?>
  <?php includePost('Tyler Duggan','test-image-3.jpg', 'Another day, another icecream... this is great!', 1) ?>
  <?php includePost('Tyler Duggan','test-image-4.jpg', 'Full of ice cream now... no more dairy!', 8) ?>
  <?php includePost('Tyler Duggan','', 'I ran out of stuff to say', 0) ?>

