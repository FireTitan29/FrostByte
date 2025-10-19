<div class="div-holder-top-profile" style="margin-bottom: 15px;">
    <div class="window-Profile" style="margin-bottom: 0px;">
        <!-- Edit Profile Information -->
        <?php if($view === 'profile' && isset($_GET['edit'])) :?>
            <!-- My solution for making it possible for the user to edit the profile picture
            my clicking on the current profile picture profile -->
            <form method="POST" enctype="multipart/form-data">
            <label for="image">
            <div class="picture-info-Profile">
                <div class="profile-picture-wrapper">
                    <div id="edit-profile-picture-toggle" class="edit-profile-picture-toggle">
                        <img class="picture-profile" src="<?php echo $_SESSION['user']['profile_pic']?>" alt="profile-picture">
                        <div class="overlay-text">Edit Picture</div>
                </div>
                <div class="profile-picture-wrapper">
                    <img id="preview" src="" alt="Preview" class="picture-profile-changed">
                    <div class="overlay-text-preview" id="edit-pic-overlay-preview">Edit Picture</div>
                    <small class="small-error-message"><?php if (isset($errors['image'])){echo $errors['image'];} else {echo "Edit";} ?></small>
                </div> 
                </div>
                </label>

                <!-- The form for submitting the changes -->
                <input type="file" name="image" id="image" hidden accept=".jpg,.jpeg,.png">
                <div class="name-bio-Profile">
                    <div class="top-holder-Profile">
                        <div class="user-info">
                            <small class="small-error-message-profile"><?php if (isset($errors['firstname'])) echo $errors['firstname']; ?></small>
                            <input class="username-Profile-edit" name="firstname" placeholder="First Name" value="<?php if (isset($_POST['firstname'])) {
                                                                                                            echo htmlspecialchars($_POST['firstname']); 
                                                                                                            } else {
                                                                                                                echo htmlspecialchars($_SESSION['user']['firstname']);}?>">

                            <span style="margin-left: 5px;"></span>
                            <small class="small-error-message-profile"><?php if (isset($errors['surname'])) echo $errors['surname']; ?></small>
                            <input class="username-Profile-edit" name="surname" placeholder="Surname" value="<?php if (isset($_POST['surname'])) {
                                                                                                            echo htmlspecialchars($_POST['surname']); 
                                                                                                            } else {
                                                                                                                echo htmlspecialchars($_SESSION['user']['surname']);}?>">
                            <span class="email-Profile"><?php echo htmlspecialchars($_SESSION['user']['email'])?></span>
                        </div>
                    </div>
                    <textarea name="profile_bio" id="profile_bio" class="bio-Profile-edit" rows="3"><?php if (isset($_POST['profile_bio'])) {
                                                                                                            echo htmlspecialchars($_POST['profile_bio']); 
                                                                                                            } else {
                                                                                                                echo htmlspecialchars($_SESSION['user']['profile_bio']);
                                                                                                            }?></textarea>
                    <small class="small-error-message"><?php if (isset($errors['bio'])) echo $errors['bio']; ?></small>
                </div>
            </div>
            <!-- Buttons Edit -->
            <div class="buttons-Profile">
                <input type="hidden" name="view" value="profile">
                <input type="hidden" name="edit" value="complete">
                <button type="submit" class="button-Profile">Save</button>
            </form>
            <form method="GET" action="index.php">
                <input type="hidden" name="view" value="profile">
                <button type="submit" class="button-Profile">Cancel</button>
            </form>
        </div>
        <!-- Normal Profile -->
        <?php else: ?>
        <div class="picture-info-Profile">
            <img class="picture-profile" src="<?php echo $_SESSION['user']['profile_pic']?>" alt="profile-picture">
            <div class="name-bio-Profile">
                <div class="top-holder-Profile">
                    <div class="user-info">
                        <h3 class="username-Profile"><?php echo htmlspecialchars($_SESSION['user']['fullname'])?></h3>
                        <span class="gender-Profile">(<?php echo htmlspecialchars($_SESSION['user']['gender'])?>)</span><br>
                        <span class="email-Profile"><?php echo htmlspecialchars($_SESSION['user']['email'])?></span><br>
                    </div>
                    <div class="settings-holder">
                        <button onclick="slideOutSettings()" class="button-Profile settings-button"><img src="icons/Settings<?php if ($_SESSION['user']['theme'] === 'dark'){ echo "-Dark";}?>.svg" alt="Settings" class="settings-icon"></button>
                    </div>
                </div>
                <p class="bio-Profile"><?php echo htmlspecialchars($_SESSION['user']['profile_bio'])?></p>
            </div>
        </div>
        <!-- Buttons Normal -->
        <div class="buttons-Profile">
            <form method="GET">
                <input type="hidden" name="view" value="profile">
                <input type="hidden" name="edit" value="true">
                <button type="submit" class="button-Profile">Edit Profile</button>
            </form>
            <form method="GET" action="index.php">
                <input type="hidden" name="view" value="logout">
                <button type="submit" class="button-Profile">Logout</button>
            </form>
        </div>
        <?php endif;?>
    </div>
    <div class="settings-window">
        <h3 class="settings-window-heading">Personalise your Experience</h3><br>
        <form class="settings-form">
            <div class="radio-holder-settings-form">
            <label class="radio-label-settings" for="lightmode">
            <input onclick = "changeTheme(this.value)" class="mode-radio" type="radio" name="displaymode" id="lightmode" value="light" <?php if ($_SESSION['user']['theme'] === 'light') echo "checked";?>>
            Light Mode</label>
            <br>
            <label class="radio-label-settings" for="darkmode">
            <input onclick="changeTheme(this.value)" class="mode-radio" type="radio" name="displaymode" id="darkmode" value="dark" <?php if ($_SESSION['user']['theme'] === 'dark') echo "checked";?>>
            Dark Mode</label>
            </div>
        </form>
    </div>
</div>
<?php if(!findAndDisplayPosts($_SESSION['user']['id'])): ?>
  <h3 class="noPosts-Text">No Posts Yet...</h3>
<?php endif; ?> 

<script src="js/like_post.js"></script>