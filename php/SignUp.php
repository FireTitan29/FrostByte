<div class="mainForm-Holder">
    <form class="window-Form" method="POST" enctype="multipart/form-data">
        <h3 class="mainHeading-Form"><span class="accentColor">Sign Up</span> to FrostByte</h3>
        <h3 class="tagLine-Form"><i>Are you fresh from the freezer?</i></h3><br>
        <h3 class="subHeading-Form">Your Sweet Identity</h3>

        <small class="small-error-message"><?php if (isset($errors['firstname'])) echo $errors['firstname']; ?></small>
        <input class="textInput-Form" type="text" placeholder="First Name" name="firstname" value="<?php echo htmlspecialchars($name) ?>"><br>

        <small class="small-error-message"><?php if (isset($errors['surname'])) echo $errors['surname'];?></small>
        <input class="textInput-Form" type="text" placeholder="Surname" name="surname" value="<?php echo htmlspecialchars($surname) ?>"><br>

        <small class="small-error-message"><?php if (isset($errors['email'])) echo $errors['email'];?></small>
        <input class="textInput-Form" type="text" placeholder="Email Address" name="email" value="<?php echo htmlspecialchars($email) ?>">
        <br>
        <h3 class="subHeading-Form">What's your Flavour?</h3>
        <small class="small-error-message"><?php if (isset($errors['gender'])) echo $errors['gender'];?></small>
        <div class="genderGroup">
        <input type="radio" id="gender-cone" name="gender" value="Male" class="genderInput" <?php if ($gender === 'Male') echo "selected"?>>
        <label for="gender-cone" class="genderLabel">Flake<br><span class="genderHelpText">Male<span></label>

        <input type="radio" id="gender-cup" name="gender" value="Female" class="genderInput" <?php if ($gender === 'Female') echo "selected"?>>
        <label for="gender-cup" class="genderLabel">Cone<br><span class="genderHelpText">Female<span></label>
        </div>
        <br>
        <!-- Image Adding of Profile Picture -->
         <h3 class="subHeading-Form" style="margin-bottom: 0px;">Add a Profile Picture</h3>
        <small class="small-error-message"><?php if (isset($errors['image'])) echo $errors['image']; ?></small>
        
        <!-- Live Preview Image -->
        <div class="image-holder-AddPost">
            <img id="preview" src="" alt="Preview" class="image-profilePicturePreview">
        </div>

        <div class="linkDiv-Form">(Optional) Max 4MB</div>
        <span style="margin-bottom: 5px; display:block;"></span>
        <label class="custom-file-upload-AddPost">
            <div class="button-Content">
                <img src="icons/UploadImage.svg" alt="Upload" class="upload-icon" />
                <span>Upload Image</span>
            </div>
        <input type="file" name="image" id="image" hidden accept=".jpg,.jpeg,.png">
        </label>
        <!-- Rest of Form after image -->
        <br>
        <h3 class="subHeading-Form">Secret Recipe</h3>
        <small class="small-error-message"><?php if (isset($errors['password'])) echo $errors['password']; ?></small>
        <input class="textInput-Form" type="password" placeholder="Password" name="password" value="<?php echo htmlspecialchars($password) ?>">
        <input class="textInput-Form" type="password" placeholder="Retype Password" name="passwordretype" value="<?php echo htmlspecialchars($passwordReType) ?>">
        <br>
        <button class="button-Form">Sign Up</button>
        <br>
        <div class="linkDiv-Form">Already been scooped?<br> <a href="index.php?view=login" class="forgotPassword-Form">Login</a></div>
        <br>
    </form>
</div>