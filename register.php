<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Email already exists";
        } else {
            $error = "Registration failed";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TaskFlow</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <i class="fas fa-tasks auth-icon"></i>
                <h1>Create your account</h1>
                <p>Start managing your tasks efficiently</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Create Account</button>
            </form>
            <p class="auth-links">Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </div>
</body>
</html>
