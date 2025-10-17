<?php include 'php/main.php' ?>

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
        <!-- Loading our Notifications Bar in -->
        <?php include 'php/NotificationsBar.php'; ?>

        <!-- Loading in the different page options depending on the view -->
        <?php 
            if ($sessionActive) {
                if (isset($_SESSION['user']['theme'])) {
                    if ($_SESSION['user']['theme'] === 'dark') {
                        echo '<script src="js/change_theme_dark.js"></script>';
                    }
                }
                if ($view === 'profile') {
                    include "php/Profile.php";
                } else if ($view === 'addpost') {
                    include "php/AddPost.php";                   
                } else if ($view === 'messages'){ 
                    include "php/Messages.php";
                } else {
                    include "php/Timeline.php";
                }  
            // Login/SignUp Page
            } else {
                if ($view === 'signup') {
                    include 'php/SignUp.php';
                } else if ($view === 'passwordreset') {
                    include 'php/PasswordReset.php';  
                } else {
                    include 'php/Login.php';
                }
            }
                ?> 

        <!-- Loading the Navigation Bar in -->
        <?php if ($sessionActive) include 'php/NavigationBar.php'; ?>
    </div>

    <!-- JavaScript -->
    <?php if ($view === 'addpost' || $view === 'signup' || $view === 'profile'): ?>
        <script src="js/live_image_viewer.js"></script>
        <?php endif; ?>
        
    <script src="js/main.js"></script>

</body>