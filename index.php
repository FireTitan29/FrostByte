<?php 
    session_start();
    // unset($_SESSION['user']);
    // session_destroy();

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
            include "php/PostText.php";
        } else {
            include "php/PostImage.php";
        }
    }
    // instead of validating every single field with the same code,
    // I have written a function so that I can reuse it whenever I need to
    // this makes sure that a string isn't empty, and doesn't contain numbers
    // this will be used for firstname, surname etc
    function validateString($string, $field, &$errors)
    {
        if ($string === '') {
            $errors[$field] = "Required!";
            } else if (!preg_match("/^[a-zA-Z' -]+$/", $string)) {
                $errors[$field] = "CANNOT contain numbers";
            } else if (strlen($string) < 3 || strlen($string) > 25) {
                $errors[$field] = "Must be between 3-25 characters";
            }  else {
            return true;
        }
            return false;
    }

    // this function ensures that the email the user is signing up with is unique
    function checkEmailExists($email) {
            // connecting to the DB
            $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);

            $stmt = $pdo->prepare('SELECT email FROM users WHERE email = :email LIMIT 1');
            $stmt->bindValue(':email', $email);

            $stmt->execute();

            // if fetchColumn returns something, the email exists therefore
            // we cannot proceed with signup
            return (bool) $stmt->fetchColumn();

    }

    function checkPasswordIsCorrect($password, $email) {
        // connecting to the DB
        $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);

        $stmt = $pdo->prepare('SELECT password FROM users WHERE email = :email LIMIT 1');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $pass_hash = $stmt->fetchColumn();
        
        // if the password is correct, then we return true, otherwise they aren't the same
        return (bool) password_verify($password, $pass_hash);
    }

    // function for validating files, reused for all file uploads of images
    // there are a lot of comments here because this is quite complicated
    function validateFile($fileName,$fileTempPath, $fileSize, &$errors) {

        // Ensuring we only get the file types we want, and also
        // stop malware injection by validating file MIME type
        $fileExtentionsAllowed = ['jpg', 'jpeg', 'png'];
        $fileMIMEsAllowed = ['image/jpeg', 'image/png'];

        // getting the file's extention and then ensuring it is lowercase
        // for uniformity, so we can validate it
        $extention = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validating the file from here:
        // Extension
        if(!in_array($extention, $fileExtentionsAllowed)) {
            $errors['image'] = "Only JPG and PNG files are allowed";
            return false;
        } 

        // MIME type using finfo (file info) functions
        // first we open the file, to read the type
        // then we save the type into mime, then we close the file
        // then we ensure the mime is the same as what is in our array
        // that we are validating towards
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $fileTempPath);
        finfo_close($finfo);
        if (!in_array($mime, $fileMIMEsAllowed)) {
            $errors['image'] = "Invalid file type. Only JPG and PNG files are allowed";
            return false;
        }
        
        // File Size (4MB file size cap)
        if ($fileSize > (4 * 1024 *1024)) {
            $errors['image'] = "File size is too large. Max 4MB";
            return false;
        }
        
        // Extra layer of protection to protect against mime spoofing
        // again, we want to ensure nobody can upload viruses or issues
        // into our site
        $checkFileImage = getimagesize($fileTempPath);
        if ($checkFileImage === false) {
            $errors['image'] = "The uploaded file is not an image file";
            return false;
        }
        return true;
    }

    function addPostToDB($caption, $imagePath = '') {
        // connecting to the DB
        $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);

        $stmt = $pdo->prepare(
        'INSERT INTO posts (user_id, caption, image_path)
        VALUES (:user_id, :caption, :image_path)');

        // stopping SQL injection
        $stmt->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->bindValue(':caption', $caption, PDO::PARAM_STR);
        $stmt->bindValue(':image_path', $imagePath, PDO::PARAM_STR);
        
        $stmt->execute();
    }

    function getUserDetails($email) {
        // connecting to the DB
        $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
        
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }


    // Form Submissions
    $name = '';
    $surname =  '';
    $email =  '';
    $gender =  '';
    $caption =  '';
    $password =  '';
    $passwordReType = '';
    $errors = [];

    // Signup Page Submission, Form Validation & Adding to DB
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'signup') {
        // Getting all of the values that have been posted through the form
        $name = trim($_POST['firstname'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $passwordReType = trim($_POST['passwordretype'] ?? '');

        // Form validation
        validateString($name, 'firstname', $errors);
        validateString($surname, 'surname', $errors);

        // Validating the email using the built in method, and then also using our custom function to check the db
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";
        else if (checkEmailExists($email)) $errors["email"] = "Email is already in use by another user";

        if ($gender === '') $errors['gender'] = 'Please select an option';
        if ($password === '' || $passwordReType === '') {
            $errors["password"] = "Fields cannot be left empty";
        } else if ($password !== $passwordReType) {
            $errors["password"] = "Passwords do not match";
        }

        // Validating Profile Picture if user has uploaded something
        if (!empty($_FILES['image']['name'])) {
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK)
            {
                // getting all of the file details
                $fileTempPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileSize = $_FILES['image']['size'];
                $fileType = $_FILES['image']['type'];

                // Using the function we made to validate the file
                validateFile($fileName, $fileTempPath, $fileSize, $errors);

            } else {
                $errors['image'] = "Error during file upload.";
            }
        }

        // if there are no errors, add user info to DB, then redirect to login page
        if (empty($errors)) {
            $fileExists = false;

            // Default value for the profile picture if nothing is uploaded
            $destPath = 'icons/profile-picture-none.svg';

            if (!empty($_FILES['image']['name'])) {  

                $fileExists = true;

                // Making sure the directory exists,
                // and if not making the directory with the correct
                // permissions in place
                $folderName = $email;
                $dir = __DIR__ . "/uploads/profilepictures/$folderName";
                
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 

                $userSurname = $surname;
    
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
                // Generating a new Name for the image
                $newName    = $email . '.' . $ext;
                $destPath = "uploads/profilepictures/$folderName/$newName";
                
                $fullPath = $dir . "/" . $newName;

                // Finally, moving everything into the correct file destination
                // and also adding it to the DB
                move_uploaded_file($fileTempPath, $fullPath);
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // connecting to the DB
            $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
            $stmt = '';

            // insert using placeholder
            $stmt = $pdo->prepare(
            'INSERT INTO users (email, firstname, surname, gender, password, profile_pic)
            VALUES (:email, :firstname, :surname, :gender, :password, :profile_pic)');

            // stopping SQL injection
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':firstname', $name);
            $stmt->bindValue(':surname', $surname);
            $stmt->bindValue(':gender', $gender);
            $stmt->bindValue(':password', $hashedPassword);
            $stmt->bindValue(':profile_pic', $destPath);

            $stmt->execute();
            header("Location: index.php?view=login&signup=success");
            exit;
        }
    }

     // Login Page Submission, Form Validation & Retrieving credentials from DB
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'login') {

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validation of email, then password
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";
        else if (checkEmailExists($email)) { 
            if (!checkPasswordIsCorrect($password, $email)) {
                $errors["email"] = "Wrong email/password combination";
            }
        }
        else {
            $errors["email"] = "User does not exist...";
        }

        // if there are no errors, then we pull all the details into the session
        // except for the password of course, and then we go to the timeline
        if (empty($errors))
        {
            $user = getUserDetails($email);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'firstname' => $user['firstname'],
                'surname' => $user['surname'],
                'gender' => $user['gender'],
                'profile_pic' => $user['profile_pic'],       
            ];

            // Redirect to timeline after login has been successful
            header("Location: index.php?view=timeline");
            exit;
        }
    }

    // Reset Password Form
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'passwordreset') {
        // Getting all of the values that have been posted through the form
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $passwordReType = trim($_POST['passwordretype'] ?? '');

        // Form validation
        // Validating the email using the built in method, and then also using our custom function to check the db
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";
        else if (!checkEmailExists($email)) $errors["email"] = "User does not exist";

        if ($password === '' || $passwordReType === '') {
            $errors["password"] = "Fields cannot be left empty";
        } else if ($password !== $passwordReType) {
            $errors["password"] = "Passwords do not match";
        }

        // if there are no errors, add user info to DB, then redirect to login page
        if (empty($errors)) { 

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // connecting to the DB
            $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);

            // update using placeholder
            $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE email = :email');

            // stopping SQL injection
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $hashedPassword);

            $stmt->execute();

            header("Location: index.php?view=login&reset=success");
            exit;
        }
    }

    // Adding a new post
    if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'addpost') {
        
        $caption = trim($_POST['caption'] ?? '');
        
        if (empty($_FILES['image']['name']) && empty($caption))
        {
            $errors['image'] = "You cannot make an empty post!";
        }

        // Creating the variables
        $fileTempPath = ''; 
        $fileName = ''; 
        $fileSize = ''; 
        $fileType = ''; 


        if (!empty($_FILES['image']['name'])) {
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK)
            {
                // getting all of the file details
                $fileTempPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileSize = $_FILES['image']['size'];
                $fileType = $_FILES['image']['type'];

                // Using the function we made to validate the file
                validateFile($fileName, $fileTempPath, $fileSize, $errors);

            } else {
                $errors['image'] = "Error during file upload.";
            }
        }
        
        // Decided to keep all posts uniform with a max value of
        // 280 characters for captions (like Twitter used to do)
        if (strlen($caption) > 280) {
            $length = strlen($caption);
            $errors['caption'] = "$length / 280 Characters, (280 Max)";
        }


        if (empty($errors)) {
            if (!empty($_FILES['image']['name'])) {
                
                // Making sure the directory exists,
                // and if not making the directory with the correct
                // permissions in place
                $folderName = $_SESSION['user']['email'];
                $dir = __DIR__ . "/uploads/posts/$folderName";
                
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 

                $userSurname = $_SESSION['user']['surname'];
    
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
                // Generating a new Name for the image
                $newName    = uniqid("img_$userSurname", true) . '.' . $ext;
                $destPath = "uploads/posts/$folderName/$newName";
                
                $fullPath = $dir . "/" . $newName;

                // Finally, moving everything into the correct file destination
                // and also adding it to the DB
                if (move_uploaded_file($fileTempPath, $fullPath)) {
                    addPostToDB($caption, $destPath);
                    header("Location: index.php?view=addpost&post=success");
                    exit;
                } else {
                    header("Location: index.php?view=addpost&post=failed");
                    exit;
                }
            } else {
                addPostToDB($caption);
                header("Location: index.php?view=addpost&post=success");
                exit;
            }
        }
    }
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
     <!-- Live Image Viewer -->
    <?php if ($view == 'addpost' || $view = 'signup'): ?>
        <script src="js/live_image_viewer.js"></script>
    <?php endif; ?>
</body>