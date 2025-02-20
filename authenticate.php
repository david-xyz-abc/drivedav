<?php
session_start();

// Hard-coded credentials (replace with database in production)
$valid_username = "dav";
$valid_password = "123"; // Replace with your desired password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // For production, use password_hash() and password_verify()
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true; // Changed from 'logged_in' to 'loggedin'
        header("Location: explorer.php?folder=Home"); // Redirect to Home folder
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
?>
