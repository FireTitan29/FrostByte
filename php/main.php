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
    // DATABASE FUNCTIONS:

    // Because I am going to be connecting to the DB a lot, I wrote this to speed
    // that process up a bit, and make the code easier to read.
    // All this does it connect to the database, and then return the $pdo if
    // the connection was successful. Otherwise it shoots out an error message
    function connectToDatabase() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return $pdo;
        } catch (PDOException $e) {
            die("Connection to the DB failed, here's why: " . $e->getMessage());
        }
    }

    // This function adds the posts to the DB using a SQL insert
    function addPostToDB($caption, $imagePath = '') {
        // connecting to the DB
        $pdo = connectToDatabase();

        $stmt = $pdo->prepare(
        'INSERT INTO posts (user_id, caption, image_path)
        VALUES (:user_id, :caption, :image_path)');

        // stopping SQL injection
        $stmt->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->bindValue(':caption', $caption, PDO::PARAM_STR);
        $stmt->bindValue(':image_path', $imagePath, PDO::PARAM_STR);
        
        $stmt->execute();
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

    // Getting User Details using their email
    function getUserDetailsEmail($email) {

        $pdo = connectToDatabase();
        
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    // Getting a user's details using their user ID
    function getUserDetailsID($user_id) {

        $pdo = connectToDatabase();
        
        $stmt = $pdo->prepare('SELECT firstname, surname, email, gender, profile_pic, profile_bio FROM users WHERE id = :user_id');
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
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
    // VALIDATION:

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
            $pdo = connectToDatabase();

            $stmt = $pdo->prepare('SELECT email FROM users WHERE email = :email LIMIT 1');
            $stmt->bindValue(':email', $email);

            $stmt->execute();

            // if fetchColumn returns something, the email exists therefore
            // we cannot proceed with signup
            return (bool) $stmt->fetchColumn();

    }

    function checkPasswordIsCorrect($password, $email) {
        // connecting to the DB
        $pdo = connectToDatabase();

        $stmt = $pdo->prepare('SELECT password FROM users WHERE email = :email LIMIT 1');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $pass_hash = $stmt->fetchColumn();
        
        // if the password is correct, then we return true, otherwise they aren't the same
        return (bool) password_verify($password, $pass_hash);
    }

    function validatePasswords($password, $passwordReType, &$errors) {
        if ($password === '' || $passwordReType === '') {
            $errors["password"] = "Fields cannot be left empty";
        } else if ($password !== $passwordReType) {
            $errors["password"] = "Passwords do not match";
        }
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

    // Made SignUp & Login Form validations into it's own function to improve readbility of the
    // SERVER REQUEST === POST sections
    function validateSignUp($name, $surname, $email, $gender, $password, $passwordReType, &$errors) {
        // Form validation
        validateString($name, 'firstname', $errors);
        validateString($surname, 'surname', $errors);

        // Validating the email using the built in method, and then also using our custom function to check the db
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";
        else if (checkEmailExists($email)) $errors["email"] = "Email is already in use by another user";

        if ($gender === '') $errors['gender'] = 'Please select an option';

        validatePasswords($password, $passwordReType, $errors);

        // Validating Profile Picture if user has uploaded something
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
    }


    // -----------------------------------------------------------
    // FORMS:
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
                header("Location: index.php?view=login&signup=success");
                exit;
            }
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

    // Editing the profile infomation
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
            $length = strlen($caption);
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

            header("Location: index.php?view=profile");
            exit;
        }
    }

    // -----------------------------------------------------------
    // POSTS:

    function didUserLike($user_id, $post_id) {
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare('SELECT 1 FROM post_likes WHERE post_id = :post_id AND user_id = :user_id LIMIT 1');
        $stmt->bindValue(':post_id', $post_id);
        $stmt->bindValue(':user_id', $user_id);

        $stmt->execute();
        $result =  $stmt->fetchColumn();
        if (!$result) {
            return false;
        }
            return true;

    }

    function getUserOfPost($post_id) {
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE post_id = :post_id');
        $stmt->bindValue(':post_id', $post_id);
        $stmt->execute();
        $result =  $stmt->fetchColumn();

        return $result;
    }

    // this function includes a post depending on whether it has an image
    // or not. It puts it in the correct format.
    function includePost($userName, $profilePicture, $timeStamp, $post_id ,$imageName = '', $caption = '', $likesCount = 0) {
        $likeBool = didUserLike($_SESSION['user']['id'], $post_id);
        $user_id = getUserOfPost($post_id);
        include "php/Post.php";
    }

    // This function finds all posts relating to a user / all users
    // and then displays them on the page
    function findAndDisplayPosts($user_id = '')
    {

        // connecting to the DB
        $pdo = connectToDatabase();
        
        // This is the part where we see if we are on the timeline (select *), or on the user's profile (select where user_id))
        $stmt = '';   
        if ($user_id === '') {
            $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC');
        } else {
            $stmt = $pdo->prepare('SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC');
            $stmt->bindValue(':user_id', $user_id);
        }
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // After we fetch them all, only if there are posts to be displayed, do we display them
        // otherwise we return false
        if (sizeof($result) > 0) {
            // This is now the part when the posts are actually created
            foreach ($result AS $post) {
                $user = getUserDetailsID($post['user_id']);
                $fullName = $user['firstname'] . ' ' . $user['surname'];
                includePost($fullName, $user['profile_pic'], $post['created_at'], $post['post_id'], $post['image_path'], $post['caption'], $post['likes']);
            }
            return true;
        } else {
            return false;
        }
    }

    
    // -----------------------------------------------------------
    // MESSAGES:
    function getConversationId($sender_id, $receiver_id) {
        $pdo = connectToDatabase();
    
        $stmt = $pdo->prepare('
            SELECT conversation_id 
            FROM conversations
            WHERE (user1_id = :sender AND user2_id = :receiver)
            OR (user1_id = :receiver AND user2_id = :sender)
            LIMIT 1
        ');
        $stmt->bindValue(':sender', $sender_id);
        $stmt->bindValue(':receiver', $receiver_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    function includeMessage($sender_id, $textBody, $timeStamp, $is_read, $addDateLine) {
        $date = date('d M Y', strtotime($timeStamp));
        $timeStamp = date('H:i', strtotime($timeStamp));

        $user_id = $_SESSION['user']['id'];
        if ($sender_id === $user_id) {
            $sent = true;
        } else {
            $sent = false;
        }
        include "php/message-bubble.php";
    }

    function findAndDisplayMessages($send_to, $user_id)
    {
        $chatID = getConversationId($user_id, $send_to);
        if (!$chatID) {
            return false;
        } else {
            $pdo = connectToDatabase();
            $stmt = $pdo->prepare('SELECT * FROM messages WHERE conversation_id = :chat_id');
            $stmt->bindValue(':chat_id',$chatID);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $lastDate = null;

            foreach ($messages AS $message) {
                $addDateLine = false;

                $currentDate = date('d M Y', strtotime($message['created_at']));

                if ($lastDate !== $currentDate) {
                    $addDateLine = true;
                    $lastDate = $currentDate;
                }
                includeMessage($message['sender_id'], $message['text_body'],
                 $message['created_at'], $message['is_read'], $addDateLine);
            }
        }
    }

function findAndDisplayActiveChats($user_id) {
    $pdo = connectToDatabase();

    // Get all conversations this user is part of
    $stmt = $pdo->prepare('
        SELECT conversation_id, user1_id, user2_id 
        FROM conversations 
        WHERE user1_id = :user_id OR user2_id = :user_id
    ');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$conversations) {
        return false;
    }

    foreach ($conversations as $conv) {
        // Get the last message for this conversation
        $stmt = $pdo->prepare('
            SELECT * FROM messages 
            WHERE conversation_id = :chat_id 
            ORDER BY created_at DESC 
            LIMIT 1
        ');
        $stmt->bindValue(':chat_id', $conv['conversation_id']);
        $stmt->execute();
        $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lastMessage) {
            $rawTime = $lastMessage['created_at'];
            $messageTime = strtotime($rawTime);

            // check if it's today, if it is, show time, otherwise, show date
            if (date('Y-m-d') === date('Y-m-d', $messageTime)) {
                $formattedTime = date('H:i A', $messageTime);
            } else {
                $formattedTime = date('d M Y', $messageTime);
            }
            
            $profilePicture = '';

            // Figure out who the "other" user is
            if ($conv['user1_id'] == $user_id) {
                $sendTo = getUserDetailsID($conv['user2_id']);
                $profilePicture = $sendTo['profile_pic'];
                $thisUserID = $conv['user2_id'];
            } else {
                $sendTo = getUserDetailsID($conv['user1_id']);
                $profilePicture = $sendTo['profile_pic'];
                $thisUserID = $conv['user1_id'];
            }
            $textBody = htmlspecialchars($lastMessage['text_body']);
            $senderId = htmlspecialchars($lastMessage['sender_id']);
            // Pass message + user info to your include
            include 'php/SingleMessage.php';
        }
    }
}
?>