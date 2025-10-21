<!-- Security: Prevent direct access to this file unless APP_RUNNING is defined -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>

<!-- Navigation Bar Component
     - Displays fixed navigation bar at the bottom of the page.
     - Each icon acts as a navigation link.
     - Icon color indicates state:
        + Pink = current page
        + Blue = inactive (link available to user but not active) -->

<span class="gap"><br></span>
<!-- Navigation Bar at Bottom -->
    <div class="navbar-holder">
        <nav class="navbar">
            <!-- Timeline/Dashboard: Takes user to main timeline feed -->
            <input type="radio" id="nav-dashboard" name="nav" <?php selectNavigationIcon("timeline"); ?>>
            <label for="nav-dashboard">
                <a class="icon-AnchorTag" href="index.php?view=timeline">
                <img src="icons/Timeline.svg" class="icon inactive" alt="Dashboard">
                <img src="icons/Timeline-Active.svg" class="icon active" alt="Dashboard">
                <br>
                <span>Timeline</span>
                </a>
            </label>

            <!-- Add Post: Goes to the form for creating a new post -->
            <input type="radio" id="nav-addPost" name="nav" <?php selectNavigationIcon("addpost"); ?>>
            <label for="nav-addPost">
                <a class="icon-AnchorTag" href="index.php?view=addpost">
                <img src="icons/Add.svg" class="icon inactive" alt="Add">
                <img src="icons/Add-Active.svg" class="icon active" alt="Add">
                <br>
                <span>Add Post</span>
                </a>
            </label>

            <!-- Messages: Opens the user's messages/inbox -->
            <input type="radio" id="nav-messages" name="nav"  <?php selectNavigationIcon("messages"); ?>>
            <label for="nav-messages">
                <a class="icon-AnchorTag" href="index.php?view=messages">
                <img src="icons/Message.svg" class="icon inactive" alt="Messages">
                <img src="icons/Message-Active.svg" class="icon active" alt="Messages">
                <br>
                <span>Messages</span>
                </a>
            </label>

            <!-- Profile: Opens the SESSION user's profile page -->
            <input type="radio" id="nav-profile" name="nav"  <?php selectNavigationIcon("profile"); ?>>
            <label for="nav-profile">
                <a class="icon-AnchorTag" href="index.php?view=profile">
                <img src="icons/Profile.svg" class="icon inactive" alt="Profile">
                <img src="icons/Profile-Active.svg" class="icon active" alt="Profile">
                <br>
                <span>Profile</span>
                </a>
            </label>
        </nav>
    </div>