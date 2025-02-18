<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Self Hosted Google Drive - Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Poppins Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    /* Global Reset & Typography */
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      background: #121212;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    /* Login Container */
    .login-container {
      background: #1e1e1e;
      border: 1px solid #333;
      border-radius: 8px;
      padding: 30px;
      width: 350px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
      text-align: center;
    }
    /* Logo Placeholder */
    .logo {
      width: 80px;
      height: 80px;
      background: url('https://via.placeholder.com/80/ffffff/000000?text=Logo') no-repeat center center;
      background-size: cover;
      margin: 0 auto 15px;
      border-radius: 50%;
    }

    /* Project Name */
    .project-name {
      font-size: 18px;
      font-weight: 500;
      margin-bottom: 25px;
      color: #fff;
    }

    /* Error Message */
    .error {
      color: #f44336;
      margin-bottom: 15px;
    }

    /* Form Fields */
    .form-group {
      text-align: left;
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-size: 14px;
      color: #ccc;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 4px;
      color: #fff;
      font-size: 14px;
      transition: border-color 0.3s;
    }
    .form-group input:focus {
      outline: none;
      border-color: #4a90e2;
    }

    /* Submit Button */
    .button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #555, #777);
      border: none;
      border-radius: 4px;
      color: #fff;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }
    .button:hover {
      background: linear-gradient(135deg, #777, #555);
      transform: scale(1.03);
    }
    .button:active {
      transform: scale(0.98);
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="logo"></div>
    <div class="project-name">Self Hosted Google Drive</div>

    <?php 
      if (isset($_SESSION['error'])) {
          echo '<div class="error">' . htmlspecialchars($_SESSION['error']) . '</div>';
          unset($_SESSION['error']);
      }
    ?>

    <form action="authenticate.php" method="post">
      <div class="form-group">
        <label for="username">Username or Email</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="button">Sign In</button>
    </form>
  </div>
</body>
</html>
