<!-- Stop people from accessing the file directly -->
<?php 
  if (!defined('APP_RUNNING')) {
      header("Location: ../../index.php");
      exit;
  }
?>

<?php if ($sessionActive): ?>; 
  <?php   
    $numNotifications = countUnreadNotifications($_SESSION['user']['id']); 
    $numFriendRequests = countFriendRequests($_SESSION['user']['id']); 
  ?>
<div class="notifications-holder">
  <nav class="notifications-bar">
    <!-- Logo (left side) -->
    <img src="icons/logo<?php if (cleanHTML($_SESSION['user']['theme']) === 'dark'){ echo "-Dark";}?>.svg" class="logo" alt="logo">

    <!-- Icons & SearchBar (right side) -->
    <div class="searchWrapper">
      <form method="get" class="searchBarForm" id="searchBarForm">
          <input type="text" id="searchInput" class="searchBarInput" placeholder="Search..." autocomplete="off">
          <button class="hidden-button" type="submit">
            <img src="icons/Search.svg" class="searchIconInside" alt="Search">
          </button>
      </form>
    <!-- results will appear here -->
		<div id="searchResultsprofileview" class="searchResults">
			<p class="search-placeholder">Search to see results...</p>
		</div>
    </div>
    
    <div class="notif-icons">
      <!-- Notifications -->
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

      <!-- Friend Requests -->
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
    <img src="icons/logo.svg" class="logo" alt="logo">
  </nav>
</div>
<?php endif; ?>
<span class="gap"></span>