
<!-- A little animated greeting to cheer up the user -->
<div class="timeline-div">
<h3 class="welcome-message"><span class="hand-wave">👋</span> Welcome, <?php echo htmlspecialchars($_SESSION['user']['firstname'])?></h3>
<p class="timeline-text">Let's see what everyone has been up to...</p>
</div>

<!-- Displaying all posts in the DB on the timeline -->
<?php if(!findAndDisplayPosts()): ?>
  <h3 class="noPosts-Text">No Posts Yet...</h3>
<?php endif; ?> 

