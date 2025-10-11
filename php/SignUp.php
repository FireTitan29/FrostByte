<?php 
    $name = $_POST['firstname'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordReType = $_POST['passwordretype'] ?? '';
    $errors = [];
?>


<div class="mainForm-Holder">
    <form class="window-Form" action="POST">
        <h3 class="mainHeading-Form"><span class="accentColor">Sign Up</span> to FrostByte</h3>
        <h3 class="tagLine-Form"><i>Are you fresh from the freezer?</i></h3><br>
        <h3 class="subHeading-Form">Your Sweet Identity</h3>
        <small class="small-error-message"><? if (isset($errors['firstname'])) echo $errors['firstname'];?></small>
        <input class="textInput-Form" type="text" placeholder="First Name" name="firstname" value="<?php echo $name ?>"><br>

        <small class="small-error-message"><? if (isset($errors['surname'])) echo $errors['surname'];?></small>
        <input class="textInput-Form" type="text" placeholder="Surname" name="surname" value="<?php echo $surname ?>"><br>

        <small class="small-error-message"><? if (isset($errors['email'])) echo $errors['email'];?></small>
        <input class="textInput-Form" type="text" placeholder="Email Address" name="email" value="<?php echo $email ?>">
        <br>
        <h3 class="subHeading-Form">What's your Flavour?</h3>
        <small class="small-error-message"><? if (isset($errors['gender'])) echo $errors['gender'];?></small>
        <div class="genderGroup">
        <input type="radio" id="gender-cone" name="gender" value="Male" class="genderInput" <?php if ($gender === 'Male') echo "selected"?>>
        <label for="gender-cone" class="genderLabel">Flake<br><span class="genderHelpText">Male<span></label>

        <input type="radio" id="gender-cup" name="gender" value="Female" class="genderInput" <?php if ($gender === 'Female') echo "selected"?>>
        <label for="gender-cup" class="genderLabel">Cone<br><span class="genderHelpText">Female<span></label>
        </div>
        <br>
        <h3 class="subHeading-Form">Secret Recipe</h3>
        <small class="small-error-message"><? if (isset($errors['password'])) echo $errors['password']; ?></small>
        <input class="textInput-Form" type="password" placeholder="Password" name="password" value="<?php echo $password ?>">
        <small class="small-error-message"><? if (isset($errors['passwordReType'])) echo $errors['passwordReType']; ?></small>
        <input class="textInput-Form" type="password" placeholder="Retype Password" name="passwordretype" value="<?php echo $passwordReType ?>">
        <br>
        <button class="button-Form">Sign Up</button>
        <br>
        <div class="linkDiv-Form">Already been scooped?<br> <a href="index.php?view=login" class="forgotPassword-Form">Login</a></div>
        <br>
    </form>
</div>