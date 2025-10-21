<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<!-- failed / success -->
<?php if (isset($_GET['post'])): ?>
    <?php if ($_GET['post'] === 'success'): ?>
        <div class="positive-feedback-message-holder">
            <h3 class="feedback-heading">🎉 Posted</h3>
            <p class="feedback-message">Time to scoop up some likes!</p>
        </div>
    <?php endif; ?>

    <?php if ($_GET['post'] === 'failed'): ?>
        <div class="negative-feedback-message-holder">
            <h3 class="feedback-heading">😢 Something went wrong</h3>
            <p class="feedback-message">Please try again...</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

<form class="window-AddPost" method="POST" enctype="multipart/form-data">
    <h3 class="mainHeading-Form"><span class="accentColor">Add a Scoop</span></h3>
    <br>
    <small class="small-error-message"><?php if (isset($errors['image'])) echo $errors['image']; ?></small>
    <textarea class="captionField-AddPost" name="caption" id="caption" rows="4" placeholder="Add a post caption..."><?php echo htmlspecialchars($caption); ?></textarea>
    <small class="small-error-message"><?php if (isset($errors['caption'])) echo $errors['caption']; ?></small>
    <div class="linkDiv-Form">(Optional) Max 4MB</div>
    <span style="margin-bottom: 5px; display:block;"></span>

        <label class="custom-file-upload-AddPost">
            <div class="button-Content">
                <img src="icons/UploadImage<?php if ($_SESSION['user']['theme'] === 'dark'){ echo "-Dark";}?>.svg" alt="Upload" class="upload-icon" />
                <span>Upload Image</span>
            </div>
        <input type="file" name="image" id="image" hidden accept=".jpg,.jpeg,.png">
        </label>
        <div class="image-holder-AddPost">
            <img id="preview" src="" alt="Preview" class="image-AddPost">
        </div>
    <button type="submit" class="button-Form">Add Post</button>
    <p style="margin-bottom: 10px; margin-top: 0px"></p>
</form>

