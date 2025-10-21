<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>

<?php if ($sessionActive): ?>; 
<div class="notifications-holder">
  <nav class="notifications-bar">
    <!-- Logo (left side) -->
    <img src="icons/logo<?php if ($_SESSION['user']['theme'] === 'dark'){ echo "-Dark";}?>.svg" class="logo" alt="logo">

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
        <img src="icons/Notifications.svg" alt="Notifications">
        <!-- Example badge -->
        <span class="notifications-badge">3</span>
      </div>

      <!-- Friend Requests -->
      <div class="icon-holder">
        <img src="icons/FriendRequest.svg" alt="Friend Requests">
        <span class="notifications-badge">3</span>
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
