<?php
// Library: Authentication & User Management
// Handles all user-related operations including:
// - Signup: form validation, file uploads, DB insert
// - Login: email/password validation, session creation
// - Password reset: validation and updating DB
// - Add Post: caption/image validation, file upload, DB insert
// - Profile Edit: update user info, profile picture handling

// Each block runs only when triggered by a matching POST + view.
// Redirects users appropriately after successful operations.

$name = '';
$surname =  '';
$email =  '';
$gender =  '';
$caption =  '';
$password =  '';
$passwordReType = '';
$errors = [];

// ==============================
// SIGNUP FORM
// - Validates signup form fields
// - Handles optional profile picture upload
// - Hashes password and inserts new user into DB
// - Redirects to login with success message
// ==============================

if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'signup') {
    // Getting all of the values that have been posted through the form
    $name = trim($_POST['firstname'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $passwordReType = trim($_POST['passwordretype'] ?? '');

    // Validating through the function I wrote
    validateSignUp($name, $surname, $email, $gender, $password, $passwordReType, $errors);

    // if there are no errors, add user info to DB, then redirect to login page
    if (empty($errors)) {
        $fileExists = false;

        // Default value for the profile picture if nothing is uploaded
        $destPath = 'icons/profile-picture-none.svg';

        if (!empty($_FILES['image']['name'])) {  

            $fileExists = true;
            $fileTempPath = $_FILES['image']['tmp_name'];
            $fileName     = $_FILES['image']['name'];
            $fileSize     = $_FILES['image']['size'];
            
            $dirArray = createFileDirectory("/../uploads/profilepictures", $fileName, "profilepictures", "img_$surname");

            // Finally, moving everything into the correct file destination
            // and also adding it to the DB
            $destPath = $dirArray['Destination_Path'];
            $fullPath = $dirArray['Full_Path'];

            if (!move_uploaded_file($fileTempPath, $fullPath)) {
                $errors['image'] = "File upload failed. Please check folder permissions.";
            }
        }
            // Only proceed if image move succeeded further
            if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // connecting to the DB
            $pdo = connectToDatabase();

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
            closeDatabase($pdo);
            header("Location: index.php?view=login&signup=success");
            exit;
        }
    }
}

// ==============================
// LOGIN FORM
// - Validates login form fields
// - Verifies password against DB hash
// - Stores user info in session (excl. password)
// - Redirects to timeline on success
// ==============================

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
        $user = getUserDetailsEmail($email);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'firstname' => $user['firstname'],
            'surname' => $user['surname'],
            'fullname' => $user['firstname'] . ' ' . $user['surname'],
            'gender' => $user['gender'],
            'profile_pic' => $user['profile_pic'],       
            'profile_bio' => $user['profile_bio'],     
            'theme' => $user['theme'],     
        ];

        // Redirect to timeline after login has been successful
        header("Location: index.php?view=timeline");
        exit;
    }
}

// ==============================
// PASSWORD RESET FORM
// - Validates email and checks if user exists
// - Validates and hashes new password
// - Updates password in DB
// - Redirects to login with reset success message
// ==============================

if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'passwordreset') {
    // Getting all of the values that have been posted through the form
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $passwordReType = trim($_POST['passwordretype'] ?? '');

    // Form validation
    // Validating the email using the built in method, and then also using our custom function to check the db
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";
    else if (!checkEmailExists($email)) $errors["email"] = "User does not exist";

    validatePasswords($password, $passwordReType, $errors);

    // if there are no errors, add user info to DB, then redirect to login page
    if (empty($errors)) { 

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // connecting to the DB
        $pdo = connectToDatabase();

        // update using placeholder
        $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE email = :email');

        // stopping SQL injection
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashedPassword);

        $stmt->execute();
        closeDatabase($pdo);
        header("Location: index.php?view=login&reset=success");
        exit;
    }
}

