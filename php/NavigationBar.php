<span class="gap"><br></span>
<!-- Navigation Bar at Bottom -->
    <div class="navbar-holder">
        <nav class="navbar">
            <!-- Timeline/Dashboard -->
            <input type="radio" id="nav-dashboard" name="nav" <?php selectNavigationIcon("timeline"); ?>>
            <label for="nav-dashboard">
                <a class="icon-AnchorTag" href="index.php?view=timeline">
                <img src="icons/Timeline.svg" class="icon inactive" alt="Dashboard">
                <img src="icons/Timeline-Active.svg" class="icon active" alt="Dashboard">
                <br>
                <span>Timeline</span>
                </a>
            </label>

            <!-- Add Post -->
            <input type="radio" id="nav-addPost" name="nav" <?php selectNavigationIcon("addpost"); ?>>
            <label for="nav-addPost">
                <a class="icon-AnchorTag" href="index.php?view=addpost">
                <img src="icons/Add.svg" class="icon inactive" alt="Add">
                <img src="icons/Add-Active.svg" class="icon active" alt="Add">
                <br>
                <span>Add Post</span>
                </a>
            </label>

            <!-- Messages -->
            <input type="radio" id="nav-messages" name="nav"  <?php selectNavigationIcon("messages"); ?>>
            <label for="nav-messages">
                <a class="icon-AnchorTag" href="index.php?view=messages">
                <img src="icons/Message.svg" class="icon inactive" alt="Messages">
                <img src="icons/Message-Active.svg" class="icon active" alt="Messages">
                <br>
                <span>Messages</span>
                </a>
            </label>

            <!-- Profile -->
            <input type="radio" id="nav-profile" name="nav"  <?php selectNavigationIcon("profile"); ?>>
            <label for="nav-profile">
                <a class="icon-AnchorTag" href="index.php?view=profile">
                <img src="icons/Profile.svg" class="icon inactive" alt="Profile">
                <img src="icons/Profile-active.svg" class="icon active" alt="Profile">
                <br>
                <span>Profile</span>
                </a>
            </label>
        </nav>
    </div>