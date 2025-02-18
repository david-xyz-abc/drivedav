<?php
session_start();

// In a real application, store credentials securely in a database.
// Here we hard-code the valid username and password.
$valid_username = "davdrive";
$valid_password = "123"; // Replace with your desired password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // For production, consider using password_hash() and password_verify()
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        header("Location: explorer.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
