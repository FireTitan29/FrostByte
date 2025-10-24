<?php 
// Controller: SearchUsers.php
// Purpose: Handles search requests for users via the search bar
// - Accepts POST requests with a 'find' parameter
// - Queries the 'users' table for matches in firstname, surname, or email
// - Excludes the current logged-in user from results
// - Returns results as JSON for display in the search bar
// - Redirects to homepage if accessed directly without POST

session_start();

include '../library/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['find'])) {
    
    // If the user hasn't entered at least 3 characters, show error message
    if (strlen($_POST['find']) < 2) {
        echo json_encode('');
    } else {
        $pdo = connectToDatabase();
        $find = "%" . $_POST['find'] . "%";

        $stmt = $pdo->prepare("SELECT id, firstname, surname, profile_pic, email
                            FROM users 
                            WHERE firstname LIKE :find OR surname LIKE :find OR email LIKE :find");
        $stmt->bindValue(':find', $find, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        // Cleaning out results to take out the current user
        foreach ($results as $user) {
            if ($user['id'] === $_SESSION['user']['id']) {
                
            } else {
                array_push($users, $user);
            }
        }
        echo json_encode($users);
        closeDatabase($pdo);
    }
} else {
        // Block direct URL access (no POST data), send user back to homepage (index)
        header("Location: ../../index.php");
        exit;
}
?>