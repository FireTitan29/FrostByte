<!-- Stop people from accessing the file directly -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>
<?php $imageName = ''; ?>
<!-- Main container for the Messages tab -->
<div class="window-Message">
    <!-- Top section containing heading and search bar -->
    <div class="sendNewMessage-Top">
        <!-- Heading for the Messages tab -->
        <span class="mainHeading-Form">
            <span class="accentColor">Messages</span>
        </span><br>
        <!-- Spacer paragraph for layout -->
        <p style="margin-bottom: 20px;"></p>
        <!-- Wrapper for the search bar and results -->
        <div class="searchWrapper">
            <!-- Search form to find users to message -->
            <form method="get" class="searchBarForm" id="searchBarMessageForm">
                <input type="text" id="searchMessageInput" class="searchBarInput" placeholder="Search..." autocomplete="off">
                <button class="hidden-button" type="submit">
                    <img src="icons/Search.svg" class="searchIconInside" alt="Search">
                </button>
            </form>
            <!-- Container to display search results dynamically -->
            <div id="searchResultschat" class="searchResultschat">
                <p class="search-placeholder">Search to message someone new</p>
            </div>
        </div>
    </div>
    <!-- Conditional display of conversations or no conversations message -->
    <?php if (countConversations($_SESSION['user']['id']) > 0):  ?>
    <!-- Container for displaying active chat conversations -->
    <div>
        <?php findAndDisplayActiveChats($_SESSION['user']['id']) ?>
    </div>
    <?php else: ?>
    <!-- Message displayed when no conversations exist -->
    <div>
        <p class="noConvos">No conversations yet…</p>
        <p class="noConvos-bottom">Find someone and say "hello!" <span class="hand-wave">💬</span></p>
    </div>
    <?php endif; ?>
</div>