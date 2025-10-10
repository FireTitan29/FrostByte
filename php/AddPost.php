
<form class="window-AddPost" action="POST">
    <h3 class="mainHeading-Form"><span class="accentColor">Add a Scoop</span> </h3>
    <br>
    <textarea class="captionField-AddPost" name="caption" id="caption" rows="4" placeholder="Add a post caption..."></textarea>
    <div class="linkDiv-Form">(Optional) Max 4MB</div>
    <span style="margin-bottom: 5px; display:block;"></span>
        <label class="custom-file-upload-AddPost">
            <div class="button-Content">
                <img src="icons/UploadImage.svg" alt="Upload" class="upload-icon" />
                <span>Upload Image</span>
            </div>
        <input type="file" name="image" id="image" hidden>
        </label>
    <button class="button-Form">Add Post</button>
    <p style="margin-bottom: 10px; margin-top: 0px"></p>
</form>