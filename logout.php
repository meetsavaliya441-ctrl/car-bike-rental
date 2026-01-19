<?php
require_once('includes/config.php');
session_start();
session_unset();
session_destroy();

// Redirect to login or home
header("Location: " . BASE_URL . "index.php");
exit();
?>