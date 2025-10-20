<?php
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
?>