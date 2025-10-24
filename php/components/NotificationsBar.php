<!--
NotificationsBar.php
This component displays the notifications bar at the top of the page.
It handles displaying the logo, search bar, notification icons, and friend requests.
If the user session is not active, it displays a simplified bar with just the logo.
-->

<?php 
  // Session check: Prevent direct access to this file
  if (!defined('APP_RUNNING')) {
      header("Location: ../../index.php");
      exit;
  }
?>

<?php if ($sessionActive): ?>; 
  <?php   
    // Notification counters: Count unread notifications and friend requests for the logged-in user
    $numNotifications = countUnreadNotifications($_SESSION['user']['id']); 
    $numFriendRequests = countFriendRequests($_SESSION['user']['id']); 
  ?>
<div class="notifications-holder">
  <nav class="notifications-bar">
    <!-- Logo (left side) -->
    <img src="icons/logo<?php if (cleanHTML($_SESSION['user']['theme']) === 'dark'){ echo "-Dark";}?>.svg" class="logo" alt="logo">

    <!-- Icons & SearchBar (right side) -->
    <div class="searchWrapper">
      <!-- Search bar form -->
      <form method="get" class="searchBarForm" id="searchBarForm">
          <input type="text" id="searchInput" class="searchBarInput" placeholder="Search..." autocomplete="off">
          <button class="hidden-button" type="submit">
            <img src="icons/Search.svg" class="searchIconInside" alt="Search">
          </button>
      </form>
    <!-- Search results will appear here -->
		<div id="searchResultsprofileview" class="searchResults">
			<p class="search-placeholder">Search to see results...</p>
		</div>
    </div>
    
    <div class="notif-icons">
      <!-- Notifications icon and dropdown -->
      <div class="icon-holder">
        <button class="hidden-button" onclick="showNotifications('notifications')">
          <img src="icons/Notifications.svg" alt="Notifications">
        </button>
        <?php if ($numNotifications != 0): ?>
          <span id="notificationBadge" class="notifications-badge"><?php echo cleanHTML($numNotifications)?></span>
        <?php endif;?>
      </div>
      <div id="notifications" class="notificationsResults">
			  <p class="search-placeholder">Notifications</p>
        <?php findAndDisplayNotifications(cleanHTML($_SESSION['user']['id'])) ?>
		  </div>

      <!-- Friend Requests icon and dropdown -->
      <div class="icon-holder">
        <button class="hidden-button" onclick="showNotifications('friendrequests')">
          <img src="icons/FriendRequest.svg" alt="Friend Requests">
        </button>
        <?php if ($numFriendRequests != 0): ?>
          <span id="friendRequestBadge" class="notifications-badge"><?php echo cleanHTML($numFriendRequests)?></span>
        <?php endif; ?>
      </div>
      <div id="friendrequests" class="notificationsResults">
			  <p class="search-placeholder">Friend Requests</p>
        <?php findAndDisplayFriendRequests($_SESSION['user']['id']); ?>
		  </div>
    </div>
  </nav>
</div>
<!-- If session isn't active, load in a simple logo top bar -->
<?php else: ?>
  <div class="notifications-holder">
  <nav class="signUp-bar">
    <!-- Simple logo bar for non-logged-in users -->
    <img src="icons/logo.svg" class="logo" alt="logo">
  </nav>
</div>
<?php endif; ?>
<span class="gap"></span>