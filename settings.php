<?php
// Start the session to maintain user state
session_start();

// Include database connection and utility functions
require_once 'config.php';

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];

// Initialize error and success messages
$message = '';

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        
        // Update user information in database
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $user_id]);
        $message = 'Profile updated successfully';
    }
    
    // Change password
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Fetch current user data
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // Verify current password
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                // Update password in database
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
                $message = 'Password changed successfully';
            } else {
                $message = 'New passwords do not match';
            }
        } else {
            $message = 'Current password is incorrect';
        }
    }
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - TaskFlow</title>
    <!-- External CSS and Font dependencies -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
</head>
<body>
    <!-- Main Layout Container -->
    <div class="layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="dashboard.php" class="logo">
                    <i class="fas fa-tasks"></i>
                    TaskFlow
                </a>
                <button class="mobile-menu-close"><i class="fas fa-times"></i></button>
            </div>
            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" onclick="showAddTaskModal()"><i class="fas fa-plus"></i> Add Task</a>
                <a href="tasks.php"><i class="fas fa-tasks"></i> Task List</a>
                <a href="settings.php" class="active"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <button onclick="mobileMenuToggle()" class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
                <h1>Setting</h1>
                <div class="user-menu">
                    <div class="user-profile" onclick="toggleUserMenu()">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=1D4ED8&color=fff" alt="Profile">
                        <span><?php echo htmlspecialchars($user['name']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="settings.php"><i class="fas fa-user"></i> Profile Settings</a>
                        <a href="billing.php"><i class="fas fa-credit-card"></i> Billing</a>
                        <a href="functions/logout.php"><i class="fas fa-sign-out-alt"></i> logout</a>
                    </div>
                </div>
            </header>

            <!-- Error/Success Messages -->
            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Settings Content -->
            <div class="settings-container">
                <!-- Profile Settings Form -->
                <div class="settings-card">
                    <h2>Profile Settings</h2>
                    <form method="POST" class="settings-form">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input  type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly required>
                        </div>
                        <button type="submit" name="update_profile" class="btn-primary">Update Profile</button>
                    </form>
                </div>

                <!-- Password Change Form -->
                <div class="settings-card">
                    <h2>Change Password</h2>
                    <form method="POST" class="settings-form">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript for Mobile Menu -->
    <script>
        // Mobile menu functionality
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const sidebar = document.querySelector('.sidebar');

        mobileMenuToggle.addEventListener('click', () => {
            sidebar.classList.add('show');
        });

        mobileMenuClose.addEventListener('click', () => {
            sidebar.classList.remove('show');
        });

        // User menu toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // show add task Modal 
        function showAddTaskModal() {
            document.getElementById('addTaskModal').style.display = 'block';
        }
    </script>
</body>
</html>
