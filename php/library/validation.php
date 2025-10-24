<?php

// Library: validation.php
// Purpose: Centralized validation functions for form inputs and file uploads
// - validateString(): Validates names (non-empty, no numbers, length 3–25)
// - checkEmailExists(): Ensures unique email in 'users' table
// - checkPasswordIsCorrect(): Verifies login password against stored hash
// - validatePasswords(): Confirms password and retyped password match
// - validateFile(): Validates uploaded images (type, size, spoofing checks)
// - validateSignUp(): Full signup form validation (names, email, gender, password, profile picture)


// Validates a generic string field (e.g., firstname, surname)
// - Checks: not empty, only letters/valid chars, length between 3–25
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

// Checks if an email is already registered in the DB
// Returns true if it exists, false otherwise
function checkEmailExists($email) {
        // connecting to the DB
        $pdo = connectToDatabase();

        $stmt = $pdo->prepare('SELECT email FROM users WHERE email = :email LIMIT 1');
        $stmt->bindValue(':email', $email);

        $stmt->execute();
        closeDatabase($pdo);
        // if fetchColumn returns something, the email exists therefore
        // we cannot proceed with signup
        return (bool) $stmt->fetchColumn();

}

// Verifies login password against stored password hash
// Returns true if match, false otherwise
function checkPasswordIsCorrect($password, $email) {

    // connecting to the DB
    $pdo = connectToDatabase();

    $stmt = $pdo->prepare('SELECT password FROM users WHERE email = :email LIMIT 1');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $pass_hash = $stmt->fetchColumn();
    closeDatabase($pdo);
    
    return (bool) password_verify($password, $pass_hash);
}

// Confirms both password fields are filled and match
function validatePasswords($password, $passwordReType, &$errors) {
    if ($password === '' || $passwordReType === '') {
        $errors["password"] = "Fields cannot be left empty";
    } else if ($password !== $passwordReType) {
        $errors["password"] = "Passwords do not match";
    }
}

// Validates uploaded images
// - Checks extension (jpg/png), MIME type, file size (≤4MB), and spoofing
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

    // Email validation: syntax + uniqueness
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "Please enter a valid email address";
    else if (checkEmailExists($email)) $errors["email"] = "Email is already in use by another user";

    if ($gender === '') $errors['gender'] = 'Please select an option';

    validatePasswords($password, $passwordReType, $errors);

    // Validate profile picture if uploaded
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