<?php
// includes/db.php – Database connection
// BrewMaster | ASB/2023/144

define('DB_HOST', 'localhost');
define('DB_USER', 'root');    // Change if needed
define('DB_PASS', '');        // Change if needed
define('DB_NAME', 'brewmaster');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("<div style='font-family:sans-serif;padding:2rem;color:#c00;'>
         <strong>Database connection failed:</strong> " . $conn->connect_error . "
         <br><small>Check your credentials in includes/db.php</small></div>");
}

$conn->set_charset("utf8mb4");
?>