// ==============================
// ADD POST FORM
// - Validates caption & optional image upload
// - Restricts captions to 280 characters
// - Moves uploaded files into user folder
// - Inserts post record into DB
// - Redirects with post success/failure message
// ==============================

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
            
            $dirArray = createFileDirectory("/../uploads/posts/$folderName", $fileName, "posts", "post_", $folderName);


            $destPath = $dirArray['Destination_Path'];
            $fullPath = $dirArray['Full_Path'];

            // Finally, moving everything into the correct file destination
            // and also adding it to the DB
            if (move_uploaded_file($fileTempPath, $fullPath)) {
                addPostToDB($caption, $destPath);
                // forcing the files readable by server & browser
                chmod($fullPath, 0644);
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

// ==============================
// PROFILE EDIT FORM
// - Validates updated firstname, surname, and bio
// - Handles new profile picture upload (replaces old file)
// - Updates user record in DB
// - Reloads updated user info into session
// - Redirects back to profile page
// ==============================
if ($_SERVER["REQUEST_METHOD"] === 'POST' && $view === 'profile') {
    // Getting all of the values that have been posted through the form
    $name = trim($_POST['firstname'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $bio = trim($_POST['profile_bio'] ?? '');

    $email = $_SESSION['user']['email'];

    // Form validation
    validateString($name, 'firstname', $errors);
    validateString($surname, 'surname', $errors);

    if (strlen($bio) > 120) {
        $length = strlen($bio);
        $errors['bio'] = "$length / 120 Characters, (120 Max)";
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
        $destPath = $_SESSION['user']['profile_pic'];

        if (!empty($_FILES['image']['name'])) {  

            $fileExists = true;

            // Making sure the directory exists,
            // and if not making the directory with the correct
            // permissions in place
            $dirArray = createFileDirectory("/../uploads/profilepictures", $fileName, "profilepictures", "img_$surname");
            
            $destPath = $dirArray['Destination_Path'];
            $fullPath = $dirArray['Full_Path']; 

            // Delete old profile pic and making sure not to delete the placeholder icon
            $oldPic = $_SESSION['user']['profile_pic'];
            $oldPicPath = realpath(__DIR__ . '/../' . $oldPic);

            if ($oldPic && $oldPic !== 'icons/profile-picture-none.svg' && file_exists($oldPicPath)) {
                unlink($oldPicPath);
            }

            // I've had a lot of issues with file permissions, so if this fails on your side,
            // you need to make sure your phpserver has the rights to write and read files in the 
            // uploads folder and all subdirectories 
            if (!move_uploaded_file($fileTempPath, $fullPath)) {
                $errors['image'] = "File upload failed. Please check folder permissions.";
            }
            // Permissions change on the path
            chmod($fullPath, 0644);
        }

        // connecting to the DB
        $pdo = connectToDatabase();

        // insert using placeholder
        $stmt = $pdo->prepare('UPDATE users 
                        SET firstname = :firstname, 
                        surname = :surname, 
                        profile_pic = :profile_pic, 
                        profile_bio = :profile_bio
                        WHERE id = :id' );


        // stopping SQL injection
        $stmt->bindValue(':firstname', $name);
        $stmt->bindValue(':surname', $surname);
        $stmt->bindValue(':profile_pic', $destPath);
        $stmt->bindValue(':profile_bio', $bio);
        $stmt->bindValue(':id', $_SESSION['user']['id'], PDO::PARAM_INT);

        $stmt->execute();

        // Reloading all new details into the session
        $user = getUserDetailsEmail($_SESSION['user']['email']);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'firstname' => $user['firstname'],
            'surname' => $user['surname'],
            'fullname' => $user['firstname'] . ' ' . $user['surname'],
            'gender' => $user['gender'],
            'profile_pic' => $user['profile_pic'],       
            'profile_bio' => $user['profile_bio'],       
            'theme' => $user['theme'],       
        ];
        closeDatabase($pdo);
        header("Location: index.php?view=profile");
        exit;
    }
}

// ==============================
// END: Authentication & User Management
// ==============================

?>
