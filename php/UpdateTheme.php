<?php
    // This code is called by the theme mode JS in the profile
    // It basically changes the theme in the user DB so that on
    // page reload, the user has the theme they have selected. It also ensures
    // that the page doesn't have to reload before the theme is innitiated
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['theme'])) {
    // Getting values
    $theme = $_POST['theme'];
    $user_id = $_SESSION['user']['id'];

    $pdo = new PDO('mysql:host=localhost;dbname=frostbyte_social', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $stmt = $pdo->prepare("UPDATE users SET theme = :theme WHERE id = :id");
    $stmt->bindValue(':theme', $theme);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['user']['theme'] = $theme;
    exit;
}
?>