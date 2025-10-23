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
    <?php if (countConversations($_SESSION['user']['id']) > 0):  ?>
    <div>
        <?php findAndDisplayActiveChats($_SESSION['user']['id']) ?>
    </div>
    <?php else: ?>
    <div>
        <p class="noConvos">No conversations yet…</p>
        <p class="noConvos-bottom">Find someone and say "hello!" <span class="hand-wave">💬</span></p>
    </div>
    <?php endif; ?>
</div>