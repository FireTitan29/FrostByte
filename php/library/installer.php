<?php
// installer.php
// Purpose: To ensure that the database is setup so that the program can work correctly

// Check if any user table exists (basically this is a way to test if DB is already set up or not)
function isDatabaseInitialized($pdo) {
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    return $stmt->rowCount() > 0;
}

// a function to run the SQL file
function runSQLFile(PDO $pdo, $filePath) {
    $sql = file_get_contents($filePath);

    // Remove comments that start with -- or #
    $sql = preg_replace('/--.*\n/', '', $sql);
    $sql = preg_replace('/#.*/', '', $sql);

    // Remove block comments /* ... */
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if ($stmt !== '') {
            $pdo->exec($stmt);
        }
    }
}

// Connect without DB
$pdo = new PDO("mysql:host=localhost", "root", ""); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create DB if missing
$pdo->exec("CREATE DATABASE IF NOT EXISTS frostbyte_social 
            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// Reconnect using the new DB
$pdo->exec("USE frostbyte_social");

if (!isDatabaseInitialized($pdo)) {

    // Run schema first to create the tables needed
    runSQLFile($pdo, __DIR__ . "/../../sql/frostbyte_social_tables.sql");

    // Insert sample data if it is in the file
    // so if you want to start fresh, delete the data.sql file from the sql folder
    if (file_exists( __DIR__ . "/../../sql/data.sql")) {
        runSQLFile($pdo, __DIR__ . "/../../sql/data.sql");
    }

}
?>