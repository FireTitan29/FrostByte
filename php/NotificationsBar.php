<?php if ($sessionActive): ?>; 
<div class="notifications-holder">
  <nav class="notifications-bar">
    <!-- Logo (left side) -->
    <img src="icons/logo.svg" class="logo" alt="logo">

    <!-- Icons & SearchBar (right side) -->
     <div class="searchWrapper">
        <form method="get" class="searchBarForm">
            <input type="text" class="searchBarInput" placeholder="Search...">
            <img src="icons/Search.svg" class="searchIconInside" alt="Search">
        </form>
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