<?php include 'php/main.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrostByte: The Coolest Place</title>

    <!-- Linking our CSS File in -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon.png">

</head>
<body>
    <div class="page-wrapper">
        <!-- Loading our Notifications Bar in -->
        <?php if ($sessionActive) include 'php/NotificationsBar.php'; 
            else include 'php/TopLogo.php';
        ?>
        <!-- Loading in the different page options depending on the view -->
        <!-- Login/SignUp Page -->
        <?php 
            if ($sessionActive) {
                if ($view === 'profile') {
                    include "php/Profile.php";
                } else if ($view === 'addpost') {
                    include "php/AddPost.php";                   
                } else if ($view === 'messages'){ 
                    include "php/Messages.php";
                } else {
                    include "php/Timeline.php";
                }  
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
     <!-- Live Image Viewers -->
      <!-- SignUp & Add Post -->
    <?php if ($view == 'addpost' || $view = 'signup' || $view === 'profile'): ?>
        <script src="js/live_image_viewer.js"></script>
    <?php endif; ?>
</body>