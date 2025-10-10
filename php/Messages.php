<?php $imageName = ''; ?>
<div class="window-Message">
    <div class="sendNewMessage-Top">
        <span class="mainHeading-Form">
            <span class="accentColor">Messages</span>
        </span><br>
        <!-- Little Gap -->
        <p style="margin-bottom: 20px;"></p>
        
        <div class="searchWrapper">
            <form method="get" class="searchBarForm">
                <input type="text" class="searchBarInput" placeholder="Search to start new chat...">
                <img src="icons/Search.svg" class="searchIconInside" alt="Search">
            </form>
        </div>
    </div>
    <div class="message-scroll-area">
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
        <?php include 'SingleMessage.php'; ?>
    </div>
</div>