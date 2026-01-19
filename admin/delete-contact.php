<?php
session_start();
require_once('../includes/db.php');

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Secure delete using prepared statement
    $query = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        echo "<script>alert('Message successfully removed.'); window.location.href='manage-contact.php';</script>";
    } else {
        echo "<script>alert('Error: Could not delete message.'); window.location.href='manage-contact.php';</script>";
    }
    $stmt->close();
}
?>