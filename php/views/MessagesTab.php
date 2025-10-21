<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<?php $imageName = ''; ?>
<div class="window-Message">
    <div class="sendNewMessage-Top">
        <span class="mainHeading-Form">
            <span class="accentColor">Messages</span>
        </span><br>
        <p style="margin-bottom: 20px;"></p>
        <div class="searchWrapper">
            <form method="get" class="searchBarForm" id="searchBarMessageForm">
                <input type="text" id="searchMessageInput" class="searchBarInput" placeholder="Search..." autocomplete="off">
                <button class="hidden-button" type="submit">
                    <img src="icons/Search.svg" class="searchIconInside" alt="Search">
                </button>
            </form>
            <div id="searchResultschat" class="searchResultschat">
                <p class="search-placeholder">Search to message someone new</p>
            </div>
        </div>
    </div>
    <div class="message-scroll-area">
        <?php findAndDisplayActiveChats($_SESSION['user']['id']) ?>
    </div>
</div>

<script>
    document.getElementById('searchBarMessageForm').addEventListener('submit', searchMessagingListener);
</script>