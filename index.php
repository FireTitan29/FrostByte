<?php 
    session_start();
    // unset($_SESSION['user']);
    // session_destroy();

    // debugging for errors when displaying on my domain (www.defiantlyduggan.co.za)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $view = $_GET['view'] ?? '';

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

    // Form Submissions
    $name = '';
    $surname =  '';
    $email =  '';
    $gender =  '';
    $password =  '';
    $passwordReType = '';
    $errors = [];
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

        // Validating the email using the built
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";

        if ($gender === '') $errors['gender'] = 'Please select an option';
        if ($password === '' || $passwordReType === '') {
            $errors["password"] = "Fields cannot be left empty";
        } else if ($password !== $passwordReType) {
            $errors["password"] = "Passwords do not match";
        }

        if (empty($errors)) {
            // store the whole user in session
            $_SESSION['user'] = [
                'firstname' => $name,
                'surname'   => $surname,
                'email'     => $email,
                'gender'    => $gender,
            ];
            header("Location: index.php?view=timeline");
            exit;
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
    <!-- Loading our Notifications Bar in -->
    <?php if ($sessionActive) include 'php/NotificationsBar.php'; 
        else include 'php/TopLogo.php';
    ?>
    <!-- Loading in the different page options depending on the view -->
    <!-- Login/SignUp Page -->
    <?php 
        if ($sessionActive) {
            if ($view === 'profile') include "php/Profile.php";
            else if ($view === 'addpost') include "php/AddPost.php";
            else if ($view === 'messages') include "php/Messages.php";
            else {
                include "php/Timeline.php";
            }  
        } else {
            if ($view === 'login') {
                include 'php/Login.php';
            } else if ($view === 'signup') {
                include 'php/SignUp.php';
            }
        }
    ?> 

    <!-- Loading the Navigation Bar in -->
<?php if ($sessionActive) include 'php/NavigationBar.php'; ?>
</body>