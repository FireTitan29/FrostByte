<?php
// Login view - displays the login form and handles login-related messages
// Prevent direct access to this file
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<?php 
// Display signup success message if applicable
if (isset($_GET['signup'])): ?>
    <?php if ($_GET['signup'] === 'success'): ?>
        <div class="positive-feedback-message-holder disapearingMessage" data-autoshow="positive">
            <h3 class="feedback-heading">🍦 Sign Up Successful!</h3>
            <p class="feedback-message">Login to get to cool</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Login Form Container -->
<div class="mainForm-Holder">
    <form class="window-Form" method="POST">
        <h3 class="mainHeading-Form"><span class="accentColor">Login</span> to FrostByte</h3>
        <span style="margin-bottom: 25px; display:block;"></span>
        <small class="small-error-message"><?php if (isset($errors['email'])) echo $errors['email'];?></small>
        <!-- Email address input field -->
        <input class="textInput-Form" type="text" placeholder="Email Address" name="email" value="<?php echo cleanHTML($email) ?>">
        <br>

        <!-- Password input field -->
        <input class="textInput-Form" type="password" placeholder="Password" style="margin-bottom: 10px;" name='password'>
        <span style="margin-bottom: 5px; display:block;"></span>
        <a href="index.php?view=passwordreset" class="forgotPassword-Form">Forgot Password</a>
        <span style="margin-bottom: 5px; display:block;"></span>

        <!--Login Button -->
        <button class="button-Form">Login</button>
        <span style="margin-bottom: 15px; display:block;"></span>

        <!-- Link to create a new account for new users -->
        <div class="linkDiv-Form">New to FrostByte?<br><a href="index.php?view=signup" class="forgotPassword-Form">Create an Account</a></div>
        <span style="margin-bottom: 15px; display:block;"></span>
    </form>
</div>
