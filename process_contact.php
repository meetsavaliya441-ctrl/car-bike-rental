<?php
// Start session if required for headers or config
session_start();
require_once('includes/config.php');
require_once('includes/db.php');

if(isset($_POST['submit_contact'])) {
    
    // 1. Receive data from the form
    $name = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // 2. Validate required fields
    if(empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('Error: Please fill all required fields.'); window.history.back();</script>";
        exit();
    }

    // 3. Prepare Database Statement (Security against SQL Injection)
    // Note: Ensure the $conn variable is correctly defined in includes/db.php
    $query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    
    if($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if($stmt->execute()) {
            // Success alert and redirect
            echo "<script>alert('Success: Your message has been sent!'); window.location.href='contact.php';</script>";
        } else {
            // Error during execution
            echo "Execution Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in preparing the query (Check if table 'contact_messages' exists)
        echo "Database Query Error: " . $conn->error;
    }
} else {
    // Redirect back to contact page if accessed directly
    header("Location: contact.php");
    exit();
}
?>