<?php
session_start();

// Hard-coded credentials
$valid_username = "dav";
$valid_password = "123";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        // Debug output
        file_put_contents('/tmp/auth_debug.log', "Authenticate: Session ID: " . session_id() . "\n", FILE_APPEND);
        file_put_contents('/tmp/auth_debug.log', "Authenticate: Loggedin set: " . var_export($_SESSION['loggedin'], true) . "\n", FILE_APPEND);
        file_put_contents('/tmp/auth_debug.log', "Authenticate: Full session: " . var_export($_SESSION, true) . "\n", FILE_APPEND);
        header("Location: explorer.php?folder=Home");
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        file_put_contents('/tmp/auth_debug.log', "Authenticate: Login failed\n", FILE_APPEND);
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
