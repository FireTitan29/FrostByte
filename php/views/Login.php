<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<?php if (isset($_GET['signup'])): ?>
    <?php if ($_GET['signup'] === 'success'): ?>
        <div class="positive-feedback-message-holder">
            <h3 class="feedback-heading">🍦 Sign Up Successful!</h3>
            <p class="feedback-message">Login to get to cool</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="mainForm-Holder">
    <form class="window-Form" method="POST">
        <h3 class="mainHeading-Form"><span class="accentColor">Login</span> to FrostByte</h3>
        <span style="margin-bottom: 25px; display:block;"></span>
        <small class="small-error-message"><?php if (isset($errors['email'])) echo $errors['email'];?></small>
        <input class="textInput-Form" type="text" placeholder="Email Address" name="email" value="<?php echo $email ?>">
        <br>
        <input class="textInput-Form" type="password" placeholder="Password" style="margin-bottom: 10px;" name='password'>
        <span style="margin-bottom: 5px; display:block;"></span>
        <a href="index.php?view=passwordreset" class="forgotPassword-Form">Forgot Password</a>
        <span style="margin-bottom: 5px; display:block;"></span>
        <button class="button-Form">Login</button>
        <span style="margin-bottom: 15px; display:block;"></span>
        <div class="linkDiv-Form">New to FrostByte?<br><a href="index.php?view=signup" class="forgotPassword-Form">Create an Account</a></div>
        <span style="margin-bottom: 15px; display:block;"></span>
    </form>
</div>


