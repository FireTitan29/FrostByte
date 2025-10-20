<?php 
    session_start();

    // debugging for errors when displaying on my domain (www.defiantlyduggan.co.za)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $view = $_GET['view'] ?? '';

    // logging out of account
    function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?view=login');
        exit;
    }
    
    if ($view === 'logout') {
        logout();
    }

    $sessionActive = isset($_SESSION['user']);

    // Handle redirects BEFORE output (on the cpanel, it won't let me change
    // the header after html elements have landed on the page, so we'll handle
    // the redirects here before the rest of the page loads)
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
    // GENERAL FUNCTIONS:

    // this function makes sure the icons change color by checking what
    // "view" (page) is selected in the URL
    function selectNavigationIcon($iconName) {
        $view = $_GET['view'] ?? '';
        if ($iconName === $view) echo "checked";
        return;
    }

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

        // if the folder name is blank, then it doesn't create a specific folder
        if ($folderName === '') {
            $destPath = "uploads/$parentFolder/$newName";
        } else {          
            $destPath = "uploads/$parentFolder/$folderName/$newName";
        }
        
        $fullPath = $dir . "/" . $newName;

        return ["Destination_Path" => $destPath, 
                "Full_Path" => $fullPath ];
    }
    
    // -----------------------------------------------------------
    // Including all of the other libraries
    include 'library/database.php';
    include 'library/validation.php';
    include 'library/authentication.php';
    include 'library/messages.php';
    include 'library/posts.php';
?>