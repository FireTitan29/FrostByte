<?php 
    // -----------------------------------------------------------
    // main.php
    // Purpose: Acts the core brain for the web app.
    // - Defines session behavior, view handling, and redirects
    // - Includes all supporting libraries (database, validation, etc.)
    // - Provides a few global helper functions
    // -----------------------------------------------------------

    // Prevent direct access unless app is initialized
    if (!defined('APP_RUNNING')) {
        header("Location: ../index.php");
        exit;
    }
    session_start();

    // Debug settings (for development/domain error tracking)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Get the current view from the URL
    $view = $_GET['view'] ?? '';

    // Log the user out by clearing session and redirecting
    function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?view=login');
        exit;
    }
    
    if ($view === 'logout') {
        logout();
    }

    // Check if a user session is active
    $sessionActive = isset($_SESSION['user']);

    // Handle redirects BEFORE any output is sent
    // Ensures correct page loads depending on login state
    if ($sessionActive) {
        if ($view === '') {
            header("Location: index.php?view=timeline");
            exit;
        }
    } else {
        if ($view === '') {
            header("Location: index.php?view=signup");
            exit;
        }
    }
    
    // -----------------------------------------------------------
    // GENERAL FUNCTIONS
    // -----------------------------------------------------------

    // Highlights the selected navigation icon based on current view
    function selectNavigationIcon($iconName) {
        $view = $_GET['view'] ?? '';
        if ($iconName === $view) echo "checked";
        return;
    }

    // Creates folder paths for uploads (profile pics, posts, etc.)
    // - Ensures directory exists (creates if not)
    // - Returns both the relative destination path (for DB) and full path (for file system)
    function createFileDirectory($filePath, $fileName, $parentFolder, $prefix, $folderName = '') {
        $dir = __DIR__ . $filePath;
        
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            // Trying to fix permissions, so I am forcing them
            chmod($dir, 0777);
        } 

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newName = uniqid($prefix, true) . '.' . $ext;  
        $destPath = '';

        // Decide whether to create a subfolder or not
        if ($folderName === '') {
            $destPath = "uploads/$parentFolder/$newName";
        } else {          
            $destPath = "uploads/$parentFolder/$folderName/$newName";
        }
        
        $fullPath = $dir . "/" . $newName;

        return ["Destination_Path" => $destPath, 
                "Full_Path" => $fullPath ];
    }

    // Easier to write 'cleanHTML' than all the other stuff when escaping the HTML
    function cleanHTML($phpEchoText) {
        return (string) htmlspecialchars($phpEchoText, ENT_QUOTES, 'UTF-8');
    }
    
    // -----------------------------------------------------------
    // LIBRARY INCLUDES
    // -----------------------------------------------------------
    // Load modular functions for DB, validation, auth, posts, and messaging
    include 'library/database.php';
    include 'library/validation.php';
    include 'library/notifications.php';
    include 'library/friendRequests.php';
    include 'library/posts.php';
    include 'library/authentication.php';
    include 'library/messages.php';
?>