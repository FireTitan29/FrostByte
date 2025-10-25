<?php 
// -----------------------------------------------------------
// index.php
// Purpose: Main entry point for "FrostByte"
// - Defines APP_RUNNING constant (prevents direct file access)
// - Loads main.php (handles session, routing, and setup)
// - Decides which view to include based on current user state
// - Handles theme loading (dark/light mode)
// -----------------------------------------------------------
    define('APP_RUNNING', true); 
    include 'php/library/installer.php'; 
    include 'php/main.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrostByte: The Coolest Place</title>

    <!-- Linking the CSS File in -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon.png">

</head>

<body>
    <div class="page-wrapper">
        <!-- Notifications bar -->
        <?php include 'php/components/NotificationsBar.php'; ?>
        <!-- Message to The User -->
         <!-- Load view depending on session and $view -->
        <?php
        if ($sessionActive) {
            // Apply dark theme if set
            if (isset($_SESSION['user']['theme'])) {
                if ($_SESSION['user']['theme'] === 'dark') {
                    echo '<script src="js/change_theme_dark.js"></script>';
                }
            }
            // Logged-in views
            if ($view === 'profile') {
                include "php/views/Profile.php";
            } elseif ($view === 'profileview') {
                include "php/views/ViewProfile.php";
            } else if ($view === 'addpost') {
                include "php/views/AddPost.php";
            } else if ($view === 'messages') {
                include "php/views/MessagesTab.php";
            } else if ($view === 'chat') {
                include "php/views/chatWindow.php";
            } else {
                include "php/views/Timeline.php";
            }
            // Navigation bar is always included for logged-in users
            include 'php/components/NavigationBar.php';

        } else {
            // Guest-only views
            if ($view === 'signup') {
                include 'php/views/SignUp.php';
            } else if ($view === 'passwordreset') {
                include 'php/views/PasswordReset.php';
            } else {
                include 'php/views/Login.php';
            }
        }
        ?>
    </div>

    <!-- JavaScript -->
    <?php if ($view === 'addpost' || $view === 'signup' || $view === 'profile'): ?>
        <script src="js/live_image_viewer.js"></script>
    <?php endif; ?>

    <?php if ($view === 'profile' || $view === 'timeline' || $view === 'profileview'): ?>
        <script src="js/like_post.js"></script>
    <?php endif; ?>

    <?php if ($view === 'chat'): ?>
        <script src="js/chatbox.js"></script>
    <?php endif; ?>

    <script src="js/main.js" defer></script>

</body>