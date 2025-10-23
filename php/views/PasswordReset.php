<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<div class="mainForm-Holder">
    <form class="window-Form" method="POST">
        <div class="linkDiv-Form">Did you have a brain-freeze?</div>
        <h3 class="mainHeading-Form"><span class="accentColor">Reset</span> Password</h3>
        <span style="margin-bottom: 8px; display:block;"></span>
        <h3 class="subHeading-Form">Email linked to your account</h3>
        <small class="small-error-message"><?php if (isset($errors['email'])) echo $errors['email'];?></small>
        <input class="textInput-Form" type="text" placeholder="Email Address" name="email" value="<?php echo cleanHTML($email) ?>">
        <h3 class="subHeading-Form">New Secret Recipe</h3>
        <small class="small-error-message"><?php if (isset($errors['password'])) echo $errors['password']; ?></small>
        <input class="textInput-Form" type="password" placeholder="Password" name="password" value="<?php echo cleanHTML($password) ?>">
        <input class="textInput-Form" type="password" placeholder="Retype Password" name="passwordretype" value="<?php echo cleanHTML($passwordReType) ?>">
        <button class="button-Form">Reset Password</button>
        <span style="margin-bottom: 15px; display:block;"></span>
        <div class="linkDiv-Form">Remembered your password?<br><a href="index.php?view=login" class="forgotPassword-Form">Back to Login</a></div>
        <span style="margin-bottom: 15px; display:block;"></span>
    </form>
</div>

