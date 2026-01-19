<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is NOT logged in
if (!isset($_SESSION['user_id'])) {
    // Agar hum root file (index.php) mein hain, toh rasta user/login.php hoga
    // Agar hum admin folder mein hote, toh ye alag hota.
    header("Location: user/login.php");
    exit();
}
?>