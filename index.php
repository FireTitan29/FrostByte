<?php 
    session_start();
    // this function makes sure the icons change color by checking what
    // "view" (page) is selected in the URL
    function selectNavigationIcon($iconName) {
        $view = $_GET['view'] ?? '';
        if ($iconName === $view) echo "checked";
        return;
    }

    // this function includes a post depending on whether it has an image
    // or not. It puts it in the correct format.
    function includePost($userName ,$imageName = '', $caption = '', $likesCount = 0) {
        $timeStamp = date("h:i:sa");
        if ($imageName === '') {
            include "php/postText.php";
        } else {
            include "php/PostImage.php";
        }
    }

    $sessionActive = false;

    if (session_status() == PHP_SESSION_ACTIVE) {
        $sessionActive = true;
    }
    $view = $_GET['view'] ?? '';

?>

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
    <!-- Loading our Notifications Bar in -->
    <?php if ($sessionActive) include 'php/NotificationsBar.php'; 
        else include 'php/TopLogo.php';
    ?>
    <!-- Loading in the different page options depending on the view -->
    <?php 
        if ($sessionActive) {
            if ($view === 'profile') include "php/Profile.php";
            else if ($view === 'addpost') include "php/AddPost.php";
            else if ($view === 'messages') include "php/Messages.php";
            else {
                    if (($view !== 'timeline') ||(!isset($_GET['view']))) {
                        header("Location: index.php?view=timeline");
                        exit;
                    }
                    include "php/Timeline.php";
                }  
        }
    ?> 
    <!-- Login/SignUp Page -->
        <?php 
            if (!$sessionActive) {
                if ($view === 'login') include 'php/Login.php';
                else {
                    if ($view !== 'signup' || !isset($_GET['view'])) {
                        header("Location: index.php?view=signup");
                        exit;
                    }
                    include 'php/SignUp.php';
                } 
            } 
        ?>
    <!-- Loading the Navigation Bar in -->
<?php if ($sessionActive) include 'php/NavigationBar.php'; ?>
</body>