<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TaskFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
    <style>
            body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/task-img.jpg');
            background-size: cover;
            background-position: center;
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
        }
            .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1D4ED8;
            text-decoration: none;
        }
        .error{
            color: red;
        }

    </style>
    
</head>
<body>
    <div class="auth-containers">
        <div class="auth-box">
          <a href="#" class="logo">
            <i class="fas fa-tasks"></i>
            TaskFlow
         </a>
            <h1>Sign in to your account</h1>
           
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required placeholder="enter your email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label >
                    <input type="password" id="password" name="password" required placeholder="******">
                 <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
                </div>
                <button type="submit" class="btn-primary">Sign in</button>
            </form>
            <p class="auth-links">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>
